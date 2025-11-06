<?php
/**
 * Authentication Controller
 */
class AuthController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login($request = null, $response = null, $params = [])
    {
        if (Session::has('user_id')) {
            $this->redirect('/dashboard');
        }

        
        $this->view('auth/login', [
            'title' => 'Login',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function authenticate($request = null, $response = null, $params = [])
    {
        try {
            $validator = $this->validate([
                'username_email' => 'required',
                'password' => 'required|min:6'
            ]);

            if (!$validator->validate()) {
                $this->withErrors($validator->errors());
                $this->redirect('/login');
                return;
            }
        } catch (Exception $e) {
            error_log("Auth validation error: " . $e->getMessage());
            $this->withError("Validation error occurred");
            $this->redirect('/login');
            return;
        }

        $usernameEmail = $this->input('username_email');
        $password = $this->input('password');

        try {
            // Try to find user by email first, then by username
            $user = $this->userModel->findByEmail($usernameEmail);
            if (!$user) {
                $user = $this->userModel->findByUsername($usernameEmail);
            }
        } catch (Exception $e) {
            error_log("Database query error: " . $e->getMessage());
            $this->withError("Database error occurred");
            $this->redirect('/login');
            return;
        }

        if ($user && password_verify($password, $user['password'])) {
            // Check if user can login (status must be 'aktif')
            if (!$this->userModel->canLogin($user)) {
                $statusMessage = $this->userModel->getStatusMessage($user['status']);
                if ($this->isAjax()) {
                    $this->json(['error' => $statusMessage], 403);
                } else {
                    $this->withError($statusMessage);
                    $this->redirect('/login');
                }
                return;
            }

            // Set basic user session
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['namalengkap']);
            Session::set('user_email', $user['email']);
            Session::set('user_role', $user['role']);
            Session::set('user_username', $user['username']);
            Session::set('user_picture', $user['picture']);
            
            // Initialize session activity tracking
            Session::set('_last_activity', time());
            Session::set('_last_regeneration', time());
            
            Session::regenerate();

            // Handle Remember Me functionality
            $rememberMe = $this->input('remember');
            if ($rememberMe) {
                Session::setRememberMe($user['id']);
            }

            // Update last login
            $updateResult = $this->userModel->updateLastLogin($user['id']);

            // Create login log
            try {
                $loginLogModel = new LoginLog();
                $sessionToken = bin2hex(random_bytes(32)); // Generate session token using same pattern as remember token
                $ipAddress = $this->getClientIp();
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
                
                $loginLogId = $loginLogModel->createLog($user['id'], $sessionToken, $ipAddress, $userAgent);
                
                // Store session token in session for logout tracking
                Session::set('_session_token', $sessionToken);
            } catch (Exception $e) {
                // Log error but don't break login flow
                error_log("Login log creation error: " . $e->getMessage());
            }

            // Get user's menu groups
            $usersMenuModel = new UsersMenu();
            $userMenuGroups = $usersMenuModel->getUserMenuGroups($user['id']);
            
            if (empty($userMenuGroups)) {
                // No menu groups assigned, proceed to dashboard
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'redirect' => '/dashboard']);
                } else {
                    $this->redirect('/dashboard');
                }
            } elseif (count($userMenuGroups) === 1) {
                // Only one menu group, set it directly to session
                Session::set('active_menu_group_id', $userMenuGroups[0]['id']);
                Session::set('active_menu_group_name', $userMenuGroups[0]['name']);
                
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'redirect' => '/dashboard']);
                } else {
                    $this->redirect('/dashboard');
                }
            } else {
                // Multiple menu groups, show modal selection
                Session::set('pending_menu_selection', true);
                Session::set('available_menu_groups', $userMenuGroups);
                
                if ($this->isAjax()) {
                    $this->json([
                        'success' => true, 
                        'show_menu_selection' => true,
                        'groups' => $userMenuGroups,
                        'redirect' => '/dashboard'
                    ]);
                } else {
                    $this->redirect('/dashboard');
                }
            }
        } else {
            if ($this->isAjax()) {
                $this->json(['error' => 'Invalid credentials'], 401);
            } else {
                $this->withError('Invalid email or password');
                $this->redirect('/login');
            }
        }
    }

    public function register($request = null, $response = null, $params = [])
    {
        if (Session::has('user_id')) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/register', [
            'title' => 'Register',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function store($request = null, $response = null, $params = [])
    {
        $validator = $this->validate([
            'username' => 'required|min:3|unique:users',
            'namalengkap' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:user,sales,customer',
            'registration_reason' => 'required|min:10',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        if (!$validator->validate()) {
            $this->withErrors($validator->errors());
            $this->redirect('/register');
        }

        $data = [
            'username' => $this->input('username'),
            'namalengkap' => $this->input('namalengkap'),
            'email' => $this->input('email'),
            'role' => $this->input('role'),
            'registration_reason' => $this->input('registration_reason'),
            'password' => $this->input('password') // Will be hashed in model
        ];

        try {
            $this->userModel->beginTransaction();
            $userId = $this->userModel->registerUser($data); // This sets status to 'register'
            
            // Handle profile picture upload
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['picture'];
                
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowedTypes)) {
                    $this->userModel->rollback();
                    $this->withError('Invalid file type. Please upload JPG, PNG, GIF, or WEBP image.');
                    $this->redirect('/register');
                    return;
                }
                
                // Validate file size (5MB max)
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($file['size'] > $maxSize) {
                    $this->userModel->rollback();
                    $this->withError('File too large. Please upload an image smaller than 5MB.');
                    $this->redirect('/register');
                    return;
                }
                
                // Create upload directory
                $uploadDir = APP_PATH . '/assets/images/users/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
                $filepath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Update user with picture path
                    $this->userModel->updatePicture($userId, 'assets/images/users/' . $filename);
                } else {
                    $this->userModel->rollback();
                    $this->withError('Failed to upload profile picture.');
                    $this->redirect('/register');
                    return;
                }
            }
            
            // Assign default menu group based on user role
            $usersMenuModel = new UsersMenu();
            $usersMenuModel->assignDefaultMenuByRole($userId, $data['role']);
            
            $this->userModel->commit();

            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Registration successful. Your account is pending activation.']);
            } else {
                $this->withSuccess('Registration successful. Your account is pending activation.');
                $this->redirect('/login');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($this->isAjax()) {
                $this->json(['error' => 'Registration failed'], 500);
            } else {
                $this->withError('Registration failed. Please try again.');
                $this->redirect('/register');
            }
        }
    }

    public function forgotPassword($request = null, $response = null, $params = [])
    {
        if (Session::has('user_id')) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/forgot-password', [
            'title' => 'Lupa Password',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function sendPasswordReset($request = null, $response = null, $params = [])
    {
        try {
            $validator = $this->validate([
                'email' => 'required|email'
            ]);

            if (!$validator->validate()) {
                $this->withErrors($validator->errors());
                $this->redirect('/forgot-password');
                return;
            }
        } catch (Exception $e) {
            error_log("Forgot password validation error: " . $e->getMessage());
            $this->withError("Terjadi kesalahan validasi");
            $this->redirect('/forgot-password');
            return;
        }

        $email = $this->input('email');

        // Check if email is empty (additional check)
        if (empty(trim($email))) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Email harus diisi'], 400);
            } else {
                $this->withError('Email harus diisi');
                $this->redirect('/forgot-password');
            }
            return;
        }

        try {
            // Check if user exists with this email
            $user = $this->userModel->findByEmail($email);
            
            if ($user) {
                // Generate a simple password reset token (in real app, use proper token system)
                $resetToken = bin2hex(random_bytes(32));
                $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Store reset token in database (you might want to create a password_resets table)
                // For now, we'll just show success message
                
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Password akan dikirim lewat email']);
                } else {
                    $this->withSuccess('Password akan dikirim lewat email');
                    $this->redirect('/login');
                }
            } else {
                // Show specific error message for email not found
                if ($this->isAjax()) {
                    $this->json(['error' => 'Email tidak ditemukan dalam database'], 404);
                } else {
                    $this->withError('Email tidak ditemukan dalam database');
                    $this->redirect('/forgot-password');
                }
            }
        } catch (Exception $e) {
            error_log("Database query error: " . $e->getMessage());
            if ($this->isAjax()) {
                $this->json(['error' => 'Terjadi kesalahan database'], 500);
            } else {
                $this->withError('Terjadi kesalahan database');
                $this->redirect('/forgot-password');
            }
        }
    }

    public function setMenuGroup($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $groupId = $this->input('group_id');

        if (!$groupId) {
            $this->json(['error' => 'Menu group harus dipilih'], 400);
            return;
        }

        // Verify that the group is in available groups
        $availableGroups = Session::get('available_menu_groups');
        
        if (!$availableGroups) {
            $this->json(['error' => 'Session expired, please login again'], 401);
            return;
        }

        $selectedGroup = null;
        foreach ($availableGroups as $group) {
            if ($group['id'] == $groupId) {
                $selectedGroup = $group;
                break;
            }
        }

        if (!$selectedGroup) {
            $this->json(['error' => 'Menu group tidak valid'], 400);
            return;
        }

        // Set the selected group to session
        Session::set('active_menu_group_id', $selectedGroup['id']);
        Session::set('active_menu_group_name', $selectedGroup['name']);

        // Clear pending selection flags
        Session::remove('pending_menu_selection');
        Session::remove('available_menu_groups');

        $this->json([
            'success' => true, 
            'message' => 'Menu group berhasil dipilih',
            'redirect' => '/dashboard'
        ]);
    }

    public function logout($request = null, $response = null, $params = [])
    {
        Session::logout();
        $this->redirect('/login');
    }
}
