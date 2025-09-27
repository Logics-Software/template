<?php
/**
 * User Model
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role', 'status'];
    protected $hidden = ['password'];

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->fetch($sql, ['email' => $email]);
    }

    public function findByRole($role)
    {
        return $this->findAll('role = :role', ['role' => $role]);
    }

    public function getActiveUsers()
    {
        return $this->findAll('status = :status', ['status' => 'active']);
    }

    public function getInactiveUsers()
    {
        return $this->findAll('status = :status', ['status' => 'inactive']);
    }

    public function updateLastLogin($id)
    {
        $data = ['last_login' => date('Y-m-d H:i:s')];
        return $this->update($id, $data);
    }

    public function changePassword($id, $newPassword)
    {
        $data = ['password' => password_hash($newPassword, PASSWORD_DEFAULT)];
        return $this->update($id, $data);
    }

    public function activate($id)
    {
        $data = ['status' => 'active'];
        return $this->update($id, $data);
    }

    public function deactivate($id)
    {
        $data = ['status' => 'inactive'];
        return $this->update($id, $data);
    }
}
