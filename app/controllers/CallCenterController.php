<?php

require_once APP_PATH . '/app/models/CallCenter.php';

class CallCenterController extends BaseController
{
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
        
        $callCenterModel = new CallCenter();
        $result = $callCenterModel->getPaginated($page, $perPage, $search);
        
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

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/call-center');
            return;
        }
        
        // Validate CSRF token
        if (!Session::validateCSRF($_POST['_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token');
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
            Session::flash('error', implode(', ', $errors));
            $this->redirect('/call-center/create');
            return;
        }
        
        // Create call center
        $callCenterModel = new CallCenter();
        if ($callCenterModel->createEntry($data)) {
            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Call center entry created successfully']);
            } else {
                Session::flash('success', 'Call center entry created successfully');
                $this->redirect('/call-center');
            }
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'Failed to create call center entry'], 500);
            } else {
                Session::flash('error', 'Failed to create call center entry');
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
            Session::flash('error', 'Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenterModel = new CallCenter();
        $callCenter = $callCenterModel->getById($id);
        if (!$callCenter) {
            Session::flash('error', 'Call center entry not found');
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
            Session::flash('error', 'Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/call-center');
            return;
        }
        
        // Validate CSRF token
        if (!Session::validateCSRF($_POST['_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenterModel = new CallCenter();
        $callCenter = $callCenterModel->getById($id);
        if (!$callCenter) {
            Session::flash('error', 'Call center entry not found');
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
            Session::flash('error', implode(', ', $errors));
            $this->redirect('/call-center/' . $id . '/edit');
            return;
        }
        
        // Update call center
        $callCenterModel = new CallCenter();
        if ($callCenterModel->updateEntry($id, $data)) {
            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Call center entry updated successfully']);
            } else {
                Session::flash('success', 'Call center entry updated successfully');
                $this->redirect('/call-center');
            }
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'Failed to update call center entry'], 500);
            } else {
                Session::flash('error', 'Failed to update call center entry');
                $this->redirect('/call-center/' . $id . '/edit');
            }
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
            Session::flash('error', 'Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/call-center');
            return;
        }
        
        // Validate CSRF token
        if (!Session::validateCSRF($_POST['_token'] ?? '')) {
            Session::flash('error', 'Invalid CSRF token');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenterModel = new CallCenter();
        $callCenter = $callCenterModel->getById($id);
        if (!$callCenter) {
            Session::flash('error', 'Call center entry not found');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenterModel = new CallCenter();
        if ($callCenterModel->deleteEntry($id)) {
            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Call center entry deleted successfully']);
            } else {
                Session::flash('success', 'Call center entry deleted successfully');
                $this->redirect('/call-center');
            }
        } else {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'Failed to delete call center entry'], 500);
            } else {
                Session::flash('error', 'Failed to delete call center entry');
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
            Session::flash('error', 'Invalid call center ID');
            $this->redirect('/call-center');
            return;
        }
        
        $callCenterModel = new CallCenter();
        $callCenter = $callCenterModel->getById($id);
        if (!$callCenter) {
            Session::flash('error', 'Call center entry not found');
            $this->redirect('/call-center');
            return;
        }
        
        $this->view('call-center/show', ['callCenter' => $callCenter]);
    }
}
