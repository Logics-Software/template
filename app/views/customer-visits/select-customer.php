<?php
/**
 * Select Customer for Visit
 * Mobile-friendly customer selection with search
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Pilih Customer' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/complete-optimized.css">
    <style>
        .search-box {
            position: sticky;
            top: 0;
            background: white;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            z-index: 100;
        }
        .customer-list-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .customer-list-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        .customer-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        .customer-icon.retail { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .customer-icon.wholesale { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
        .customer-icon.distributor { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .badge-distance {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <main class="container-fluid p-0">
        <!-- Search Box -->
        <div class="search-box">
            <form method="GET" action="<?= BASE_URL ?>customer-visits/select-customer">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           id="searchInput"
                           placeholder="Cari customer..." 
                           value="<?= htmlspecialchars($search ?? '') ?>"
                           autocomplete="off">
                    <?php if (!empty($search)): ?>
                    <a href="<?= BASE_URL ?>customer-visits/select-customer" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="container-fluid p-3">
            <h5 class="mb-3">
                <i class="fas fa-building"></i> Pilih Customer
                <span class="badge bg-primary"><?= count($customers ?? []) ?></span>
            </h5>

            <!-- Customer List -->
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>
                <div class="customer-list-item" onclick="selectCustomer(<?= $customer['id'] ?>)">
                    <div class="d-flex align-items-start">
                        <div class="customer-icon <?= $customer['customer_type'] ?>">
                            <?php
                            $icons = [
                                'retail' => 'fa-store',
                                'wholesale' => 'fa-warehouse',
                                'distributor' => 'fa-truck'
                            ];
                            $icon = $icons[$customer['customer_type']] ?? 'fa-building';
                            ?>
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?= htmlspecialchars($customer['customer_name']) ?></h6>
                            <p class="mb-1 text-muted small">
                                <i class="fas fa-code"></i> <?= htmlspecialchars($customer['customer_code']) ?>
                                <?php if ($customer['owner_name']): ?>
                                | <i class="fas fa-user"></i> <?= htmlspecialchars($customer['owner_name']) ?>
                                <?php endif; ?>
                            </p>
                            <p class="mb-1 text-muted small">
                                <i class="fas fa-map-marker-alt"></i> 
                                <?= htmlspecialchars(substr($customer['address'], 0, 50)) ?>
                                <?= strlen($customer['address']) > 50 ? '...' : '' ?>
                            </p>
                            <?php if ($customer['phone']): ?>
                            <p class="mb-0 text-muted small">
                                <i class="fas fa-phone"></i> <?= htmlspecialchars($customer['phone']) ?>
                            </p>
                            <?php endif; ?>
                            
                            <!-- Visit Stats -->
                            <?php if ($customer['total_visits'] > 0): ?>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-history"></i> <?= $customer['total_visits'] ?> kunjungan
                                </span>
                                <?php if ($customer['last_visit_date']): ?>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-calendar"></i> Terakhir: <?= date('d M Y', strtotime($customer['last_visit_date'])) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h5>Tidak ada customer</h5>
                    <p>
                        <?php if (!empty($search)): ?>
                            Tidak ditemukan customer dengan kata kunci "<?= htmlspecialchars($search) ?>"
                        <?php else: ?>
                            Belum ada customer yang di-assign ke Anda
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Check-in -->
    <div class="modal fade" id="checkInModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-map-marker-alt"></i> Check-in Kunjungan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="customerInfo"></div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        Sistem akan mendeteksi lokasi Anda menggunakan GPS
                    </div>
                    
                    <div id="gpsStatus" class="text-center p-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 mb-0">Mendeteksi lokasi GPS...</p>
                    </div>
                    
                    <div id="locationInfo" style="display: none;">
                        <div class="form-group mb-3">
                            <label>Tujuan Kunjungan</label>
                            <select class="form-select" id="visitPurpose" required>
                                <option value="">-- Pilih Tujuan --</option>
                                <option value="sales">Sales/Penawaran</option>
                                <option value="follow_up">Follow Up Order</option>
                                <option value="complaint">Handling Complaint</option>
                                <option value="delivery">Delivery/Pengiriman</option>
                                <option value="survey">Survey/Research</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Informasi GPS:</h6>
                                <p class="mb-1"><i class="fas fa-map-pin"></i> <span id="gpsCoords"></span></p>
                                <p class="mb-1"><i class="fas fa-crosshairs"></i> Akurasi: <span id="gpsAccuracy"></span></p>
                                <p class="mb-0" id="gpsDistance"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnCheckIn" disabled>
                        <i class="fas fa-check"></i> Check-in Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php echo Notify::render(); ?>

    <script src="<?= BASE_URL ?>assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js?v=<?= time() ?>"></script>
    <script>
        let selectedCustomer = null;
        let currentPosition = null;
        const checkInModal = new bootstrap.Modal(document.getElementById('checkInModal'));
        
        function selectCustomer(customerId) {
            // Get customer data
            fetch(`<?= BASE_URL ?>api/customer-visits/search-customers?id=${customerId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.customers.length > 0) {
                        selectedCustomer = data.customers[0];
                        showCheckInModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengambil data customer');
                });
        }
        
        function showCheckInModal() {
            document.getElementById('customerInfo').innerHTML = `
                <h6>${selectedCustomer.customer_name}</h6>
                <p class="text-muted mb-0">${selectedCustomer.address}</p>
            `;
            
            checkInModal.show();
            detectGPS();
        }
        
        function detectGPS() {
            if (!navigator.geolocation) {
                document.getElementById('gpsStatus').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> 
                        GPS tidak tersedia di browser Anda
                    </div>
                `;
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                position => {
                    currentPosition = position;
                    validateLocation(position);
                },
                error => {
                    let message = 'Gagal mendeteksi GPS';
                    if (error.code === 1) message = 'Izin GPS ditolak. Mohon aktifkan GPS di browser';
                    if (error.code === 2) message = 'Lokasi tidak tersedia';
                    if (error.code === 3) message = 'Request timeout';
                    
                    document.getElementById('gpsStatus').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> ${message}
                        </div>
                    `;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
        
        function validateLocation(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            
            // Validate with backend
            const formData = new FormData();
            formData.append('customer_id', selectedCustomer.id);
            formData.append('latitude', lat);
            formData.append('longitude', lon);
            formData.append('_token', '<?= $csrf_token ?>');
            
            fetch('<?= BASE_URL ?>api/customer-visits/validate-location', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('gpsStatus').style.display = 'none';
                document.getElementById('locationInfo').style.display = 'block';
                
                document.getElementById('gpsCoords').textContent = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                document.getElementById('gpsAccuracy').textContent = `±${accuracy.toFixed(0)}m`;
                
                if (data.valid) {
                    document.getElementById('gpsDistance').innerHTML = `
                        <i class="fas fa-check-circle text-success"></i> 
                        <strong class="text-success">Lokasi Valid</strong> 
                        (${data.distance.toFixed(0)}m dari customer)
                    `;
                } else {
                    document.getElementById('gpsDistance').innerHTML = `
                        <i class="fas fa-exclamation-triangle text-warning"></i> 
                        <strong class="text-warning">Jarak: ${data.distance.toFixed(0)}m</strong>
                        <br><small>Anda berada di luar radius 50m dari customer</small>
                    `;
                }
                
                document.getElementById('btnCheckIn').disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('locationInfo').style.display = 'block';
                document.getElementById('gpsStatus').style.display = 'none';
                document.getElementById('btnCheckIn').disabled = false;
            });
        }
        
        document.getElementById('btnCheckIn').addEventListener('click', function() {
            const purpose = document.getElementById('visitPurpose').value;
            if (!purpose) {
                alert('Pilih tujuan kunjungan terlebih dahulu');
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            const formData = new FormData();
            formData.append('customer_id', selectedCustomer.id);
            formData.append('latitude', currentPosition.coords.latitude);
            formData.append('longitude', currentPosition.coords.longitude);
            formData.append('accuracy', currentPosition.coords.accuracy);
            formData.append('purpose', purpose);
            formData.append('_token', '<?= $csrf_token ?>');
            
            fetch('<?= BASE_URL ?>customer-visits/check-in', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= BASE_URL ?>customer-visits/active/' + data.visit_id;
                } else {
                    alert(data.error || 'Gagal check-in');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check"></i> Check-in Sekarang';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-check"></i> Check-in Sekarang';
            });
        });
        
        // Auto-search
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
</body>
</html>

