<?php
/**
 * User Model
 */
require_once 'app/core/Cache.php';

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'namalengkap', 'email', 'password', 'role', 'registration_reason', 'picture', 'status', 'lastlogin'];
    protected $hidden = ['password'];

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->fetch($sql, ['email' => $email]);
    }

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        return $this->db->fetch($sql, ['username' => $username]);
    }

    public function findByRole($role)
    {
        return $this->findAll('role = :role', ['role' => $role]);
    }

    public function getAktifUsers()
    {
        return $this->findAll('status = :status', ['status' => 'aktif']);
    }

    public function getNonAktifUsers()
    {
        return $this->findAll('status = :status', ['status' => 'non_aktif']);
    }

    public function getRegisterUsers()
    {
        return $this->findAll('status = :status', ['status' => 'register']);
    }

    public function updateLastLogin($id)
    {
        $data = ['lastlogin' => date('Y-m-d H:i:s')];
        $result = $this->update($id, $data);
        
        return $result;
    }

    public function changePassword($id, $newPassword)
    {
        $data = ['password' => password_hash($newPassword, PASSWORD_DEFAULT)];
        return $this->update($id, $data);
    }

    public function activate($id)
    {
        $data = ['status' => 'aktif'];
        return $this->update($id, $data);
    }

    public function deactivate($id)
    {
        $data = ['status' => 'non_aktif'];
        return $this->update($id, $data);
    }

    public function updatePicture($id, $picturePath)
    {
        $data = ['picture' => $picturePath];
        return $this->update($id, $data);
    }

    public function createUser($data, $status = 'aktif')
    {
        $data['status'] = $status;
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }

    public function registerUser($data)
    {
        return $this->createUser($data, 'register');
    }

    public function canLogin($user)
    {
        return $user && $user['status'] === 'aktif';
    }

    public function getStatusMessage($status)
    {
        switch ($status) {
            case 'register':
                return 'Akun anda belum diaktifkan';
            case 'non_aktif':
                return 'Akun anda telah dinonaktifkan';
            default:
                return '';
        }
    }

    /**
     * Get total users count
     */
    public function getTotalUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Get active users count
     */
    public function getActiveUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'aktif'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Get pending users count
     */
    public function getPendingUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'register'";
        $result = $this->db->fetch($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Get team members for management
     */
    public function getTeamMembers($managerId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id != :managerId AND status = 'aktif' ORDER BY namalengkap ASC";
        return $this->db->fetchAll($sql, ['managerId' => $managerId]);
    }

    /**
     * Update user preferences
     */
    public function updatePreferences($userId, $data)
    {
        // For now, we'll store preferences in a JSON field or separate table
        // This is a placeholder implementation
        $preferences = json_encode($data);
        $updateData = ['preferences' => $preferences];
        
        return $this->update($userId, $updateData);
    }
}
