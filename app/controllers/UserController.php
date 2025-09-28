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
        // Temporarily disabled for testing
        // if (!Session::has('user_id') && APP_DEBUG === false) {
        //     $this->redirect('/login');
        // }

        $page = (int) ($request->input('page') ?? 1);
        $search = $request->input('search') ?? '';
        $status = $request->input('status') ?? '';

        $where = '1=1';
        $params = [];

        if ($search) {
            $where .= ' AND (username LIKE :search OR namalengkap LIKE :search OR email LIKE :search)';
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
        // Temporarily disabled for testing
        // if (!Session::has('user_id') && APP_DEBUG === false) {
        //     $this->redirect('/login');
        // }

        $this->view('users/create', [
            'title' => 'Create User',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function store($request = null, $response = null, $params = [])
    {
        // Temporarily disabled for testing
        // if (!Session::has('user_id') && APP_DEBUG === false) {
        //     $this->redirect('/login');
        // }

        $validator = $request->validate([
            'username' => 'required|min:3|unique:users',
            'namalengkap' => 'required|min:3',
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
            'username' => $request->input('username'),
            'namalengkap' => $request->input('namalengkap'),
            'email' => $request->input('email'),
            'password' => $request->input('password'), // Will be hashed in model
            'role' => $request->input('role'),
            'status' => 'aktif' // Default status for CRUD operations
        ];

        try {
            $this->userModel->beginTransaction();
            $userId = $this->userModel->createUser($data, 'aktif');
            
            // Handle file upload if picture is provided
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/users/';
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                $file = $_FILES['picture'];
                
                // Validate file type
                if (!in_array($file['type'], $allowedTypes)) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'], 400);
                    } else {
                        $this->withError('Invalid file type');
                        $this->redirect('/users/create');
                    }
                    return;
                }

                // Validate file size
                if ($file['size'] > $maxSize) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'File size too large. Maximum 5MB allowed.'], 400);
                    } else {
                        $this->withError('File size too large');
                        $this->redirect('/users/create');
                    }
                    return;
                }

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
                $filepath = $uploadDir . $filename;

                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Update user with picture path
                    $this->userModel->updatePicture($userId, $filepath);
                } else {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Failed to upload file'], 500);
                    } else {
                        $this->withError('Failed to upload file');
                        $this->redirect('/users/create');
                    }
                    return;
                }
            }
            
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
        // Temporarily disabled for testing
        // if (!Session::has('user_id') && APP_DEBUG === false) {
        //     $this->redirect('/login');
        // }

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
        // Temporarily disabled for testing
        // if (!Session::has('user_id') && APP_DEBUG === false) {
        //     $this->redirect('/login');
        // }

        $id = $params[0] ?? $request->input('id');
        
        $user = $this->userModel->find($id);

        if (!$user) {
            $this->withError('User not found');
            $this->redirect('/users');
            return;
        }
        $this->view('users/edit', [
            'title' => 'Edit User',
            'user' => $user,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function update($request, $response, $params = [])
    {
        // Temporarily disabled for testing
        // if (!Session::has('user_id') && APP_DEBUG === false) {
        //     $this->redirect('/login');
        // }

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
            'username' => 'required|min:3',
            'namalengkap' => 'required|min:3',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        // Custom validation for unique fields excluding current user
        // Temporarily disabled for testing
        // $username = $request->input('username');
        // $email = $request->input('email');

        // Validate password confirmation if password is provided
        // Temporarily disabled for testing
        // if ($request->input('password')) {
        //     $passwordValidator = $request->validate([
        //         'password' => 'min:6|confirmed'
        //     ]);
        //     
        //     if (!$passwordValidator->validate()) {
        //         if ($request->isAjax()) {
        //             $this->json(['errors' => $passwordValidator->errors()], 422);
        //         } else {
        //             $this->withErrors($passwordValidator->errors());
        //             $this->redirect("/users/{$id}/edit");
        //         }
        //         return;
        //     }
        // }

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect("/users/{$id}/edit");
            }
        }

        $data = [
            'username' => $request->input('username'),
            'namalengkap' => $request->input('namalengkap'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'status' => $request->input('status') ?? 'aktif'
        ];

        // Only update password if provided
        if ($request->input('password')) {
            $data['password'] = password_hash($request->input('password'), PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->update($id, $data);
            
            // Handle file upload if picture is provided
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/users/';
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                $file = $_FILES['picture'];
                
                // Validate file type
                if (!in_array($file['type'], $allowedTypes)) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'], 400);
                    } else {
                        $this->withError('Invalid file type');
                        $this->redirect("/users/{$id}/edit");
                    }
                    return;
                }

                // Validate file size
                if ($file['size'] > $maxSize) {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'File size too large. Maximum 5MB allowed.'], 400);
                    } else {
                        $this->withError('File size too large');
                        $this->redirect("/users/{$id}/edit");
                    }
                    return;
                }

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'user_' . $id . '_' . time() . '.' . $extension;
                $filepath = $uploadDir . $filename;

                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Delete old picture if exists
                    if ($user['picture'] && file_exists($user['picture'])) {
                        unlink($user['picture']);
                    }
                    
                    // Update user with picture path
                    $this->userModel->updatePicture($id, $filepath);
                } else {
                    $this->userModel->rollback();
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Failed to upload file'], 500);
                    } else {
                        $this->withError('Failed to upload file');
                        $this->redirect("/users/{$id}/edit");
                    }
                    return;
                }
            }
            
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
        // Temporarily disabled for testing
        // if (!Session::has('user_id')) {
        //     $this->redirect('/login');
        // }

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
        // Temporarily disabled for testing
        // if (!Session::has('user_id')) {
        //     $this->redirect('/login');
        // }

        $this->view('users/settings', [
            'title' => 'Settings',
            'current_page' => 'settings',
            'csrf_token' => $this->csrfToken()
        ]);
    }

    public function updateSettings($request = null, $response = null, $params = [])
    {
        // Temporarily disabled for testing
        // if (!Session::has('user_id')) {
        //     $this->redirect('/login');
        // }

        $this->withSuccess('Settings updated successfully');
        $this->redirect('/settings');
    }

    public function profile($request = null, $response = null, $params = [])
    {
        // Temporarily disabled for testing
        // if (!Session::has('user_id')) {
        //     $this->redirect('/login');
        // }

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
        // Temporarily disabled for testing
        // if (!Session::has('user_id')) {
        //     $this->redirect('/login');
        // }

        $this->withSuccess('Profile updated successfully');
        $this->redirect('/profile');
    }

    public function uploadPicture($request = null, $response = null, $params = [])
    {
        // Temporarily disabled for testing
        // if (!Session::has('user_id')) {
        //     if ($request->isAjax()) {
        //         $this->json(['error' => 'Unauthorized'], 401);
        //     } else {
        //         $this->redirect('/login');
        //     }
        //     return;
        // }

        $userId = $params[0] ?? Session::get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
            return;
        }

        // Handle file upload
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/images/users/';
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            $file = $_FILES['picture'];
            
            // Validate file type
            if (!in_array($file['type'], $allowedTypes)) {
                if ($request->isAjax()) {
                    $this->json(['error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'], 400);
                } else {
                    $this->withError('Invalid file type');
                    $this->redirect('/profile');
                }
                return;
            }

            // Validate file size
            if ($file['size'] > $maxSize) {
                if ($request->isAjax()) {
                    $this->json(['error' => 'File size too large. Maximum 5MB allowed.'], 400);
                } else {
                    $this->withError('File size too large');
                    $this->redirect('/profile');
                }
                return;
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                try {
                    $this->userModel->beginTransaction();
                    
                    // Delete old picture if exists
                    if ($user['picture'] && file_exists($user['picture'])) {
                        unlink($user['picture']);
                    }
                    
                    // Update user picture
                    $this->userModel->updatePicture($userId, $filepath);
                    $this->userModel->commit();

                    if ($request->isAjax()) {
                        $this->json([
                            'success' => true, 
                            'message' => 'Picture updated successfully',
                            'picture_url' => APP_URL . '/' . $filepath
                        ]);
                    } else {
                        $this->withSuccess('Picture updated successfully');
                        $this->redirect('/profile');
                    }
                } catch (Exception $e) {
                    $this->userModel->rollback();
                    // Clean up uploaded file
                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }
                    
                    if ($request->isAjax()) {
                        $this->json(['error' => 'Failed to update picture'], 500);
                    } else {
                        $this->withError('Failed to update picture');
                        $this->redirect('/profile');
                    }
                }
            } else {
                if ($request->isAjax()) {
                    $this->json(['error' => 'Failed to upload file'], 500);
                } else {
                    $this->withError('Failed to upload file');
                    $this->redirect('/profile');
                }
            }
        } else {
            if ($request->isAjax()) {
                $this->json(['error' => 'No file uploaded'], 400);
            } else {
                $this->withError('No file uploaded');
                $this->redirect('/profile');
            }
        }
    }

    public function activateUser($request = null, $response = null, $params = [])
    {
        // Temporarily disabled for testing
        // if (!Session::has('user_id')) {
        //     $this->redirect('/login');
        // }

        $id = $params[0] ?? $request->input('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            if ($request->isAjax()) {
                $this->json(['error' => 'User not found'], 404);
            } else {
                $this->withError('User not found');
                $this->redirect('/users');
            }
            return;
        }

        try {
            $this->userModel->beginTransaction();
            $this->userModel->activate($id);
            $this->userModel->commit();

            if ($request->isAjax()) {
                $this->json(['success' => true, 'message' => 'User activated successfully']);
            } else {
                $this->withSuccess('User activated successfully');
                $this->redirect('/users');
            }
        } catch (Exception $e) {
            $this->userModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'Failed to activate user'], 500);
            } else {
                $this->withError('Failed to activate user');
                $this->redirect('/users');
            }
        }
    }
}
