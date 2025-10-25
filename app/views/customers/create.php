<?php 
$this->layout('layouts/app', ['title' => $title]) ?>

<?php $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tambah Customer Baru</h5>
                    <a href="<?= BASE_URL ?>customers" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>customers">
                        <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Informasi Dasar</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Kode Customer <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="customer_code" 
                                           value="<?= $old['customer_code'] ?? '' ?>" required>
                                    <small class="text-muted">Contoh: CUST001, TK-001</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nama Customer <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="customer_name" 
                                           value="<?= $old['customer_name'] ?? '' ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nama Pemilik</label>
                                    <input type="text" class="form-control" name="owner_name" 
                                           value="<?= $old['owner_name'] ?? '' ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="tel" class="form-control" name="phone" 
                                           value="<?= $old['phone'] ?? '' ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?= $old['email'] ?? '' ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="address" rows="3" required><?= $old['address'] ?? '' ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Category & Assignment -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Kategori & Penugasan</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Tipe Customer</label>
                                    <select class="form-select" name="customer_type">
                                        <option value="retail" <?= ($old['customer_type'] ?? '') == 'retail' ? 'selected' : '' ?>>Retail</option>
                                        <option value="wholesale" <?= ($old['customer_type'] ?? '') == 'wholesale' ? 'selected' : '' ?>>Wholesale</option>
                                        <option value="distributor" <?= ($old['customer_type'] ?? '') == 'distributor' ? 'selected' : '' ?>>Distributor</option>
                                        <option value="other" <?= ($old['customer_type'] ?? '') == 'other' ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Kategori Customer</label>
                                    <input type="text" class="form-control" name="customer_category" 
                                           value="<?= $old['customer_category'] ?? '' ?>"
                                           placeholder="Toko, Apotek, Rumah Sakit, dll">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Ditugaskan ke Marketing</label>
                                    <select class="form-select" name="assigned_marketing_id">
                                        <option value="">-- Belum Ditugaskan --</option>
                                        <?php foreach ($marketingList ?? [] as $marketing): ?>
                                            <option value="<?= $marketing['id'] ?>" 
                                                    <?= ($old['assigned_marketing_id'] ?? '') == $marketing['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($marketing['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" <?= ($old['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= ($old['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        <option value="prospect" <?= ($old['status'] ?? '') == 'prospect' ? 'selected' : '' ?>>Prospect</option>
                                    </select>
                                </div>
                                
                                <hr>
                                
                                <h6 class="text-primary mb-3">Koordinat GPS</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" class="form-control" name="latitude" 
                                           id="latitude" value="<?= $old['latitude'] ?? '' ?>"
                                           placeholder="-6.2088">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" class="form-control" name="longitude" 
                                           id="longitude" value="<?= $old['longitude'] ?? '' ?>"
                                           placeholder="106.8456">
                                </div>
                                
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="getCurrentLocation()">
                                    <i class="fas fa-crosshairs"></i> Ambil Lokasi Sekarang
                                </button>
                                
                                <hr>
                                
                                <div class="mb-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea class="form-control" name="notes" rows="3"><?= $old['notes'] ?? '' ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= BASE_URL ?>customers" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->end() ?>

<?php $this->section('js') ?>
<script>
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(8);
                    alert('Lokasi berhasil diambil!');
                },
                error => {
                    alert('Gagal mendapatkan lokasi GPS. Pastikan GPS aktif dan berikan izin lokasi.');
                    console.error('Geolocation error:', error);
                }
            );
        } else {
            alert('Geolocation tidak didukung oleh browser Anda.');
        }
    }
</script>
<?php $this->end() ?>

