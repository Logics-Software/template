<?php

class KonfigurasiController extends BaseController
{
    private $konfigurasiModel;

    public function __construct()
    {
        parent::__construct();
        $this->konfigurasiModel = new Konfigurasi();
    }

    /**
     * Display configuration page
     */
    public function index($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Get current configuration
        $konfigurasi = $this->konfigurasiModel->getConfiguration();
        
        if (!$konfigurasi) {
            // If no configuration exists, redirect to create
            $this->redirect('/konfigurasi/create');
            return;
        }

        $this->view('konfigurasi/index', [
            'title' => 'Konfigurasi Sistem',
            'current_page' => 'konfigurasi',
            'konfigurasi' => $konfigurasi
        ]);
    }

    /**
     * Show create configuration form
     */
    public function create($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Check if configuration already exists
        if ($this->konfigurasiModel->configurationExists()) {
            $this->redirect('/konfigurasi');
            return;
        }

        $this->view('konfigurasi/create', [
            'title' => 'Buat Konfigurasi Sistem',
            'current_page' => 'konfigurasi'
        ]);
    }

    /**
     * Store new configuration
     */
    public function store($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // Check if configuration already exists
        if ($this->konfigurasiModel->configurationExists()) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Konfigurasi sudah ada']);
            } else {
                $this->redirect('/konfigurasi');
            }
            return;
        }

        // CSRF validation is handled by App.php

        $data = $this->request->input();
        
        // Clean data - remove any array values that might cause issues
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                unset($data[$key]);
            }
        }
        
        // Modern validation
        $validator = $request->validate([
            'namaperusahaan' => 'required|string|max:255',
            'alamatperusahaan' => 'required|string|max:500',
            'penanggungjawab' => 'required|string|max:255'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/konfigurasi/create');
            }
        }

        try {
            $this->konfigurasiModel->beginTransaction();
            
            // Handle file upload for logo
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleLogoUpload($_FILES['logo']);
                if ($uploadResult['success']) {
                    $data['logo'] = $uploadResult['filename'];
                } else {
                    $this->konfigurasiModel->rollback();
                    if ($this->isAjax()) {
                        $this->json(['success' => false, 'message' => $uploadResult['message']]);
                    } else {
                        $this->withError($uploadResult['message']);
                        $this->redirect('/konfigurasi/create');
                    }
                    return;
                }
            }

            $result = $this->konfigurasiModel->create($data);

            if ($result) {
                $this->konfigurasiModel->commit();
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Konfigurasi berhasil dibuat', 'redirect' => '/konfigurasi']);
                } else {
                    $this->withSuccess('Konfigurasi berhasil dibuat');
                    $this->redirect('/konfigurasi');
                }
            } else {
                $this->konfigurasiModel->rollback();
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Gagal membuat konfigurasi']);
                } else {
                    $this->withError('Gagal membuat konfigurasi');
                    $this->redirect('/konfigurasi/create');
                }
            }
        } catch (Exception $e) {
            $this->konfigurasiModel->rollback();
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
            } else {
                $this->withError('Terjadi kesalahan: ' . $e->getMessage());
                $this->redirect('/konfigurasi/create');
            }
        }
    }

    /**
     * Show edit configuration form
     */
    public function edit($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        $konfigurasi = $this->konfigurasiModel->getConfiguration();
        
        if (!$konfigurasi) {
            $this->redirect('/konfigurasi/create');
            return;
        }

        $this->view('konfigurasi/edit', [
            'title' => 'Edit Konfigurasi Sistem',
            'current_page' => 'konfigurasi',
            'konfigurasi' => $konfigurasi
        ]);
    }

    /**
     * Update configuration
     */
    public function update($request = null, $response = null, $params = [])
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            $this->redirect('/login');
            return;
        }

        // CSRF validation is handled by App.php

        $data = $this->request->input();
        
        // Clean data - remove any array values that might cause issues
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                unset($data[$key]);
            }
        }
        
        // Modern validation
        $validator = $request->validate([
            'namaperusahaan' => 'required|string|max:255',
            'alamatperusahaan' => 'required|string|max:500',
            'penanggungjawab' => 'required|string|max:255'
        ]);

        if (!$validator->validate()) {
            if ($request->isAjax()) {
                $this->json(['errors' => $validator->errors()], 422);
            } else {
                $this->withErrors($validator->errors());
                $this->redirect('/konfigurasi/edit');
            }
        }

        try {
            $this->konfigurasiModel->beginTransaction();
            
            // Handle file upload for logo
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleLogoUpload($_FILES['logo']);
                if ($uploadResult['success']) {
                    $data['logo'] = $uploadResult['filename'];
                } else {
                    $this->konfigurasiModel->rollback();
                    if ($this->isAjax()) {
                        $this->json(['success' => false, 'message' => $uploadResult['message']]);
                    } else {
                        $this->withError($uploadResult['message']);
                        $this->redirect('/konfigurasi/edit');
                    }
                    return;
                }
            }

            $result = $this->konfigurasiModel->updateConfiguration($data);

            if ($result) {
                $this->konfigurasiModel->commit();
                if ($this->isAjax()) {
                    $this->json(['success' => true, 'message' => 'Konfigurasi berhasil diperbarui']);
                } else {
                    $this->withSuccess('Konfigurasi berhasil diperbarui');
                    $this->redirect('/konfigurasi');
                }
            } else {
                $this->konfigurasiModel->rollback();
                if ($this->isAjax()) {
                    $this->json(['success' => false, 'message' => 'Gagal memperbarui konfigurasi']);
                } else {
                    $this->withError('Gagal memperbarui konfigurasi');
                    $this->redirect('/konfigurasi/edit');
                }
            }
        } catch (Exception $e) {
            $this->konfigurasiModel->rollback();
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
            } else {
                $this->withError('Terjadi kesalahan: ' . $e->getMessage());
                $this->redirect('/konfigurasi/edit');
            }
        }
    }

    /**
     * Handle logo file upload
     */
    private function handleLogoUpload($file)
    {
        // Check if uploads directory exists
        $uploadDir = 'assets/images/konfigurasi/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return ['success' => false, 'message' => 'Gagal membuat direktori upload'];
            }
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP'];
        }

        // Validate file size (5MB max)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB'];
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . time() . '.' . $extension;

        // Move uploaded file
        $filePath = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return ['success' => true, 'filename' => $filename, 'path' => $filePath];
        } else {
            return ['success' => false, 'message' => 'Gagal mengupload file'];
        }
    }
}
