<?php
/**
 * User Management Controller
 */
class UserController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function index($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $page = (int) ($request->input('page') ?? 1);
        $search = $request->input('search') ?? '';
        $status = $request->input('status') ?? '';

        $where = '1=1';
        $params = [];

        if ($search) {
            $where .= ' AND (name LIKE :search OR email LIKE :search)';
            $params['search'] = "%{$search}%";
        }

        if ($status) {
            $where .= ' AND status = :status';
            $params['status'] = $status;
        }

        $users = $this->userModel->paginate($page, DEFAULT_PAGE_SIZE, $where, $params);

        $this->view('users/index', [
            'title' => 'Users',
            'current_page' => 'users',
            'users' => $users,
            'search' => $search,
            'status' => $status,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function create($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->view('users/create', [
            'title' => 'Create User',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function store($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $validator = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/users/create');
            }
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => password_hash($request->input('password'), PASSWORD_DEFAULT),
            'role' => $request->input('role'),
            'status' => 'active'
        ];

        try {
            $this->userModel->beginTransaction();
            $userId = $this->userModel->create($data);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User created successfully']);
            } else {
                $this->withSuccess('User created successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to create user'], 500);
            } else {
                $this->withError('Failed to create user');
                $this->redirect('/users/create');
            }
        }
    }

    public function show($request, $response, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
        }

        $this->view('users/show', [
            'title' => 'User Details',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function edit($request, $response, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            $this->withError('User not found');
            $this->redirect('/users');
        }

        $this->view('users/edit', [
            'title' => 'Edit User',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function update($request, $response, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
        }

        $validator = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect("/users/{$id}/edit");
            }
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'status' => $request->input('status') ?? 'active'
        ];

        // Only update password if provided
        if ($request->input('password')) {
            $data['password'] = password_hash($request->input('password'), PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->update($id, $data);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User updated successfully']);
            } else {
                $this->withSuccess('User updated successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to update user'], 500);
            } else {
                $this->withError('Failed to update user');
                $this->redirect("/users/{$id}/edit");
            }
        }
    }

    public function destroy($request, $response, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->delete($id);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                $this->withSuccess('User deleted successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to delete user'], 500);
            } else {
                $this->withError('Failed to delete user');
                $this->redirect('/users');
            }
        }
    }

    public function settings($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->view('users/settings', [
            'title' => 'Settings',
            'current_page' => 'settings',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function updateSettings($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->withSuccess('Settings updated successfully');
        $this->redirect('/settings');
    }

    public function profile($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $user = $this->userModel->find(Session::get('user_id'));

        $this->view('users/profile', [
            'title' => 'Profile',
            'current_page' => 'profile',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function updateProfile($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
        }

        $this->withSuccess('Profile updated successfully');
        $this->redirect('/profile');
    }
}
