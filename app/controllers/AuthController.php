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
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (!$validator->validate()) {
            $this->withErrors($validator->errors());
            $this->redirect('/login');
        }

        $email = $this->input('email');
        $password = $this->input('password');

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::regenerate();

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
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        if (!$validator->validate()) {
            $this->withErrors($validator->errors());
            $this->redirect('/register');
        }

        $data = [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'password' => password_hash($this->input('password'), PASSWORD_DEFAULT),
            'role' => 'user',
            'status' => 'active'
        ];

        try {
            $this->userModel->beginTransaction();
            $userId = $this->userModel->create($data);
            $this->userModel->commit();

            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Registration successful']);
            } else {
                $this->withSuccess('Registration successful. Please login.');
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
