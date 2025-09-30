<?php

require_once __DIR__ . '/../models/Message.php';

class MessageController extends BaseController
{
    private $messageModel;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->messageModel = new Message();
        $this->userModel = new User();
    }

    /**
     * Display inbox messages
     */
    public function index()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $page = $this->request->input('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get messages
        $messages = $this->messageModel->getInboxMessages($userId, $limit, $offset);
        $unreadCount = $this->messageModel->getUnreadCount($userId);

        $this->view('messages/index', [
            'title' => 'Pesan Masuk',
            'current_page' => 'messages',
            'messages' => $messages,
            'unread_count' => $unreadCount,
            'page' => $page
        ]);
    }

    /**
     * Display sent messages
     */
    public function sent()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $page = $this->request->input('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get sent messages
        $messages = $this->messageModel->getSentMessages($userId, $limit, $offset);

        $this->view('messages/sent', [
            'title' => 'Pesan Terkirim',
            'current_page' => 'messages',
            'messages' => $messages,
            'page' => $page
        ]);
    }

    /**
     * Show compose message form
     */
    public function create()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Get all users for recipient selection
        $users = $this->messageModel->getAllUsers(Session::get('user_id'));
        
        // Initialize reply and forward data
        $replyData = null;
        $forwardData = null;
        
        // Check if this is a reply to a message
        $replyId = $this->request->input('reply');
        if ($replyId) {
            $replyData = $this->messageModel->getMessageForReply($replyId, Session::get('user_id'));
        }
        
        // Check if this is a forward of a message
        $forwardId = $this->request->input('forward');
        if ($forwardId) {
            $forwardData = $this->messageModel->getMessageForForward($forwardId, Session::get('user_id'));
        }

        $this->view('messages/create', [
            'title' => 'Tulis Pesan',
            'current_page' => 'messages',
            'users' => $users,
            'reply_data' => $replyData,
            'forward_data' => $forwardData
        ]);
    }

    /**
     * Store new message
     */
    public function store()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $data = $this->request->input();
        $userId = Session::get('user_id');

        // Validate required fields
        $requiredFields = ['subject', 'content', 'recipients'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => "Field {$field} harus diisi"]);
                } else {
                    $this->withError("Field {$field} harus diisi");
                    $this->redirect('/messages/create');
                }
                return;
            }
        }

        try {
            // Prepare message data
            $messageData = [
                'sender_id' => $userId,
                'subject' => $data['subject'],
                'content' => $data['content'],
                'message_type' => $data['message_type'] ?? 'direct',
                'status' => 'sent'
            ];

            // Parse recipients (can be comma-separated or array)
            $recipientIds = [];
            if (is_string($data['recipients'])) {
                $recipientIds = array_filter(array_map('trim', explode(',', $data['recipients'])));
            } elseif (is_array($data['recipients'])) {
                $recipientIds = array_filter($data['recipients']);
            }

            if (empty($recipientIds)) {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Pilih minimal satu penerima']);
                } else {
                    $this->withError('Pilih minimal satu penerima');
                    $this->redirect('/messages/create');
                }
                return;
            }

            // Create message
            $messageId = $this->messageModel->createMessage($messageData, $recipientIds);

            if ($messageId) {
                // Handle attachments
                if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
                    $this->handleAttachments($messageId, $_FILES['attachments']);
                }
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Pesan berhasil dikirim', 'redirect' => '/messages?sent=true']);
                } else {
                    $this->withSuccess('Pesan berhasil dikirim');
                    $this->redirect('/messages?sent=true');
                }
            } else {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Gagal mengirim pesan']);
                } else {
                    $this->withError('Gagal mengirim pesan');
                    $this->redirect('/messages/create');
                }
            }
        } catch (Exception $e) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
            } else {
                $this->withError('Terjadi kesalahan: ' . $e->getMessage());
                $this->redirect('/messages/create');
            }
        }
    }

    /**
     * Show specific message
     */
    public function show($request = null, $response = null, $routeParams = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $messageId = $routeParams[0] ?? $this->request->input('id');
        $userId = Session::get('user_id');

        if (!$messageId) {
            $this->redirect('/messages');
            return;
        }

        // Get message with recipients
        $message = $this->messageModel->getMessageWithRecipients($messageId, $userId);

        if (!$message) {
            $this->withError('Pesan tidak ditemukan atau Anda tidak memiliki akses');
            $this->redirect('/messages');
            return;
        }

        // Get attachments
        $message['attachments'] = $this->messageModel->getAttachments($messageId);

        // Check if user is recipient and mark as read if needed
        $isRecipient = false;
        foreach ($message['recipients'] as $recipient) {
            if ($recipient['recipient_id'] == $userId) {
                $isRecipient = true;
                if (!$recipient['is_read']) {
                    $this->messageModel->markAsRead($messageId, $userId);
                }
                break;
            }
        }

        $this->view('messages/show', [
            'title' => 'Detail Pesan',
            'current_page' => 'messages',
            'message' => $message,
            'is_recipient' => $isRecipient
        ]);
    }

    /**
     * Delete message
     */
    public function destroy($request = null, $response = null, $routeParams = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $messageId = $routeParams[0] ?? $this->request->input('id');
        $userId = Session::get('user_id');

        if (!$messageId) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'ID pesan tidak valid']);
            } else {
                $this->withError('ID pesan tidak valid');
                $this->redirect('/messages');
            }
            return;
        }

        try {
            $result = $this->messageModel->deleteMessage($messageId, $userId);

            if ($result) {
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Pesan berhasil dihapus']);
                } else {
                    $this->withSuccess('Pesan berhasil dihapus');
                    $this->redirect('/messages');
                }
            } else {
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Gagal menghapus pesan']);
                } else {
                    $this->withError('Gagal menghapus pesan');
                    $this->redirect('/messages');
                }
            }
        } catch (Exception $e) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
            } else {
                $this->withError('Terjadi kesalahan: ' . $e->getMessage());
                $this->redirect('/messages');
            }
        }
    }

    /**
     * Search messages
     */
    public function search()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $searchTerm = $this->request->input('q', '');
        $page = $this->request->input('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        if (empty($searchTerm)) {
            $this->redirect('/messages');
            return;
        }

        // Search messages
        $messages = $this->messageModel->searchMessages($userId, $searchTerm, $limit, $offset);

        $this->view('messages/search', [
            'title' => 'Hasil Pencarian',
            'current_page' => 'messages',
            'messages' => $messages,
            'search_term' => $searchTerm,
            'page' => $page
        ]);
    }

    /**
     * Get unread count (AJAX)
     */
    public function getUnreadCount()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->json(['success' => false, 'message' => 'Not authenticated'], 401);
            return;
        }

        $userId = Session::get('user_id');
        $unreadCount = $this->messageModel->getUnreadCount($userId);

        $this->json(['success' => true, 'unread_count' => $unreadCount]);
    }

    /**
     * Mark all messages as read (AJAX)
     */
    public function markAllAsRead()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->json(['success' => false, 'message' => 'Not authenticated'], 401);
            return;
        }

        $userId = Session::get('user_id');

        try {
            $result = $this->messageModel->markAllAsRead($userId);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'All messages marked as read']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to mark messages as read']);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Failed to mark messages as read']);
        }
    }

    /**
     * Mark message as read (AJAX)
     */
    public function markAsRead()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->json(['success' => false, 'message' => 'Not authenticated'], 401);
            return;
        }

        $messageId = $this->request->input('message_id');
        $userId = Session::get('user_id');

        if (!$messageId) {
            $this->json(['success' => false, 'message' => 'Message ID required']);
            return;
        }

        try {
            $result = $this->messageModel->markAsRead($messageId, $userId);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Message marked as read']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to mark as read']);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function searchUsers($request, $response)
    {
        try {
            $search = $request->get('search', '');
            $role = $request->get('role', '');
            $currentUserId = Session::get('user_id');
            
            $users = $this->messageModel->searchUsers($search, $role, $currentUserId);
            
            // Always return JSON for API endpoint
            $this->json(['success' => true, 'users' => $users]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    private function handleAttachments($messageId, $files)
    {
        $uploadDir = 'assets/uploads/attachments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = $files['name'][$i];
                $fileSize = $files['size'][$i];
                $fileTmp = $files['tmp_name'][$i];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Validate file type
                if (!in_array($fileExt, $allowedTypes)) {
                    continue; // Skip invalid files
                }

                // Validate file size
                if ($fileSize > $maxSize) {
                    continue; // Skip oversized files
                }

                // Generate unique filename
                $newFileName = 'msg_' . $messageId . '_' . time() . '_' . $i . '.' . $fileExt;
                $filePath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $filePath)) {
                    // Save attachment info to database
                    $this->messageModel->saveAttachment($messageId, $fileName, $filePath, $files['type'][$i], $fileSize);
                }
            }
        }
    }
}
