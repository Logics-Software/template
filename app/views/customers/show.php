<?php 
$this->layout('layouts/app', ['title' => $title]) ?>

<?php $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Detail Customer</h5>
                    <div>
                        <a href="<?= BASE_URL ?>customers/<?= $customer['id'] ?>/edit" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= BASE_URL ?>customers" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Informasi Dasar</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Kode Customer:</strong></td>
                                    <td><?= htmlspecialchars($customer['customer_code']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td><?= htmlspecialchars($customer['customer_name']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Pemilik:</strong></td>
                                    <td><?= htmlspecialchars($customer['owner_name'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon:</strong></td>
                                    <td><?= htmlspecialchars($customer['phone'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?= htmlspecialchars($customer['email'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td><?= nl2br(htmlspecialchars($customer['address'])) ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Kategori & Status</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Tipe:</strong></td>
                                    <td>
                                        <?php
                                        $typeBadges = [
                                            'retail' => 'primary',
                                            'wholesale' => 'info',
                                            'distributor' => 'success',
                                            'other' => 'secondary'
                                        ];
                                        $badge = $typeBadges[$customer['customer_type']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>"><?= ucfirst($customer['customer_type']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td><?= htmlspecialchars($customer['customer_category'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Marketing:</strong></td>
                                    <td><?= htmlspecialchars($customer['marketing_name'] ?? 'Belum ditugaskan') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php
                                        $statusBadges = [
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'prospect' => 'warning'
                                        ];
                                        $statusBadge = $statusBadges[$customer['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusBadge ?>"><?= ucfirst($customer['status']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>GPS:</strong></td>
                                    <td>
                                        <?php if (!empty($customer['latitude']) && !empty($customer['longitude'])): ?>
                                            <a href="https://www.google.com/maps?q=<?= $customer['latitude'] ?>,<?= $customer['longitude'] ?>" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-map-marker-alt"></i> Lihat di Map
                                            </a>
                                            <br><small class="text-muted">
                                                Lat: <?= $customer['latitude'] ?>, Lon: <?= $customer['longitude'] ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada koordinat</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($customer['notes'])): ?>
                        <hr>
                        <h6 class="text-primary mb-2">Catatan</h6>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($customer['notes'])) ?></p>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Dibuat:</strong> <?= date('d M Y H:i', strtotime($customer['created_at'])) ?>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <strong>Diupdate:</strong> <?= date('d M Y H:i', strtotime($customer['updated_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics & Activity -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-3 bg-light rounded text-center">
                        <h3 class="mb-1 text-primary"><?= $customer['total_visits'] ?></h3>
                        <small class="text-muted">Total Kunjungan</small>
                    </div>
                    
                    <div class="mb-3 p-3 bg-light rounded text-center">
                        <h3 class="mb-1 text-success"><?= $customer['total_orders'] ?></h3>
                        <small class="text-muted">Total Order</small>
                    </div>
                    
                    <div class="mb-0 p-3 bg-light rounded text-center">
                        <p class="mb-1"><strong>Last Visit:</strong></p>
                        <small class="text-muted">
                            <?= $customer['last_visit_date'] ? date('d M Y', strtotime($customer['last_visit_date'])) : 'Belum ada kunjungan' ?>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Recent Visits -->
            <?php if (!empty($recentVisits)): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Kunjungan Terakhir</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentVisits as $visit): ?>
                                <a href="<?= BASE_URL ?>customer-visits/<?= $visit['id'] ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar"></i> 
                                                <?= date('d M Y', strtotime($visit['visit_date'])) ?>
                                            </small>
                                            <br>
                                            <small><?= htmlspecialchars($visit['marketing_name']) ?></small>
                                        </div>
                                        <?php
                                        $resultBadges = [
                                            'order_success' => 'success',
                                            'follow_up_needed' => 'warning',
                                            'rejected' => 'danger',
                                            'no_decision' => 'info',
                                            'other' => 'secondary'
                                        ];
                                        $badge = $resultBadges[$visit['visit_result']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>">
                                            <?= $visit['has_order'] ? '💰' : '' ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $this->end() ?>

