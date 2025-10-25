<?php

class CustomerController extends BaseController
{
    private $customerModel;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = new Customer();
        $this->userModel = new User();
    }

    /**
     * Display customer list
     */
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';
        $status = $_GET['status'] ?? '';
        $marketingId = $_GET['marketing'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;

        // Build query
        $conditions = [];
        $params = [];

        if ($search) {
            $conditions[] = "(customer_name LIKE :search OR customer_code LIKE :search)";
            $params['search'] = "%$search%";
        }

        if ($type) {
            $conditions[] = "customer_type = :type";
            $params['type'] = $type;
        }

        if ($status) {
            $conditions[] = "status = :status";
            $params['status'] = $status;
        }

        if ($marketingId) {
            $conditions[] = "assigned_marketing_id = :marketing_id";
            $params['marketing_id'] = $marketingId;
        }

        $whereClause = !empty($conditions) ? implode(' AND ', $conditions) : '';

        // Get paginated customers
        $result = $this->customerModel->paginate($page, $perPage, $whereClause, $params);

        // Get marketing list for filter
        $marketingList = $this->userModel->getUsersByRole('marketing');

        return $this->view('customers/index', [
            'title' => 'Customer Management',
            'customers' => $result['data'],
            'pagination' => $result,
            'marketingList' => $marketingList
        ]);
    }

    /**
     * Show create customer form
     */
    public function create()
    {
        $marketingList = $this->userModel->getUsersByRole('marketing');

        return $this->view('customers/create', [
            'title' => 'Tambah Customer',
            'marketingList' => $marketingList,
            'old' => $_SESSION['old_input'] ?? []
        ]);
    }

    /**
     * Store new customer
     */
    public function store()
    {
        // Validation
        $required = ['customer_code', 'customer_name', 'address'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['old_input'] = $_POST;
                return $this->redirect('/customers/create')->withError("Field $field wajib diisi");
            }
        }

        // Check if customer code already exists
        $existing = $this->customerModel->findByCode($_POST['customer_code']);
        if ($existing) {
            $_SESSION['old_input'] = $_POST;
            return $this->redirect('/customers/create')->withError('Kode customer sudah digunakan');
        }

        $data = [
            'customer_code' => $_POST['customer_code'],
            'customer_name' => $_POST['customer_name'],
            'owner_name' => $_POST['owner_name'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'email' => $_POST['email'] ?? null,
            'address' => $_POST['address'],
            'latitude' => !empty($_POST['latitude']) ? $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? $_POST['longitude'] : null,
            'customer_type' => $_POST['customer_type'] ?? 'retail',
            'customer_category' => $_POST['customer_category'] ?? null,
            'assigned_marketing_id' => !empty($_POST['assigned_marketing_id']) ? $_POST['assigned_marketing_id'] : null,
            'status' => $_POST['status'] ?? 'active',
            'notes' => $_POST['notes'] ?? null,
            'created_by' => $_SESSION['user_id'] ?? null
        ];

        $customerId = $this->customerModel->create($data);

        if ($customerId) {
            unset($_SESSION['old_input']);
            return $this->redirect('/customers')->withSuccess('Customer berhasil ditambahkan');
        } else {
            $_SESSION['old_input'] = $_POST;
            return $this->redirect('/customers/create')->withError('Gagal menambahkan customer');
        }
    }

    /**
     * Show customer detail
     */
    public function show($id)
    {
        $customer = $this->customerModel->findWithDetails($id);

        if (!$customer) {
            return $this->redirect('/customers')->withError('Customer tidak ditemukan');
        }

        // Get recent visits
        $recentVisits = $this->customerModel->getRecentVisits($id, 5);

        return $this->view('customers/show', [
            'title' => 'Detail Customer',
            'customer' => $customer,
            'recentVisits' => $recentVisits
        ]);
    }

    /**
     * Show edit customer form
     */
    public function edit($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return $this->redirect('/customers')->withError('Customer tidak ditemukan');
        }

        $marketingList = $this->userModel->getUsersByRole('marketing');

        return $this->view('customers/edit', [
            'title' => 'Edit Customer',
            'customer' => $customer,
            'marketingList' => $marketingList
        ]);
    }

    /**
     * Update customer
     */
    public function update($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return $this->redirect('/customers')->withError('Customer tidak ditemukan');
        }

        // Validation
        $required = ['customer_code', 'customer_name', 'address'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return $this->redirect("/customers/$id/edit")->withError("Field $field wajib diisi");
            }
        }

        // Check if customer code already exists (exclude current customer)
        $existing = $this->customerModel->findByCode($_POST['customer_code']);
        if ($existing && $existing['id'] != $id) {
            return $this->redirect("/customers/$id/edit")->withError('Kode customer sudah digunakan');
        }

        $data = [
            'customer_code' => $_POST['customer_code'],
            'customer_name' => $_POST['customer_name'],
            'owner_name' => $_POST['owner_name'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'email' => $_POST['email'] ?? null,
            'address' => $_POST['address'],
            'latitude' => !empty($_POST['latitude']) ? $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? $_POST['longitude'] : null,
            'customer_type' => $_POST['customer_type'] ?? 'retail',
            'customer_category' => $_POST['customer_category'] ?? null,
            'assigned_marketing_id' => !empty($_POST['assigned_marketing_id']) ? $_POST['assigned_marketing_id'] : null,
            'status' => $_POST['status'] ?? 'active',
            'notes' => $_POST['notes'] ?? null
        ];

        $success = $this->customerModel->update($id, $data);

        if ($success) {
            return $this->redirect('/customers')->withSuccess('Customer berhasil diupdate');
        } else {
            return $this->redirect("/customers/$id/edit")->withError('Gagal mengupdate customer');
        }
    }

    /**
     * Delete customer
     */
    public function destroy($id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return $this->json(['success' => false, 'error' => 'Customer tidak ditemukan'], 404);
        }

        // Check if customer has visits
        $hasVisits = $this->customerModel->hasVisits($id);
        if ($hasVisits) {
            return $this->json([
                'success' => false,
                'error' => 'Customer tidak dapat dihapus karena memiliki riwayat kunjungan'
            ], 400);
        }

        $success = $this->customerModel->delete($id);

        if ($success) {
            return $this->json([
                'success' => true,
                'message' => 'Customer berhasil dihapus'
            ]);
        } else {
            return $this->json([
                'success' => false,
                'error' => 'Gagal menghapus customer'
            ], 500);
        }
    }
}
