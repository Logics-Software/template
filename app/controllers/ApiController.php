<?php
/**
 * API Controller for AJAX requests
 */
class ApiController extends BaseController
{
    public function getTheme()
    {
        $theme = $_COOKIE[THEME_COOKIE_NAME] ?? DEFAULT_THEME;
        $this->json(['theme' => $theme]);
    }

    public function setTheme()
    {
        $theme = $this->input('theme', DEFAULT_THEME);
        
        if (!in_array($theme, ['light', 'dark', 'auto'])) {
            $this->json(['error' => 'Invalid theme'], 400);
        }

        setcookie(THEME_COOKIE_NAME, $theme, time() + (365 * 24 * 60 * 60), '/');
        $this->json(['success' => true, 'theme' => $theme]);
    }

    public function searchUsers()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $query = $this->input('q', '');
        $page = (int) $this->input('page', 1);
        $perPage = (int) $this->input('per_page', DEFAULT_PAGE_SIZE);

        $userModel = new User();
        $result = $userModel->search($query, ['name', 'email'], 'status = :status', ['status' => 'active']);
        
        $this->json([
            'data' => $result,
            'total' => count($result),
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Check session validity
     */
    public function checkSession()
    {
        $isValid = Session::isValid();
        $timeRemaining = 0;
        
        if ($isValid) {
            $lastActivity = Session::get('_last_activity', time());
            $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
            $timeRemaining = $sessionLifetime - (time() - $lastActivity);
        }
        
        $this->json([
            'valid' => $isValid,
            'timeRemaining' => max(0, $timeRemaining),
            'user' => $isValid ? [
                'id' => Session::get('user_id'),
                'name' => Session::get('user_name'),
                'email' => Session::get('user_email'),
                'role' => Session::get('user_role')
            ] : null
        ]);
    }

    /**
     * Extend session lifetime
     */
    public function extendSession()
    {
        if (Session::extendSession()) {
            // Update last login when session is extended
            $userId = Session::get('user_id');
            if ($userId) {
                $userModel = new User();
                $userModel->updateLastLogin($userId);
            }
            
            $this->json([
                'success' => true,
                'message' => 'Session extended successfully',
                'timeRemaining' => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600
            ]);
        } else {
            $this->json([
                'success' => false,
                'error' => 'Unable to extend session'
            ], 401);
        }
    }

    /**
     * Get session warning info
     */
    public function getSessionWarning()
    {
        if (!Session::isValid()) {
            $this->json(['warning' => false]);
            return;
        }
        
        $lastActivity = Session::get('_last_activity', time());
        $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
        $warningTime = defined('SESSION_WARNING_TIME') ? SESSION_WARNING_TIME : 300; // 5 minutes
        $timeRemaining = $sessionLifetime - (time() - $lastActivity);
        
        $this->json([
            'warning' => $timeRemaining <= $warningTime,
            'timeRemaining' => max(0, $timeRemaining),
            'warningTime' => $warningTime
        ]);
    }

    /**
     * Get unread message count
     */
    public function getUnreadMessageCount()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $messageModel = new Message();
        $count = $messageModel->getUnreadCount(Session::get('user_id'));
        
        $this->json(['count' => $count]);
    }

    /**
     * Get recent unread messages for header dropdown
     */
    public function getRecentMessages()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $messageModel = new Message();
        $messages = $messageModel->getInboxMessages(Session::get('user_id'), 10, 0); // Get more messages to filter
        
        // Filter only unread messages and format for display
        $formattedMessages = [];
        foreach ($messages as $message) {
            // Only include unread messages
            if (!$message['is_read']) {
                $formattedMessages[] = [
                    'id' => $message['id'],
                    'subject' => $message['subject'],
                    'sender_name' => $message['sender_name'],
                    'sender_picture' => $message['sender_picture'],
                    'created_at' => $message['created_at'],
                    'is_read' => $message['is_read'],
                    'url' => APP_URL . '/messages/' . $message['id']
                ];
                
                // Limit to 5 unread messages
                if (count($formattedMessages) >= 5) {
                    break;
                }
            }
        }
        
        $this->json([
            'success' => true,
            'messages' => $formattedMessages,
            'count' => count($formattedMessages)
        ]);
    }

    /**
     * Get unread message count for header badge
     */
    public function getUnreadCount()
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        $messageModel = new Message();
        $unreadCount = $messageModel->getUnreadCount(Session::get('user_id'));
        
        $this->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }
}
