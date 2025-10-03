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
    public function index($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Get user data from session
        $userData = [
            'user_name' => Session::get('user_name') ?? 'User',
            'user_email' => Session::get('user_email') ?? 'user@example.com',
            'user_picture' => Session::get('user_picture') ?? null,
        ];

        // Render lock screen view
        $this->view('auth/lock-screen', [
            'title' => 'Lock Screen - Logics PHP MVC',
            'user_name' => $userData['user_name'],
            'user_email' => $userData['user_email'],
            'user_picture' => $userData['user_picture'],
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * Unlock screen with password
     */
    public function unlock($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Modern validation
        $validator = $request->validate([
            'password' => 'required|min:1'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/lock-screen');
            }
        }

        $password = $request->input('password');
        $remember_me = $request->input('remember_me') ? true : false;

        // Get user ID from session
        $userId = Session::get('user_id');

        try {
            $this->userModel->beginTransaction();
            
            // Get user data from database
            $user = $this->userModel->find($userId);
            
            if (!$user) {
                $this->userModel->rollback();
                if ($request->isAjax()) {
                    $this->json(['error' => 'User not found'], 404);
                } else {
                    $this->withError('User not found');
                    $this->redirect('/login');
                }
                return;
            }

            // Verify password
            if (!password_verify($password, $user['password'])) {
                $this->userModel->rollback();
                if ($request->isAjax()) {
                    $this->json(['error' => 'Invalid password. Please try again.'], 401);
                } else {
                    $this->withError('Invalid password. Please try again.');
                    $this->redirect('/lock-screen');
                }
                return;
            }

            // Update last login
            $updateResult = $this->userModel->updateLastLogin($userId);
            
            if (!$updateResult) {
                $this->userModel->rollback();
                if ($request->isAjax()) {
                    $this->json(['error' => 'Failed to update login time'], 500);
                } else {
                    $this->withError('Failed to update login time');
                    $this->redirect('/lock-screen');
                }
                return;
            }

            // Set remember me if requested
            if ($remember_me) {
                // Set cookie for 30 days
                $remember_token = bin2hex(random_bytes(32));
                setcookie('remember_token', $remember_token, time() + (30 * 24 * 60 * 60), '/');
                // You might want to store this token in database for security
            }

            // Clear lock screen session flag if you have one
            Session::remove('locked');

            // Commit transaction
            $this->userModel->commit();

            // Redirect to dashboard
            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'Screen unlocked successfully', 'redirect' => '/dashboard']);
            } else {
                $this->withSuccess('Screen unlocked successfully');
                $this->redirect('/dashboard');
            }

        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'An error occurred. Please try again.'], 500);
            } else {
                $this->withError('An error occurred. Please try again.');
                $this->redirect('/lock-screen');
            }
        }
    }

    /**
     * Lock the screen (called when user is inactive)
     */
    public function lock($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Set lock flag
        Session::set('locked', true);

        // Redirect to lock screen
        if ($request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Screen locked', 'redirect' => '/lock-screen']);
        } else {
            $this->redirect('/lock-screen');
        }
    }

}
