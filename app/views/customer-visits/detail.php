<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Detail Kunjungan' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/complete-optimized.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 12px 12px 0 0;
        }
        .info-table td {
            padding: 0.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .photo-grid img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main class="container-fluid p-3">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-eye"></i> Detail Kunjungan
            </h5>
            <a href="<?= BASE_URL ?>customer-visits/history" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Visit Info Card -->
        <div class="detail-card">
            <div class="detail-header">
                <h6 class="mb-1"><?= htmlspecialchars($visit['customer_name']) ?></h6>
                <small><?= htmlspecialchars($visit['customer_code']) ?></small>
            </div>
            <div class="p-3">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Tanggal</small>
                        <p class="mb-0"><strong><?= date('d M Y', strtotime($visit['visit_date'])) ?></strong></p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Durasi</small>
                        <p class="mb-0"><strong><?= $visit['duration_minutes'] ? $visit['duration_minutes'] . ' menit' : '-' ?></strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Check-in</small>
                        <p class="mb-0"><?= date('H:i', strtotime($visit['check_in_time'])) ?></p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Check-out</small>
                        <p class="mb-0"><?= $visit['check_out_time'] ? date('H:i', strtotime($visit['check_out_time'])) : '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="detail-card">
            <div class="p-3">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-building"></i> Informasi Customer
                </h6>
                <table class="info-table w-100">
                    <tr>
                        <td width="30%" class="text-muted">Alamat:</td>
                        <td><?= htmlspecialchars($visit['customer_address']) ?></td>
                    </tr>
                    <?php if ($visit['customer_phone']): ?>
                    <tr>
                        <td class="text-muted">Telepon:</td>
                        <td>
                            <a href="tel:<?= htmlspecialchars($visit['customer_phone']) ?>">
                                <?= htmlspecialchars($visit['customer_phone']) ?>
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($visit['customer_email']): ?>
                    <tr>
                        <td class="text-muted">Email:</td>
                        <td><?= htmlspecialchars($visit['customer_email']) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Visit Purpose & Result -->
        <div class="detail-card">
            <div class="p-3">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-chart-line"></i> Tujuan & Hasil
                </h6>
                <div class="mb-3">
                    <small class="text-muted">Tujuan Kunjungan:</small><br>
                    <?php
                    $purposes = [
                        'sales' => '💼 Sales/Penawaran',
                        'follow_up' => '🔄 Follow Up Order',
                        'complaint' => '⚠️ Handling Complaint',
                        'delivery' => '🚚 Delivery',
                        'survey' => '📊 Survey/Research',
                        'other' => '📝 Lainnya'
                    ];
                    echo $purposes[$visit['visit_purpose']] ?? $visit['visit_purpose'];
                    ?>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Hasil Kunjungan:</small><br>
                    <?php
                    $results = [
                        'order_success' => ['✅ Berhasil Order', 'success'],
                        'follow_up_needed' => ['🔄 Perlu Follow Up', 'warning'],
                        'rejected' => ['❌ Ditolak', 'danger'],
                        'no_decision' => ['⏳ Belum Putus', 'info'],
                        'other' => ['📝 Lainnya', 'secondary']
                    ];
                    $result = $results[$visit['visit_result']] ?? [$visit['visit_result'], 'secondary'];
                    ?>
                    <span class="badge bg-<?= $result[1] ?>"><?= $result[0] ?></span>
                </div>

                <?php if ($visit['has_order']): ?>
                <div class="alert alert-success mb-0">
                    <strong><i class="fas fa-shopping-cart"></i> Order:</strong> 
                    Rp <?= number_format($visit['order_amount'], 0, ',', '.') ?>
                    <?php if (!empty($visit['order_notes'])): ?>
                        <br><small><?= nl2br(htmlspecialchars($visit['order_notes'])) ?></small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Photos -->
        <?php if (!empty($visit['photos'])): ?>
        <div class="detail-card">
            <div class="p-3">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-camera"></i> Dokumentasi Foto
                </h6>
                <div class="row g-2 photo-grid">
                    <?php 
                    $photos = is_array($visit['photos']) ? $visit['photos'] : (json_decode($visit['photos'], true) ?: []);
                    foreach ($photos as $photo): 
                    ?>
                        <div class="col-6">
                            <a href="<?= BASE_URL . $photo ?>" target="_blank">
                                <img src="<?= BASE_URL . $photo ?>" alt="Photo">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Notes & Feedback -->
        <div class="detail-card">
            <div class="p-3">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-comment-dots"></i> Catatan & Feedback
                </h6>
                
                <div class="mb-3">
                    <strong>Catatan Kunjungan:</strong>
                    <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($visit['visit_notes'])) ?></p>
                </div>
                
                <?php if (!empty($visit['customer_feedback'])): ?>
                <div class="mb-3">
                    <strong>Feedback Customer:</strong>
                    <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($visit['customer_feedback'])) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($visit['problems'])): ?>
                <div class="mb-3">
                    <strong>Problem/Keluhan:</strong>
                    <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($visit['problems'])) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($visit['next_action'])): ?>
                <div class="mb-3">
                    <strong>Tindak Lanjut:</strong>
                    <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($visit['next_action'])) ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($visit['next_visit_plan'])): ?>
                <div class="mb-0">
                    <strong>Rencana Kunjungan Berikutnya:</strong>
                    <p class="text-muted small mb-0"><?= date('d F Y', strtotime($visit['next_visit_plan'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- GPS Information -->
        <div class="detail-card">
            <div class="p-3">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-map-marker-alt"></i> Informasi GPS
                </h6>
                
                <div class="mb-3">
                    <small class="text-muted">Check-in Location:</small>
                    <p class="small mb-1">
                        <i class="fas fa-map-pin"></i> 
                        <?= $visit['check_in_latitude'] ?>, <?= $visit['check_in_longitude'] ?>
                    </p>
                    <?php if (!empty($visit['check_in_address'])): ?>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($visit['check_in_address']) ?></p>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($visit['check_out_latitude'])): ?>
                <div class="mb-3">
                    <small class="text-muted">Check-out Location:</small>
                    <p class="small mb-1">
                        <i class="fas fa-map-pin"></i> 
                        <?= $visit['check_out_latitude'] ?>, <?= $visit['check_out_longitude'] ?>
                    </p>
                    <?php if (!empty($visit['check_out_address'])): ?>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($visit['check_out_address']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($visit['distance_from_customer'] !== null): ?>
                <div>
                    <small class="text-muted">Jarak dari Customer:</small><br>
                    <span class="badge bg-<?= $visit['is_location_valid'] ? 'success' : 'warning' ?>">
                        <?= number_format($visit['distance_from_customer'], 0) ?>m
                        <?= $visit['is_location_valid'] ? '✓ Valid' : '⚠️ Melebihi toleransi' ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Metadata -->
        <div class="text-center text-muted small mb-5 pb-3">
            Dibuat: <?= date('d M Y H:i', strtotime($visit['created_at'])) ?> | 
            Diupdate: <?= date('d M Y H:i', strtotime($visit['updated_at'])) ?>
        </div>
    </main>

    <?php echo Notify::render(); ?>

    <script src="<?= BASE_URL ?>assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js?v=<?= time() ?>"></script>
</body>
</html>
