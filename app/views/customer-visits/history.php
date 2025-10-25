<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Riwayat Kunjungan' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/complete-optimized.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .visit-item {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            text-decoration: none;
            display: block;
            color: inherit;
            transition: all 0.3s;
        }
        .visit-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <main class="container-fluid p-3">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-history"></i> Riwayat Kunjungan
            </h5>
            <a href="<?= BASE_URL ?>customer-visits" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Statistics Summary -->
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="stats-card">
                    <h4 class="mb-0 text-primary"><?= $stats['total_visits'] ?? 0 ?></h4>
                    <small class="text-muted">Total</small>
                </div>
            </div>
            <div class="col-4">
                <div class="stats-card">
                    <h4 class="mb-0 text-success"><?= $stats['total_orders'] ?? 0 ?></h4>
                    <small class="text-muted">Order</small>
                </div>
            </div>
            <div class="col-4">
                <div class="stats-card">
                    <h4 class="mb-0 text-info"><?= number_format($stats['success_rate'] ?? 0, 0) ?>%</h4>
                    <small class="text-muted">Rate</small>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="filter-section">
            <form method="GET">
                <div class="row g-2">
                    <div class="col-12">
                        <input type="text" name="search" class="form-control" 
                               placeholder="🔍 Cari customer..." 
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    <div class="col-6">
                        <select name="month" class="form-select">
                            <option value="">Semua Bulan</option>
                            <?php 
                            $currentMonth = date('Y-m');
                            for ($i = 0; $i < 12; $i++): 
                                $month = date('Y-m', strtotime("-$i months"));
                                $selected = ($_GET['month'] ?? '') == $month ? 'selected' : '';
                            ?>
                                <option value="<?= $month ?>" <?= $selected ?>>
                                    <?= date('M Y', strtotime($month)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="result" class="form-select">
                            <option value="">Semua Hasil</option>
                            <option value="order_success" <?= ($_GET['result'] ?? '') == 'order_success' ? 'selected' : '' ?>>
                                ✅ Order
                            </option>
                            <option value="follow_up_needed" <?= ($_GET['result'] ?? '') == 'follow_up_needed' ? 'selected' : '' ?>>
                                🔄 Follow Up
                            </option>
                            <option value="rejected" <?= ($_GET['result'] ?? '') == 'rejected' ? 'selected' : '' ?>>
                                ❌ Ditolak
                            </option>
                            <option value="no_decision" <?= ($_GET['result'] ?? '') == 'no_decision' ? 'selected' : '' ?>>
                                ⏳ Belum Putus
                            </option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Visit List -->
        <?php if (!empty($visits)): ?>
            <?php foreach ($visits as $visit): ?>
                <a href="<?= BASE_URL ?>customer-visits/<?= $visit['id'] ?>" class="visit-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0"><?= htmlspecialchars($visit['customer_name']) ?></h6>
                        <small class="text-muted"><?= date('d M', strtotime($visit['visit_date'])) ?></small>
                    </div>
                    
                    <div class="d-flex gap-3 mb-2 text-muted small">
                        <span>
                            <i class="fas fa-clock"></i> 
                            <?= date('H:i', strtotime($visit['check_in_time'])) ?> - 
                            <?= $visit['check_out_time'] ? date('H:i', strtotime($visit['check_out_time'])) : '...' ?>
                        </span>
                        <?php if ($visit['duration_minutes']): ?>
                        <span>
                            <i class="fas fa-hourglass-half"></i> <?= $visit['duration_minutes'] ?>m
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php
                            $resultBadges = [
                                'order_success' => 'success',
                                'follow_up_needed' => 'warning',
                                'rejected' => 'danger',
                                'no_decision' => 'info',
                                'other' => 'secondary'
                            ];
                            $resultLabels = [
                                'order_success' => '✅ Order',
                                'follow_up_needed' => '🔄 Follow Up',
                                'rejected' => '❌ Ditolak',
                                'no_decision' => '⏳ Belum Putus',
                                'other' => '📝 Lainnya'
                            ];
                            $badge = $resultBadges[$visit['visit_result']] ?? 'secondary';
                            $label = $resultLabels[$visit['visit_result']] ?? $visit['visit_result'];
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= $label ?></span>
                            
                            <?php if ($visit['has_order']): ?>
                                <span class="badge bg-success ms-1">
                                    Rp <?= number_format($visit['order_amount'], 0, ',', '.') ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </a>
            <?php endforeach; ?>

            <!-- Pagination -->
            <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query(array_filter($_GET ?? [], fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)) ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <span class="btn btn-primary btn-sm">
                        <?= $pagination['current_page'] ?> / <?= $pagination['total_pages'] ?>
                    </span>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query(array_filter($_GET ?? [], fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY)) ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada riwayat kunjungan</p>
                <a href="<?= BASE_URL ?>customer-visits/select-customer" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Mulai Kunjungan Baru
                </a>
            </div>
        <?php endif; ?>

        <div class="pb-5"></div>
    </main>

    <?php echo Notify::render(); ?>

    <script src="<?= BASE_URL ?>assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js?v=<?= time() ?>"></script>
</body>
</html>
