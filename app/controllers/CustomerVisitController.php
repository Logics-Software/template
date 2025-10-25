<?php
/**
 * Customer Visit Controller
 * Manages customer visit tracking with GPS for marketing team
 */
class CustomerVisitController extends BaseController
{
    private $customerModel;
    private $visitModel;
    private $targetModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = new Customer();
        $this->visitModel = new CustomerVisit();
        $this->targetModel = new VisitTarget();
    }

    /**
     * Dashboard - Main view for marketing
     */
    public function index($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');
        
        // Get current month target
        $currentMonth = date('Y-m-01');
        $target = $this->targetModel->getTargetWithProgress($userId, $currentMonth);
        
        // Get today's visits
        $todayVisits = $this->visitModel->getTodayVisits($userId);
        
        // Get active visit (if any)
        $activeVisit = $this->visitModel->getActiveVisit($userId);
        
        // Get this month stats
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $monthStats = $this->visitModel->getVisitStats($userId, $monthStart, $monthEnd);
        
        // Get my customers
        $myCustomers = $this->customerModel->getByMarketing($userId);
        
        $this->view('customer-visits/dashboard', [
            'title' => 'Customer Visit Dashboard',
            'current_page' => 'customer-visits',
            'target' => $target,
            'today_visits' => $todayVisits,
            'active_visit' => $activeVisit,
            'month_stats' => $monthStats,
            'customers' => $myCustomers,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * Show customer list for starting new visit
     */
    public function selectCustomer($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $search = $request->input('search') ?? '';
        
        if ($search) {
            $customers = $this->customerModel->searchCustomers($search, $userId);
        } else {
            $customers = $this->customerModel->getByMarketing($userId);
        }
        
        $this->view('customer-visits/select-customer', [
            'title' => 'Pilih Customer',
            'current_page' => 'customer-visits',
            'customers' => $customers,
            'search' => $search,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * Start visit - Check in
     */
    public function checkIn($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }

        $userId = Session::get('user_id');
        
        // Validate input
        $validator = $request->validate([
            'customer_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'purpose' => 'required'
        ]);

        if (!$validator->validate()) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/customer-visits/select-customer');
            }
            return;
        }

        try {
            // Check if already has active visit
            $activeVisit = $this->visitModel->getActiveVisit($userId);
            if ($activeVisit) {
                if ($this->isAjax()) {
                    $this->json(['error' => 'Anda masih memiliki kunjungan aktif yang belum diselesaikan'], 400);
                } else {
                    $this->withError('Anda masih memiliki kunjungan aktif yang belum diselesaikan');
                    $this->redirect('/customer-visits');
                }
                return;
            }

            // Get customer data
            $customer = $this->customerModel->find($request->input('customer_id'));
            if (!$customer) {
                if ($this->isAjax()) {
                    $this->json(['error' => 'Customer not found'], 404);
                } else {
                    $this->withError('Customer not found');
                    $this->redirect('/customer-visits/select-customer');
                }
                return;
            }

            // Validate GPS location
            $currentLat = (float) $request->input('latitude');
            $currentLon = (float) $request->input('longitude');
            
            $validation = ['valid' => true, 'distance' => 0];
            
            if ($customer['latitude'] && $customer['longitude']) {
                $validation = $this->visitModel->validateLocation(
                    $customer['latitude'],
                    $customer['longitude'],
                    $currentLat,
                    $currentLon,
                    50 // 50 meter radius
                );
            }

            // Prepare check-in data
            $checkInData = [
                'customer_id' => $customer['id'],
                'marketing_id' => $userId,
                'latitude' => $currentLat,
                'longitude' => $currentLon,
                'address' => $request->input('address'),
                'accuracy' => $request->input('accuracy'),
                'distance' => $validation['distance'],
                'is_valid' => $validation['valid'] ? 1 : 0,
                'purpose' => $request->input('purpose')
            ];

            // Create visit
            $visitId = $this->visitModel->checkIn($checkInData);

            if ($visitId) {
                if ($this->isAjax()) {
                    $this->json([
                        'success' => true, 
                        'message' => 'Check-in berhasil',
                        'visit_id' => $visitId,
                        'validation' => $validation
                    ]);
                } else {
                    $this->withSuccess('Check-in berhasil');
                    $this->redirect('/customer-visits/active/' . $visitId);
                }
            } else {
                throw new Exception('Failed to create visit');
            }

        } catch (Exception $e) {
            error_log("Check-in error: " . $e->getMessage());
            
            if ($this->isAjax()) {
                $this->json(['error' => 'Gagal melakukan check-in: ' . $e->getMessage()], 500);
            } else {
                $this->withError('Gagal melakukan check-in');
                $this->redirect('/customer-visits/select-customer');
            }
        }
    }

    /**
     * Show active visit form
     */
    public function activeVisit($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $visitId = $params[0] ?? null;

        if (!$visitId) {
            // Get active visit
            $visit = $this->visitModel->getActiveVisit($userId);
            if (!$visit) {
                $this->withError('Tidak ada kunjungan aktif');
                $this->redirect('/customer-visits');
                return;
            }
        } else {
            $visit = $this->visitModel->getVisitDetail($visitId);
            
            if (!$visit || $visit['marketing_id'] != $userId) {
                $this->withError('Visit not found');
                $this->redirect('/customer-visits');
                return;
            }
        }

        $this->view('customer-visits/active-visit', [
            'title' => 'Kunjungan Aktif',
            'current_page' => 'customer-visits',
            'visit' => $visit,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * Complete visit - Check out
     */
    public function checkOut($request = null, $response = null, $params = [])
    {
        // TEMPORARY: Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        
        if (!Session::has('user_id')) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }

        $userId = Session::get('user_id');
        $visitId = $request->input('visit_id');
        
        // Debug log
        error_log("Check-out request received for visit ID: " . $visitId);

        // Debug: Log all input
        error_log("Check-out input data: " . json_encode($request->all()));
        
        // Validate input
        $validator = $request->validate([
            'visit_id' => 'required',
            'result' => 'required',
            'visit_notes' => 'required'
        ]);

        if (!$validator->validate()) {
            error_log("Validation failed: " . json_encode($validator->errors()));
            if ($this->isAjax()) {
                $this->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/customer-visits/active/' . $visitId);
            }
            return;
        }
        
        error_log("Validation passed");

        try {
            // Verify visit belongs to this user
            error_log("Finding visit with ID: " . $visitId);
            $visit = $this->visitModel->find($visitId);
            
            if (!$visit) {
                error_log("Visit not found");
                throw new Exception('Visit not found');
            }
            
            error_log("Visit found. Marketing ID: " . $visit['marketing_id'] . ", User ID: " . $userId);
            
            if ($visit['marketing_id'] != $userId) {
                error_log("Unauthorized access - marketing_id mismatch");
                throw new Exception('Unauthorized access to visit');
            }

            if ($visit['check_out_time']) {
                error_log("Visit already completed at: " . $visit['check_out_time']);
                throw new Exception('Visit already completed');
            }
            
            error_log("Visit validation passed");

            // Handle photo uploads
            $photos = [];
            error_log("Checking for photo uploads...");
            if (isset($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
                error_log("Photos found: " . count($_FILES['photos']['name']));
                try {
                    $photos = $this->handlePhotoUploads($_FILES['photos'], $visitId);
                    error_log("Photos uploaded successfully: " . count($photos));
                } catch (Exception $photoError) {
                    error_log("Photo upload error: " . $photoError->getMessage());
                    // Continue without photos if upload fails
                }
            } else {
                error_log("No photos to upload");
            }

            // Prepare checkout data
            // Convert empty strings to NULL for numeric fields to avoid database errors
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $orderAmount = $request->input('order_amount');
            
            // Convert to proper types or NULL
            $latitude = ($latitude !== null && $latitude !== '') ? floatval($latitude) : null;
            $longitude = ($longitude !== null && $longitude !== '') ? floatval($longitude) : null;
            $orderAmount = ($orderAmount !== null && $orderAmount !== '') ? floatval($orderAmount) : null;
            
            $checkOutData = [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $request->input('address') ?: null,
                'result' => $request->input('result'),
                'has_order' => $request->input('has_order') ? 1 : 0,
                'order_amount' => $orderAmount,
                'order_notes' => $request->input('order_notes') ?: null,
                'customer_feedback' => $request->input('customer_feedback') ?: null,
                'visit_notes' => $request->input('visit_notes'),
                'problems' => $request->input('problems') ?: null,
                'next_action' => $request->input('next_action') ?: null,
                'next_visit_plan' => $request->input('next_visit_plan') ?: null,
                'photos' => !empty($photos) ? json_encode($photos) : null
            ];

            // Complete visit
            error_log("Calling visitModel->checkOut with data: " . json_encode($checkOutData));
            $result = $this->visitModel->checkOut($visitId, $checkOutData);
            error_log("checkOut result: " . ($result ? 'success' : 'failed'));

            if ($result) {
                // Update monthly target
                $currentMonth = date('Y-m-01');
                $this->targetModel->updateActuals($userId, $currentMonth);

                if ($this->isAjax()) {
                    $this->json([
                        'success' => true, 
                        'message' => 'Kunjungan berhasil diselesaikan',
                        'redirect' => '/customer-visits'
                    ]);
                    return;
                } else {
                    $this->withSuccess('Kunjungan berhasil diselesaikan');
                    $this->redirect('/customer-visits');
                    return;
                }
            } else {
                throw new Exception('Failed to complete visit');
            }

        } catch (Exception $e) {
            error_log("Check-out error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            if ($this->isAjax()) {
                $this->json(['error' => 'Gagal menyelesaikan kunjungan: ' . $e->getMessage()], 500);
                return;
            } else {
                $this->withError('Gagal menyelesaikan kunjungan');
                $this->redirect('/customer-visits/active/' . $visitId);
                return;
            }
        }
    }

    /**
     * Handle multiple photo uploads
     */
    private function handlePhotoUploads($files, $visitId)
    {
        $uploadedPhotos = [];
        $uploadDir = 'assets/images/visits/';
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $maxPhotos = 5;
        $maxSize = 2 * 1024 * 1024; // 2MB per photo
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        $fileCount = count($files['name']);
        $processedCount = 0;

        for ($i = 0; $i < $fileCount && $processedCount < $maxPhotos; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                // Validate file type
                if (!in_array($files['type'][$i], $allowedTypes)) {
                    continue;
                }

                // Validate file size
                if ($files['size'][$i] > $maxSize) {
                    continue;
                }

                // Generate unique filename
                $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $filename = 'visit_' . $visitId . '_' . time() . '_' . $i . '.' . $extension;
                $filepath = $uploadDir . $filename;

                // Move uploaded file
                if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
                    $uploadedPhotos[] = $filepath;
                    $processedCount++;
                }
            }
        }

        return $uploadedPhotos;
    }

    /**
     * Visit history
     */
    public function history($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $page = (int) ($request->input('page') ?? 1);
        $perPage = 15;

        $filters = [
            'customer_id' => $request->input('customer_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'visit_result' => $request->input('visit_result')
        ];

        $history = $this->visitModel->getVisitHistory($userId, $page, $perPage, $filters);

        $this->view('customer-visits/history', [
            'title' => 'Riwayat Kunjungan',
            'current_page' => 'customer-visits',
            'history' => $history,
            'filters' => $filters,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * Visit detail
     */
    public function detail($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $visitId = $params[0] ?? null;
        if (!$visitId) {
            $this->redirect('/customer-visits/history');
            return;
        }

        $visit = $this->visitModel->getVisitDetail($visitId);

        if (!$visit) {
            $this->withError('Visit not found');
            $this->redirect('/customer-visits/history');
            return;
        }

        // Decode photos JSON
        if ($visit['photos']) {
            $visit['photos'] = json_decode($visit['photos'], true);
        }

        $this->view('customer-visits/detail', [
            'title' => 'Detail Kunjungan',
            'current_page' => 'customer-visits',
            'visit' => $visit,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * Customer list management
     */
    public function customers($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $userId = Session::get('user_id');
        $userRole = Session::get('user_role');
        
        $page = (int) ($request->input('page') ?? 1);
        $perPage = 20;

        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'customer_type' => $request->input('customer_type')
        ];

        // Marketing can only see their own customers
        if ($userRole === 'marketing') {
            $filters['marketing_id'] = $userId;
        }

        $customers = $this->customerModel->getPaginatedCustomers($page, $perPage, $filters);

        $this->view('customer-visits/customers', [
            'title' => 'Daftar Customer',
            'current_page' => 'customer-visits',
            'customers' => $customers,
            'filters' => $filters,
            'csrf_token' => $this->csrfToken()
        ]);
    }

    /**
     * API: Search customers (for AJAX)
     */
    public function searchCustomers($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $userId = Session::get('user_id');
        $search = $request->input('q') ?? '';

        $customers = $this->customerModel->searchCustomers($search, $userId);

        $this->json([
            'success' => true,
            'customers' => $customers
        ]);
    }

    /**
     * API: Validate GPS location
     */
    public function validateLocation($request = null, $response = null, $params = [])
    {
        if (!Session::has('user_id')) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $customerId = $request->input('customer_id');
        $currentLat = (float) $request->input('latitude');
        $currentLon = (float) $request->input('longitude');

        $customer = $this->customerModel->find($customerId);

        if (!$customer) {
            $this->json(['error' => 'Customer not found'], 404);
            return;
        }

        if (!$customer['latitude'] || !$customer['longitude']) {
            $this->json([
                'success' => true,
                'valid' => true,
                'message' => 'Customer location not set, validation skipped',
                'distance' => 0
            ]);
            return;
        }

        $validation = $this->visitModel->validateLocation(
            $customer['latitude'],
            $customer['longitude'],
            $currentLat,
            $currentLon,
            50
        );

        $this->json([
            'success' => true,
            'valid' => $validation['valid'],
            'distance' => $validation['distance'],
            'message' => $validation['message']
        ]);
    }
}

