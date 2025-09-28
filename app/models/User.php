<?php
/**
 * User Model
 */
class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'namalengkap', 'email', 'password', 'role', 'picture', 'status'];
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
        return $this->update($id, $data);
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
}
