<?php

class Message extends Model
{
    protected $table = 'messages';

    /**
     * Create a new message with recipients
     */
    public function createMessage($data, $recipientIds = [])
    {
        try {
            $this->beginTransaction();

            // Create the message
            $messageData = [
                'sender_id' => $data['sender_id'],
                'subject' => $data['subject'],
                'content' => $data['content'],
                'message_type' => $data['message_type'] ?? 'direct',
                'status' => $data['status'] ?? 'sent'
            ];

            $messageId = $this->create($messageData);

            if (!$messageId) {
                throw new Exception('Failed to create message');
            }

            // Add recipients
            if (!empty($recipientIds)) {
                foreach ($recipientIds as $recipientId) {
                    $this->db->query(
                        "INSERT INTO message_recipients (message_id, recipient_id) VALUES (?, ?)",
                        [$messageId, $recipientId]
                    );
                }
            }

            $this->commit();
            return $messageId;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Get messages for a specific user (inbox)
     */
    public function getInboxMessages($userId, $limit = 20, $offset = 0)
    {
        $sql = "
            SELECT 
                m.*,
                u.namalengkap as sender_name,
                u.email as sender_email,
                u.picture as sender_picture,
                mr.is_read,
                mr.read_at
            FROM messages m
            INNER JOIN message_recipients mr ON m.id = mr.message_id
            INNER JOIN users u ON m.sender_id = u.id
            WHERE mr.recipient_id = ?
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        return $this->db->query($sql, [$userId, $limit, $offset])->fetchAll();
    }

    /**
     * Get sent messages for a specific user
     */
    public function getSentMessages($userId, $limit = 20, $offset = 0)
    {
        $sql = "
            SELECT 
                m.*,
                GROUP_CONCAT(u.namalengkap SEPARATOR ', ') as recipients
            FROM messages m
            LEFT JOIN message_recipients mr ON m.id = mr.message_id
            LEFT JOIN users u ON mr.recipient_id = u.id
            WHERE m.sender_id = ?
            GROUP BY m.id
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        return $this->db->query($sql, [$userId, $limit, $offset])->fetchAll();
    }

    /**
     * Get a specific message with recipients
     */
    public function getMessageWithRecipients($messageId, $userId)
    {
        // Check if user has access to this message
        $accessCheck = $this->db->query(
            "SELECT COUNT(*) as count FROM messages m 
             LEFT JOIN message_recipients mr ON m.id = mr.message_id 
             WHERE m.id = ? AND (m.sender_id = ? OR mr.recipient_id = ?)",
            [$messageId, $userId, $userId]
        )->fetch();

        if ($accessCheck['count'] == 0) {
            return null;
        }

        // Get message details
        $message = $this->db->query(
            "SELECT m.*, u.namalengkap as sender_name, u.email as sender_email, u.picture as sender_picture 
             FROM messages m 
             INNER JOIN users u ON m.sender_id = u.id 
             WHERE m.id = ?",
            [$messageId]
        )->fetch();

        if (!$message) {
            return null;
        }

        // Get recipients
        $recipients = $this->db->query(
            "SELECT mr.*, u.namalengkap as recipient_name, u.email as recipient_email, u.picture as recipient_picture
             FROM message_recipients mr
             INNER JOIN users u ON mr.recipient_id = u.id
             WHERE mr.message_id = ?",
            [$messageId]
        )->fetchAll();

        $message['recipients'] = $recipients;
        return $message;
    }

    /**
     * Mark message as read
     */
    public function markAsRead($messageId, $userId)
    {
        $sql = "
            UPDATE message_recipients 
            SET is_read = TRUE, read_at = NOW() 
            WHERE message_id = ? AND recipient_id = ?
        ";
        
        return $this->db->query($sql, [$messageId, $userId]);
    }

    /**
     * Mark all messages as read for user (update message_recipients table)
     */
    public function markAllAsRead($userId)
    {
        $sql = "
            UPDATE message_recipients 
            SET is_read = 1, read_at = NOW() 
            WHERE recipient_id = ? AND is_read = 0
        ";
        
        return $this->db->query($sql, [$userId]);
    }

    /**
     * Get unread message count for user
     */
    public function getUnreadCount($userId)
    {
        $result = $this->db->query(
            "SELECT COUNT(*) as count 
             FROM message_recipients 
             WHERE recipient_id = ? AND is_read = FALSE",
            [$userId]
        )->fetch();
        
        return $result['count'];
    }

    /**
     * Get all users for recipient selection
     */
    public function getAllUsers($excludeUserId = null)
    {
        $sql = "SELECT id, namalengkap as name, email FROM users WHERE status = 'aktif'";
        $params = [];
        
        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }
        
        $sql .= " ORDER BY namalengkap ASC";
        
        return $this->db->query($sql, $params)->fetchAll();
    }

    /**
     * Delete message (soft delete for sender, hard delete for recipients)
     */
    public function deleteMessage($messageId, $userId)
    {
        try {
            $this->beginTransaction();

            // Check if user is sender
            $message = $this->db->query(
                "SELECT sender_id FROM messages WHERE id = ?",
                [$messageId]
            )->fetch();

            if (!$message) {
                throw new Exception('Message not found');
            }

            if ($message['sender_id'] == $userId) {
                // User is sender - delete the entire message
                $this->db->query("DELETE FROM message_attachments WHERE message_id = ?", [$messageId]);
                $this->db->query("DELETE FROM message_recipients WHERE message_id = ?", [$messageId]);
                $this->db->query("DELETE FROM messages WHERE id = ?", [$messageId]);
            } else {
                // User is recipient - remove from recipients only
                $this->db->query(
                    "DELETE FROM message_recipients WHERE message_id = ? AND recipient_id = ?",
                    [$messageId, $userId]
                );
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Search messages
     */
    public function searchMessages($userId, $searchTerm, $limit = 20, $offset = 0)
    {
        $sql = "
            SELECT DISTINCT
                m.*,
                u.namalengkap as sender_name,
                u.email as sender_email,
                mr.is_read,
                mr.read_at
            FROM messages m
            INNER JOIN message_recipients mr ON m.id = mr.message_id
            INNER JOIN users u ON m.sender_id = u.id
            WHERE mr.recipient_id = ? 
            AND (m.subject LIKE ? OR m.content LIKE ?)
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        $searchPattern = "%{$searchTerm}%";
        return $this->db->query($sql, [$userId, $searchPattern, $searchPattern, $limit, $offset])->fetchAll();
    }

    public function searchUsers($search = '', $role = '', $excludeUserId = null)
    {
        $sql = "SELECT id, namalengkap, username, email, role, picture, status FROM users WHERE status = 'aktif'";
        $params = [];
        
        // Exclude current user if provided
        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }
        
        if (!empty($search)) {
            $sql .= " AND (namalengkap LIKE ? OR username LIKE ? OR email LIKE ?)";
            $searchPattern = "%{$search}%";
            $params[] = $searchPattern;
            $params[] = $searchPattern;
            $params[] = $searchPattern;
        }
        
        if (!empty($role)) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY namalengkap ASC LIMIT 50";
        
        return $this->db->query($sql, $params)->fetchAll();
    }

    public function saveAttachment($messageId, $filename, $filepath, $mimetype, $filesize)
    {
        $sql = "INSERT INTO message_attachments (message_id, filename, original_name, file_path, file_size, mime_type) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->db->query($sql, [$messageId, $filename, $filename, $filepath, $filesize, $mimetype]);
    }

    public function getAttachments($messageId)
    {
        $sql = "SELECT * FROM message_attachments WHERE message_id = ? ORDER BY created_at ASC";
        return $this->db->query($sql, [$messageId])->fetchAll();
    }

    public function getMessageForReply($messageId, $userId)
    {
        $sql = "SELECT m.*, u.namalengkap as sender_name, u.email as sender_email 
                FROM messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE m.id = ? AND (m.sender_id = ? OR EXISTS (
                    SELECT 1 FROM message_recipients mr 
                    WHERE mr.message_id = m.id AND mr.recipient_id = ?
                ))";
        
        $message = $this->db->query($sql, [$messageId, $userId, $userId])->fetch();
        
        if ($message) {
            // Get sender info for reply
            $message['reply_sender'] = [
                'id' => $message['sender_id'],
                'name' => $message['sender_name'],
                'email' => $message['sender_email']
            ];
        }
        
        return $message;
    }

    public function getMessageForForward($messageId, $userId)
    {
        $sql = "SELECT m.*, u.namalengkap as sender_name, u.email as sender_email 
                FROM messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE m.id = ? AND (m.sender_id = ? OR EXISTS (
                    SELECT 1 FROM message_recipients mr 
                    WHERE mr.message_id = m.id AND mr.recipient_id = ?
                ))";
        
        $message = $this->db->query($sql, [$messageId, $userId, $userId])->fetch();
        
        if ($message) {
            // Get original sender info for forward
            $message['forward_sender'] = [
                'id' => $message['sender_id'],
                'name' => $message['sender_name'],
                'email' => $message['sender_email']
            ];
            
            // Get attachments for forward
            $message['attachments'] = $this->getAttachments($messageId);
        }
        
        return $message;
    }
    
    /**
     * Get paginated inbox messages
     */
    public function getPaginatedInboxMessages($userId, $page = 1, $perPage = 20, $search = '')
    {
        $offset = ($page - 1) * $perPage;
        
        // Build WHERE clause for search
        $whereClause = 'WHERE mr.recipient_id = ?';
        $params = [$userId];
        
        if (!empty($search)) {
            $whereClause .= ' AND (m.subject LIKE ? OR m.content LIKE ? OR u.namalengkap LIKE ?)';
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }
        
        // Get total count
        $countSql = "
            SELECT COUNT(*) as total 
            FROM messages m
            INNER JOIN message_recipients mr ON m.id = mr.message_id
            INNER JOIN users u ON m.sender_id = u.id
            {$whereClause}
        ";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        
        // Get paginated data
        $sql = "
            SELECT 
                m.*,
                u.namalengkap as sender_name,
                u.email as sender_email,
                u.picture as sender_picture,
                mr.is_read,
                mr.read_at
            FROM messages m
            INNER JOIN message_recipients mr ON m.id = mr.message_id
            INNER JOIN users u ON m.sender_id = u.id
            {$whereClause}
            ORDER BY m.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $data = $this->db->fetchAll($sql, $params);
        
        $totalPages = ceil($total / $perPage);
        $hasNext = $page < $totalPages;
        $hasPrev = $page > 1;
        
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'has_next' => $hasNext,
            'has_prev' => $hasPrev
        ];
    }
    
    /**
     * Get paginated sent messages
     */
    public function getPaginatedSentMessages($userId, $page = 1, $perPage = 20, $search = '')
    {
        $offset = ($page - 1) * $perPage;
        
        // Build WHERE clause for search
        $whereClause = 'WHERE m.sender_id = ?';
        $params = [$userId];
        
        if (!empty($search)) {
            $whereClause .= ' AND (m.subject LIKE ? OR m.content LIKE ?)';
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm]);
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM messages m {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Get paginated data
        $sql = "
            SELECT 
                m.*,
                (SELECT COUNT(*) FROM message_recipients mr WHERE mr.message_id = m.id) as recipient_count,
                (SELECT GROUP_CONCAT(u.namalengkap SEPARATOR ', ') 
                 FROM message_recipients mr 
                 LEFT JOIN users u ON mr.recipient_id = u.id 
                 WHERE mr.message_id = m.id) as recipient_names
            FROM messages m
            {$whereClause}
            ORDER BY m.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $data = $this->db->fetchAll($sql, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
            'has_next' => $page < ceil($total / $perPage),
            'has_prev' => $page > 1
        ];
    }
}
