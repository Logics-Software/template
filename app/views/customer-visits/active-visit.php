<?php
/**
 * Active Visit - Check-out Form
 * Mobile-friendly form for completing visit
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Kunjungan Aktif' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/complete-optimized.css">
    <style>
        .visit-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        .timer-display {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            padding: 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            margin-top: 1rem;
        }
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .photo-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 2rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .photo-upload-area:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }
        .photo-preview {
            display: inline-block;
            position: relative;
            margin: 0.5rem;
        }
        .photo-preview img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .photo-preview .btn-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            padding: 0;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            border: 2px solid white;
        }
        .btn-checkout {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            border-radius: 12px;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <main class="container-fluid p-0">
        <div class="container-fluid p-3">
            <!-- Visit Header -->
            <div class="visit-header">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-clock"></i> Kunjungan Aktif
                        </h5>
                        <h6 class="mb-0"><?= htmlspecialchars($visit['customer_name']) ?></h6>
                    </div>
                    <span class="badge bg-light text-dark">
                        <?= htmlspecialchars($visit['customer_code']) ?>
                    </span>
                </div>
                
                <p class="mb-2">
                    <i class="fas fa-map-marker-alt"></i> 
                    <?= htmlspecialchars($visit['customer_address']) ?>
                </p>
                
                <div class="row g-2">
                    <div class="col-6">
                        <small>Check-in:</small><br>
                        <strong><?= date('H:i', strtotime($visit['check_in_time'])) ?></strong>
                    </div>
                    <div class="col-6">
                        <small>Tujuan:</small><br>
                        <strong>
                            <?php
                            $purposes = [
                                'sales' => 'Sales/Penawaran',
                                'follow_up' => 'Follow Up',
                                'complaint' => 'Complaint',
                                'delivery' => 'Delivery',
                                'survey' => 'Survey',
                                'other' => 'Lainnya'
                            ];
                            echo $purposes[$visit['visit_purpose']] ?? $visit['visit_purpose'];
                            ?>
                        </strong>
                    </div>
                </div>
                
                <div class="timer-display" id="timerDisplay">
                    <i class="fas fa-hourglass-half"></i> 
                    <span id="timer">00:00:00</span>
                </div>
            </div>

            <!-- Check-out Form -->
            <form id="checkoutForm" enctype="multipart/form-data">
                <input type="hidden" name="visit_id" value="<?= $visit['id'] ?>">
                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="latitude" id="checkoutLat">
                <input type="hidden" name="longitude" id="checkoutLon">
                <input type="hidden" name="address" id="checkoutAddress">

                <!-- Result -->
                <div class="form-card">
                    <h6 class="mb-3">
                        <i class="fas fa-chart-line"></i> Hasil Kunjungan
                    </h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Hasil Kunjungan <span class="text-danger">*</span></label>
                        <select class="form-select" name="result" id="visitResult" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="order_success">✅ Berhasil Order</option>
                            <option value="follow_up_needed">🔄 Perlu Follow Up</option>
                            <option value="rejected">❌ Ditolak</option>
                            <option value="no_decision">⏳ Belum Putus</option>
                            <option value="other">📝 Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="form-card" id="orderSection" style="display: none;">
                    <h6 class="mb-3">
                        <i class="fas fa-shopping-cart"></i> Informasi Order
                    </h6>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="hasOrder" name="has_order" value="1">
                        <label class="form-check-label" for="hasOrder">
                            <strong>Ada Order</strong>
                        </label>
                    </div>
                    
                    <div id="orderDetails" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Jumlah Order (Rp)</label>
                            <input type="number" class="form-control" name="order_amount" id="orderAmount" 
                                   placeholder="0" min="0" step="1000">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catatan Order</label>
                            <textarea class="form-control" name="order_notes" rows="2" 
                                      placeholder="Detail order, item, dll..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                <div class="form-card">
                    <h6 class="mb-3">
                        <i class="fas fa-camera"></i> Dokumentasi Foto (Max 5)
                    </h6>
                    
                    <div class="photo-upload-area" onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                        <p class="mb-0">Klik untuk ambil foto</p>
                        <small class="text-muted">Atau pilih dari galeri</small>
                    </div>
                    
                    <input type="file" id="photoInput" name="photos[]" multiple accept="image/*" 
                           capture="environment" style="display: none;">
                    
                    <div id="photoPreview" class="mt-3"></div>
                </div>

                <!-- Feedback & Notes -->
                <div class="form-card">
                    <h6 class="mb-3">
                        <i class="fas fa-comment-dots"></i> Feedback & Catatan
                    </h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Feedback Customer</label>
                        <textarea class="form-control" name="customer_feedback" rows="3" 
                                  placeholder="Tanggapan atau feedback dari customer..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan Kunjungan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="visit_notes" rows="3" required
                                  placeholder="Ringkasan kunjungan, pembahasan, dll..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Problem/Keluhan (jika ada)</label>
                        <textarea class="form-control" name="problems" rows="2" 
                                  placeholder="Masalah atau keluhan yang disampaikan customer..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tindak Lanjut</label>
                        <textarea class="form-control" name="next_action" rows="2" 
                                  placeholder="Rencana tindak lanjut yang perlu dilakukan..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rencana Kunjungan Berikutnya</label>
                        <input type="date" class="form-control" name="next_visit_plan" 
                               min="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <!-- GPS Info -->
                <div class="form-card">
                    <h6 class="mb-3">
                        <i class="fas fa-map-pin"></i> Lokasi Check-out
                    </h6>
                    <div id="gpsInfo">
                        <div class="text-center p-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0">Mendeteksi lokasi GPS...</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-checkout" id="btnCheckout" disabled>
                    <i class="fas fa-check-circle"></i> Selesaikan Kunjungan
                </button>
                
                <a href="<?= BASE_URL ?>customer-visits" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="fas fa-times"></i> Batal
                </a>
            </form>
        </div>
    </main>

    <?php echo Notify::render(); ?>

    <script src="<?= BASE_URL ?>assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js?v=<?= time() ?>"></script>
    <script>
        // Timer
        const checkInTime = new Date('<?= $visit['check_in_time'] ?>');
        
        function updateTimer() {
            const now = new Date();
            const diff = now - checkInTime;
            
            const hours = Math.floor(diff / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            
            document.getElementById('timer').textContent = 
                `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
        
        // Visit Result Change
        document.getElementById('visitResult').addEventListener('change', function() {
            if (this.value === 'order_success') {
                document.getElementById('orderSection').style.display = 'block';
                document.getElementById('hasOrder').checked = true;
                document.getElementById('orderDetails').style.display = 'block';
            } else {
                document.getElementById('orderSection').style.display = 'none';
            }
        });
        
        // Has Order Checkbox
        document.getElementById('hasOrder').addEventListener('change', function() {
            document.getElementById('orderDetails').style.display = this.checked ? 'block' : 'none';
        });
        
        // Photo Upload
        let selectedPhotos = [];
        const maxPhotos = 5;
        
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            if (selectedPhotos.length + files.length > maxPhotos) {
                alert(`Maksimal ${maxPhotos} foto`);
                return;
            }
            
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    selectedPhotos.push(file);
                    previewPhoto(file);
                }
            });
            
            this.value = ''; // Reset input
        });
        
        function previewPhoto(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'photo-preview';
                preview.innerHTML = `
                    <img src="${e.target.result}">
                    <button type="button" class="btn btn-remove" onclick="removePhoto(${selectedPhotos.length - 1})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                document.getElementById('photoPreview').appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
        
        function removePhoto(index) {
            selectedPhotos.splice(index, 1);
            updatePhotoPreview();
        }
        
        function updatePhotoPreview() {
            document.getElementById('photoPreview').innerHTML = '';
            selectedPhotos.forEach((file, index) => {
                previewPhoto(file);
            });
        }
        
        // Detect GPS for checkout
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    document.getElementById('checkoutLat').value = position.coords.latitude;
                    document.getElementById('checkoutLon').value = position.coords.longitude;
                    
                    document.getElementById('gpsInfo').innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Lokasi berhasil terdeteksi
                            <br><small>Lat: ${position.coords.latitude.toFixed(6)}, 
                            Lon: ${position.coords.longitude.toFixed(6)}</small>
                        </div>
                    `;
                    
                    document.getElementById('btnCheckout').disabled = false;
                },
                error => {
                    document.getElementById('gpsInfo').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            GPS tidak terdeteksi, tapi Anda masih bisa checkout
                        </div>
                    `;
                    document.getElementById('btnCheckout').disabled = false;
                }
            );
        } else {
            document.getElementById('btnCheckout').disabled = false;
        }
        
        // Form Submit
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Add photos
            selectedPhotos.forEach((photo, index) => {
                formData.append(`photos[${index}]`, photo);
            });
            
            const btnCheckout = document.getElementById('btnCheckout');
            btnCheckout.disabled = true;
            btnCheckout.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
            fetch('<?= BASE_URL ?>customer-visits/check-out', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // First get the response as text to see what we got
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    
                    // Check if response is ok
                    if (!response.ok) {
                        console.error('HTTP Error ' + response.status + ':', text);
                        throw new Error(`HTTP error! status: ${response.status}. Response: ${text.substring(0, 200)}`);
                    }
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.error('Non-JSON response:', text);
                        throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
                    }
                    
                    // Parse as JSON
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        console.error('Response text:', text);
                        throw new Error('Invalid JSON response: ' + e.message);
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    alert('Kunjungan berhasil diselesaikan!');
                    const redirectUrl = data.redirect ? '<?= BASE_URL ?>' + data.redirect.replace(/^\//, '') : '<?= BASE_URL ?>customer-visits';
                    window.location.href = redirectUrl;
                } else {
                    alert(data.error || 'Gagal menyimpan data');
                    btnCheckout.disabled = false;
                    btnCheckout.innerHTML = '<i class="fas fa-check-circle"></i> Selesaikan Kunjungan';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan: ' + error.message);
                btnCheckout.disabled = false;
                btnCheckout.innerHTML = '<i class="fas fa-check-circle"></i> Selesaikan Kunjungan';
            });
        });
    </script>
</body>
</html>

