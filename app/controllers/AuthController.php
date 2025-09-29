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

    public function login()
    {
        if (Session::has('user_id')) {
            $this->redirect('/dashboard');
        }

        
        $this->view('auth/login', [
            'title' => 'Login',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function authenticate()
    {
        $validator = $this->validate([
            'username_email' => 'required',
            'password' => 'required|min:6'
        ]);

        if (!$validator->validate()) {
            $this->withErrors($validator->errors());
            $this->redirect('/login');
        }

        $usernameEmail = $this->input('username_email');
        $password = $this->input('password');

        // Try to find user by email first, then by username
        $user = $this->userModel->findByEmail($usernameEmail);
        if (!$user) {
            $user = $this->userModel->findByUsername($usernameEmail);
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

            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['namalengkap']);
            Session::set('user_email', $user['email']);
            Session::set('user_role', $user['role']);
            Session::set('user_username', $user['username']);
            Session::set('user_picture', $user['picture']);
            Session::regenerate();

            // Update last login
            $updateResult = $this->userModel->updateLastLogin($user['id']);

            if ($this->isAjax()) {
                $this->json(['success' => true, 'redirect' => '/dashboard']);
            } else {
                $this->redirect('/dashboard');
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

    public function register()
    {
        if (Session::has('user_id')) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/register', [
            'title' => 'Register',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function store()
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

    public function logout()
    {
        Session::destroy();
        $this->redirect('/login');
    }
}
