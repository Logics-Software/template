<?php
/**
 * Lock Screen Controller
 */
class LockScreenController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    /**
     * Display lock screen
     */
    public function index()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }

        // Get user data from session
        $userData = [
            'user_name' => Session::get('user_name') ?? 'User',
            'user_email' => Session::get('user_email') ?? 'user@example.com',
            'user_picture' => Session::get('user_picture') ?? null,
        ];

        // Render lock screen view
        $this->view('auth/lock-screen', [
            'title' => 'Lock Screen - Hando PHP MVC',
            'user_name' => $userData['user_name'],
            'user_email' => $userData['user_email'],
            'user_picture' => $userData['user_picture'],
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * Unlock screen with password
     */
    public function unlock()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }

        // Check CSRF token
        if (!isset($_POST['_token']) || !Session::validateCSRF($_POST['_token'])) {
            Session::flash('error', 'Invalid security token. Please try again.');
            header('Location: ' . APP_URL . '/lock-screen');
            exit;
        }

        // Validate input
        $password = trim($_POST['password'] ?? '');
        $remember_me = isset($_POST['remember_me']);

        if (empty($password)) {
            Session::flash('error', 'Password is required.');
            header('Location: ' . APP_URL . '/lock-screen');
            exit;
        }

        // Get user ID from session
        $userId = Session::get('user_id');

        try {
            // Get user data from database
            $user = $this->userModel->find($userId);
            
            if (!$user) {
                Session::flash('error', 'User not found.');
                header('Location: ' . APP_URL . '/login');
                exit;
            }

            // Verify password
            if (!password_verify($password, $user['password'])) {
                Session::flash('error', 'Invalid password. Please try again.');
                header('Location: ' . APP_URL . '/lock-screen');
                exit;
            }

            // Update last login
            $this->userModel->updateLastLogin($userId);

            // Set remember me if requested
            if ($remember_me) {
                // Set cookie for 30 days
                $remember_token = bin2hex(random_bytes(32));
                setcookie('remember_token', $remember_token, time() + (30 * 24 * 60 * 60), '/');
                // You might want to store this token in database for security
            }

            // Clear lock screen session flag if you have one
            Session::remove('locked');

            // Redirect to dashboard
            header('Location: ' . APP_URL . '/dashboard');
            exit;

        } catch (Exception $e) {
            Session::flash('error', 'An error occurred. Please try again.');
            header('Location: ' . APP_URL . '/lock-screen');
            exit;
        }
    }

    /**
     * Lock the screen (called when user is inactive)
     */
    public function lock()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }

        // Set lock flag
        Session::set('locked', true);

        // Redirect to lock screen
        header('Location: ' . APP_URL . '/lock-screen');
        exit;
    }

}
