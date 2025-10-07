<?php


class CallCenterController extends BaseController
{
    private $callCenterModel;

    public function __construct()
    {
        parent::__construct();
        $this->callCenterModel = new CallCenter();
    }
    /**
     * Display call center list
     */
    public function index($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $search = $request->input('search') ?? '';
        $page = (int) ($request->input('page') ?? 1);
        $perPage = (int) ($request->input('per_page') ?? 10); // Items per page
        
        $result = $this->callCenterModel->getPaginated($page, $perPage, $search);
        
        $this->view('call-center/index', [
            'title' => 'Call Center',
            'callCenters' => $result['data'],
            'search' => $search,
            'pagination' => [
                'current_page' => $result['page'],
                'total_pages' => $result['total_pages'],
                'total_items' => $result['total'],
                'per_page' => $result['per_page'],
                'has_next' => $result['has_next'],
                'has_prev' => $result['has_prev']
            ]
        ]);
    }
    
    /**
     * Show create form
     */
    public function create($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $this->view('call-center/create');
    }
    
    /**
     * Store new call center
     */
    public function store($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $validator = $request->validate([
            'judul' => 'required|string|max:255',
            'nomorwa' => 'required|string|max:20',
            'deskripsi' => 'string|max:500'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/call-center/create');
            }
        }

        $data = [
            'judul' => $request->input('judul'),
            'nomorwa' => $request->input('nomorwa'),
            'deskripsi' => $request->input('deskripsi')
        ];
        
        try {
            $this->callCenterModel->beginTransaction();
            $callCenterId = $this->callCenterModel->createEntry($data);
            
            if ($callCenterId) {
                $this->callCenterModel->commit();
                if ($request->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Call center entry created successfully']);
                } else {
                    $this->withSuccess('Call center entry created successfully');
                    $this->redirect('/call-center');
                }
            } else {
                $this->callCenterModel->rollback();
                if ($request->isAjax()) {
                    $this->json(['error' => 'Failed to create call center entry'], 500);
                } else {
                    $this->withError('Failed to create call center entry');
                    $this->redirect('/call-center/create');
                }
            }
        } catch (Exception $e) {
            $this->callCenterModel->rollback();
            if ($request->isAjax()) {
                $this->json(['error' => 'An error occurred while creating call center entry'], 500);
            } else {
                $this->withError('An error occurred while creating call center entry');
                $this->redirect('/call-center/create');
            }
        }
    }
    
    /**
     * Show edit form
     */
    public function edit($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $id = $params[0] ?? null;
        
        if (!$id) {
            $this->withError('Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenter = $this->callCenterModel->getById($id);
        if (!$callCenter) {
            $this->withError('Call center entry not found');
            $this->redirect('/call-center');
            return;
        }
        
        $this->view('call-center/edit', ['callCenter' => $callCenter]);
    }
    
    /**
     * Update call center
     */
    public function update($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $id = $params[0] ?? null;
        
        if (!$id) {
            $this->withError('Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/call-center');
            return;
        }
        
        // Validate CSRF token
        if (!Session::validateCSRF($_POST['_token'] ?? '')) {
            $this->withError('Invalid CSRF token');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenter = $this->callCenterModel->getById($id);
        if (!$callCenter) {
            $this->withError('Call center entry not found');
            $this->redirect('/call-center');
            return;
        }
        
        $data = [
            'judul' => trim($_POST['judul'] ?? ''),
            'nomorwa' => trim($_POST['nomorwa'] ?? ''),
            'deskripsi' => trim($_POST['deskripsi'] ?? '')
        ];
        
        // Validation
        $errors = [];
        if (empty($data['judul'])) {
            $errors[] = 'Judul is required';
        }
        if (empty($data['nomorwa'])) {
            $errors[] = 'Nomor WhatsApp is required';
        }
        if (!empty($data['nomorwa']) && !preg_match('/^[0-9+\-\s]+$/', $data['nomorwa'])) {
            $errors[] = 'Nomor WhatsApp format is invalid';
        }
        
        if (!empty($errors)) {
            $this->withError(implode(', ', $errors));
            $this->redirect('/call-center/' . $id . '/edit');
            return;
        }
        
        // Update call center
        if ($this->callCenterModel->updateEntry($id, $data)) {
            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Call center entry updated successfully']);
            } else {
                $this->withSuccess('Call center entry updated successfully');
                $this->redirect('/call-center');
            }
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'Failed to update call center entry'], 500);
            } else {
                $this->withError('Failed to update call center entry');
                $this->redirect('/call-center/' . $id . '/edit');
            }
        }
    }
    
    /**
     * Update sort order for call center entries
     */
    public function updateSortOrder($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            if ($request->isAjax()) {
                $this->json(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }

        if (!$request->isAjax()) {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        // Validate CSRF token
        if (!$this->validateCSRF($request)) {
            $this->json(['error' => 'CSRF token mismatch'], 403);
            return;
        }
        
        // Get orders data from JSON request
        $orders = $request->json('orders');
        
        if (!$orders || !is_array($orders)) {
            $this->json(['error' => 'Invalid data'], 400);
            return;
        }
        
        // Validate each order item
        foreach ($orders as $order) {
            if (!isset($order['id']) || !isset($order['sort_order'])) {
                $this->json(['error' => 'Invalid data'], 400);
                return;
            }
            
            if (!is_numeric($order['id']) || !is_numeric($order['sort_order'])) {
                $this->json(['error' => 'Invalid data'], 400);
                return;
            }
        }
        
        try {
            $result = $this->callCenterModel->updateMultipleSortOrders($orders);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Sort order updated successfully']);
            } else {
                $this->json(['error' => 'Failed to update sort order'], 500);
            }
        } catch (Exception $e) {
            $this->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Delete call center
     */
    public function delete($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $id = $params[0] ?? null;
        
        if (!$id) {
            $this->withError('Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/call-center');
            return;
        }
        
        // Validate CSRF token
        if (!Session::validateCSRF($_POST['_token'] ?? '')) {
            $this->withError('Invalid CSRF token');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenter = $this->callCenterModel->getById($id);
        if (!$callCenter) {
            $this->withError('Call center entry not found');
            $this->redirect('/call-center');
            return;
        }
        
        if ($this->callCenterModel->deleteEntry($id)) {
            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Call center entry deleted successfully']);
            } else {
                $this->withSuccess('Call center entry deleted successfully');
                $this->redirect('/call-center');
            }
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'Failed to delete call center entry'], 500);
            } else {
                $this->withError('Failed to delete call center entry');
                $this->redirect('/call-center');
            }
        }
    }
    
    /**
     * Show call center details
     */
    public function show($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $id = $params[0] ?? null;
        
        if (!$id) {
            $this->withError('Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenter = $this->callCenterModel->getById($id);
        if (!$callCenter) {
            $this->withError('Call center entry not found');
            $this->redirect('/call-center');
            return;
        }
        
        $this->view('call-center/show', ['callCenter' => $callCenter]);
    }
}
