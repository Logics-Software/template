<?php
/**
 * User Management Controller
 */
class UserController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function index($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        try {
            $page = (int) ($request->input('page') ?? 1);
            $search = $request->input('search') ?? '';
            $status = $request->input('status') ?? '';
            $role = $request->input('role') ?? '';
            $perPage = (int) ($request->input('per_page') ?? DEFAULT_PAGE_SIZE);
            $sort = $request->input('sort') ?? 'id';
            $order = $request->input('order') ?? 'asc';

            $where = '1=1';
            $whereParams = [];

            if ($search) {
                $where .= ' AND (username LIKE :search1 OR namalengkap LIKE :search2 OR email LIKE :search3)';
                $whereParams['search1'] = "%{$search}%";
                $whereParams['search2'] = "%{$search}%";
                $whereParams['search3'] = "%{$search}%";
            }

            if ($status) {
                $where .= ' AND status = :status';
                $whereParams['status'] = $status;
            }

            if ($role) {
                $where .= ' AND role = :role';
                $whereParams['role'] = $role;
            }

            // Validate sort field
            $allowedSorts = ['id', 'username', 'namalengkap', 'email', 'role', 'status', 'created_at'];
            if (!in_array($sort, $allowedSorts)) {
                $sort = 'id';
            }

            // Validate order
            if (!in_array(strtolower($order), ['asc', 'desc'])) {
                $order = 'asc';
            }

            $users = $this->userModel->paginate($page, $perPage, $where, $whereParams, $sort, $order);

            $this->view('users/index', [
                'title' => 'Users',
                'current_page' => 'users',
                'users' => $users,
                'search' => $search,
                'status' => $status,
                'role' => $role,
                'csrf_token' => $this->csrfToken()
            ]);
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("UserController index error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Show a user-friendly error message
            $this->withError('An error occurred while loading users. Please try again.');
            $this->redirect('/users');
        }
    }

    public function create($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->view('users/create', [
            'title' => 'Create User',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function store($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $validator = $request->validate([
            'username' => 'required|min:3|unique:users',
            'namalengkap' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/users/create');
            }
        }

        $data = [
            'username' => $request->input('username'),
            'namalengkap' => $request->input('namalengkap'),
            'email' => $request->input('email'),
            'password' => $request->input('password'), // Will be hashed in model
            'role' => $request->input('role'),
            'status' => 'aktif' // Default status for CRUD operations
        ];

        try {
            $this->userModel->beginTransaction();
            $userId = $this->userModel->createUser($data, 'aktif');
            
            // Handle file upload if picture is provided
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/users/';
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                $file = $_FILES['picture'];
                
                // Validate file type
                if (!in_array($file['type'], $allowedTypes)) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'], 400);
                    } else {
                        $this->withError('Invalid file type');
                        $this->redirect('/users/create');
                    }
                    return;
                }

                // Validate file size
                if ($file['size'] > $maxSize) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'File size too large. Maximum 5MB allowed.'], 400);
                    } else {
                        $this->withError('File size too large');
                        $this->redirect('/users/create');
                    }
                    return;
                }

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
                $filepath = $uploadDir . $filename;

                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Update user with picture path
                    $this->userModel->updatePicture($userId, $filepath);
                } else {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Failed to upload file'], 500);
                    } else {
                        $this->withError('Failed to upload file');
                        $this->redirect('/users/create');
                    }
                    return;
                }
            }
            
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User created successfully']);
            } else {
                $this->withSuccess('User created successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to create user'], 500);
            } else {
                $this->withError('Failed to create user');
                $this->redirect('/users/create');
            }
        }
    }

    public function show($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
        }

        $this->view('users/show', [
            'title' => 'User Details',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function edit($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        
        $user = $this->userModel->find($id);

        if (!$user) {
            $this->withError('User not found');
            $this->redirect('/users');
            return;
        }
        $this->view('users/edit', [
            'title' => 'Edit User',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function update($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
        }

        
        $validator = $request->validate([
            'username' => 'required|min:3',
            'namalengkap' => 'required|min:3',
            'email' => 'required|email',
            'role' => 'required'
        ]);


        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect("/users/{$id}/edit");
            }
        }

        $data = [
            'username' => $request->input('username'),
            'namalengkap' => $request->input('namalengkap'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'status' => $request->input('status') ?? 'aktif'
        ];

        // Only update password if provided
        if ($request->input('password')) {
            $data['password'] = password_hash($request->input('password'), PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->update($id, $data);
            
            // Handle file upload if picture is provided
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/users/';
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                $file = $_FILES['picture'];
                
                // Validate file type
                if (!in_array($file['type'], $allowedTypes)) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'], 400);
                    } else {
                        $this->withError('Invalid file type');
                        $this->redirect("/users/{$id}/edit");
                    }
                    return;
                }

                // Validate file size
                if ($file['size'] > $maxSize) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'File size too large. Maximum 5MB allowed.'], 400);
                    } else {
                        $this->withError('File size too large');
                        $this->redirect("/users/{$id}/edit");
                    }
                    return;
                }

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'user_' . $id . '_' . time() . '.' . $extension;
                $filepath = $uploadDir . $filename;

                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Delete old picture if exists
                    if ($user['picture'] && file_exists($user['picture'])) {
                        unlink($user['picture']);
                    }
                    
                    // Update user with picture path
                    $this->userModel->updatePicture($id, $filepath);
                } else {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Failed to upload file'], 500);
                    } else {
                        $this->withError('Failed to upload file');
                        $this->redirect("/users/{$id}/edit");
                    }
                    return;
                }
            }
            
            $this->userModel->commit();
            
            // Clear user cache
            Cache::forget("user_{$id}");
            Cache::forget("user_email_{$data['email']}");
            Cache::forget("user_username_{$data['username']}");
            Cache::forget("users_aktif");
            Cache::forget("users_role_{$data['role']}");

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User updated successfully']);
            } else {
                $this->withSuccess('User updated successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to update user'], 500);
            } else {
                $this->withError('Failed to update user');
                $this->redirect("/users/{$id}/edit");
            }
        }
    }

    public function destroy($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->delete($id);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                $this->withSuccess('User deleted successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to delete user'], 500);
            } else {
                $this->withError('Failed to delete user');
                $this->redirect('/users');
            }
        }
    }


    public function profile($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $user = $this->userModel->find(Session::get('user_id'));
        

        $this->view('users/profile', [
            'title' => 'Profile',
            'current_page' => 'profile',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function updateProfile($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            $this->withError('User not found');
            $this->redirect('/profile');
            return;
        }

        // Get form data
        $namalengkap = $request->input('namalengkap');
        $email = $request->input('email');

        // Validate required fields
        if (empty($namalengkap) || empty($email)) {
            $this->withError('Name and email are required');
            $this->redirect('/profile');
            return;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->withError('Invalid email format');
            $this->redirect('/profile');
            return;
        }

        // Check if email is already used by another user
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $userId) {
            $this->withError('Email is already in use by another user');
            $this->redirect('/profile');
            return;
        }

        // Prepare update data
        $updateData = [
            'namalengkap' => $namalengkap,
            'email' => $email
        ];


        // Handle file upload
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/users/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExtension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $fileName = 'user_' . $userId . '_' . time() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;
            
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                $this->withError('Invalid file type. Only JPG, PNG, GIF, and WebP are allowed');
                $this->redirect('/profile');
                return;
            }
            
            // Validate file size (2MB max)
            if ($_FILES['picture']['size'] > 2 * 1024 * 1024) {
                $this->withError('File size too large. Maximum 2MB allowed');
                $this->redirect('/profile');
                return;
            }
            
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadPath)) {
                // Delete old picture if exists
                if (!empty($user['picture']) && file_exists($uploadDir . $user['picture'])) {
                    unlink($uploadDir . $user['picture']);
                }
                
                $updateData['picture'] = $fileName;
            }
        }

        // Update user data
        if ($this->userModel->update($userId, $updateData)) {
            // Update session data
            Session::set('user_name', $namalengkap);
            Session::set('user_email', $email);
            if (isset($updateData['picture'])) {
                Session::set('user_picture', $updateData['picture']);
            }
            
            // Redirect to a page that will use JavaScript to go back
            $this->redirect('/profile/updated');
        } else {
            $this->withError('Failed to update profile');
        $this->redirect('/profile');
        }
    }

    public function profileUpdated($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // This page will use JavaScript to go back to previous page
        $this->view('users/profile-updated', [
            'title' => 'Profile Updated',
            'current_page' => 'profile'
        ]);
    }

    public function changePassword($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $user = $this->userModel->find(Session::get('user_id'));
        
        if (!$user) {
            $this->withError('User not found');
            $this->redirect('/profile');
            return;
        }

        $this->view('users/change-password', [
            'title' => 'Change Password',
            'current_page' => 'change-password',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function updatePassword($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            $this->withError('User not found');
            $this->redirect('/change-password');
            return;
        }

        // Get form data
        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('confirm_password');

        // Validate required fields
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->withError('All password fields are required');
            $this->redirect('/change-password');
            return;
        }

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            $this->withError('Current password is incorrect');
            $this->redirect('/change-password');
            return;
        }

        // Validate new password
        if ($newPassword !== $confirmPassword) {
            $this->withError('New passwords do not match');
            $this->redirect('/change-password');
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $this->withError('New password must be at least 6 characters long');
            $this->redirect('/change-password');
            return;
        }

        // Check if new password is different from current
        if (password_verify($newPassword, $user['password'])) {
            $this->withError('New password must be different from current password');
            $this->redirect('/change-password');
            return;
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->userModel->update($userId, ['password' => $hashedPassword])) {
            $this->redirect('/change-password/updated');
        } else {
            $this->withError('Failed to update password');
            $this->redirect('/change-password');
        }
    }

    public function passwordUpdated($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // This page will use JavaScript to go back to previous page
        $this->view('users/password-updated', [
            'title' => 'Password Updated',
            'current_page' => 'change-password'
        ]);
    }

    public function uploadPicture($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            if ($request->isAjax()) {
                $this->json(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }

        $userId = $params[0] ?? Session::get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
            return;
        }

        // Handle file upload
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/images/users/';
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            $file = $_FILES['picture'];
            
            // Validate file type
            if (!in_array($file['type'], $allowedTypes)) {
                if ($request->isAjax()) {
                    $this->json(['error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'], 400);
                } else {
                    $this->withError('Invalid file type');
                    $this->redirect('/profile');
                }
                return;
            }

            // Validate file size
            if ($file['size'] > $maxSize) {
                if ($request->isAjax()) {
                    $this->json(['error' => 'File size too large. Maximum 5MB allowed.'], 400);
                } else {
                    $this->withError('File size too large');
                    $this->redirect('/profile');
                }
                return;
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                try {
                    $this->userModel->beginTransaction();
                    
                    // Delete old picture if exists
                    if ($user['picture'] && file_exists($user['picture'])) {
                        unlink($user['picture']);
                    }
                    
                    // Update user picture
                    $this->userModel->updatePicture($userId, $filepath);
                    $this->userModel->commit();

                    if ($request->isAjax()) {
                        $this->json([
                            'success' => true, 
                            'message' => 'Picture updated successfully',
                            'picture_url' => APP_URL . '/' . $filepath
                        ]);
                    } else {
                        $this->withSuccess('Picture updated successfully');
                        $this->redirect('/profile');
                    }
                } catch (Exception $e) {
                    $this->userModel->rollback();
                    // Clean up uploaded file
                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }
                    
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Failed to update picture'], 500);
                    } else {
                        $this->withError('Failed to update picture');
                        $this->redirect('/profile');
                    }
                }
            } else {
                if ($request->isAjax()) {
                    $this->json(['error' => 'Failed to upload file'], 500);
                } else {
                    $this->withError('Failed to upload file');
                    $this->redirect('/profile');
                }
            }
        } else {
            if ($request->isAjax()) {
                $this->json(['error' => 'No file uploaded'], 400);
            } else {
                $this->withError('No file uploaded');
                $this->redirect('/profile');
            }
        }
    }

    public function activateUser($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
            return;
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->activate($id);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User activated successfully']);
            } else {
                $this->withSuccess('User activated successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to activate user'], 500);
            } else {
                $this->withError('Failed to activate user');
                $this->redirect('/users');
            }
        }
    }

    public function deactivateUser($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
            return;
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->deactivate($id);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User deactivated successfully']);
            } else {
                $this->withSuccess('User deactivated successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to deactivate user'], 500);
            } else {
                $this->withError('Failed to deactivate user');
                $this->redirect('/users');
            }
        }
    }

    public function rejectUser($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
            return;
        }

        if ($user['status'] !== 'register') {
            if ($request->isAjax()) {
                $this->json(['error' => 'User is not in pending status'], 400);
            } else {
                $this->withError('User is not in pending status');
                $this->redirect('/users');
            }
            return;
        }

        try {
            // Delete user picture if exists
            if ($user['picture'] && file_exists($user['picture'])) {
                unlink($user['picture']);
            }

            $this->userModel->beginTransaction();
            $this->userModel->delete($id);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User rejected and deleted successfully']);
            } else {
                $this->withSuccess('User rejected and deleted successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to reject user'], 500);
            } else {
                $this->withError('Failed to reject user');
                $this->redirect('/users');
            }
        }
    }
}
