<?php 
$this->layout('layouts/app', ['title' => $title]) ?>

<?php $this->section('content') ?>
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-chart-line me-2"></i>Monitoring Kunjungan Customer</h4>
        <a href="<?= BASE_URL ?>customer-visits-monitoring/report" class="btn btn-primary">
            <i class="fas fa-file-export"></i> Generate Report
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <input type="month" name="month" class="form-control" 
                           value="<?= htmlspecialchars($selectedMonth) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Marketing</label>
                    <select name="marketing_id" class="form-select">
                        <option value="">-- Semua Marketing --</option>
                        <?php foreach ($marketingList as $marketing): ?>
                            <option value="<?= $marketing['id'] ?>" 
                                    <?= $selectedMarketing == $marketing['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($marketing['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h6 class="text-muted mb-1">Total Kunjungan</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_visits']) ?></h3>
                        </div>
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h6 class="text-muted mb-1">Total Order</h6>
                            <h3 class="mb-0"><?= number_format($stats['total_orders']) ?></h3>
                        </div>
                        <div class="stats-icon bg-success">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h6 class="text-muted mb-1">Total Nilai Order</h6>
                            <h3 class="mb-0">Rp <?= number_format($stats['total_order_amount'], 0, ',', '.') ?></h3>
                        </div>
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stats-info">
                            <h6 class="text-muted mb-1">Success Rate</h6>
                            <h3 class="mb-0"><?= number_format($stats['success_rate'], 1) ?>%</h3>
                        </div>
                        <div class="stats-icon bg-info">
                            <i class="fas fa-percentage"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Marketing Performance -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Performance Marketing</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Marketing</th>
                                    <th class="text-center">Kunjungan</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Order</th>
                                    <th class="text-end">Nilai Order</th>
                                    <th class="text-center">Achievement</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($marketingPerformance)): ?>
                                    <?php foreach ($marketingPerformance as $perf): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($perf['marketing_name']) ?></strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary"><?= $perf['total_visits'] ?></span>
                                            </td>
                                            <td class="text-center"><?= $perf['unique_customers'] ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-success"><?= $perf['total_orders'] ?></span>
                                            </td>
                                            <td class="text-end">Rp <?= number_format($perf['total_order_amount'], 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <?php 
                                                $achievement = $perf['visit_achievement'] ?? 0;
                                                $badgeClass = $achievement >= 100 ? 'success' : ($achievement >= 75 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= number_format($achievement, 0) ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>customer-visits-monitoring/marketing/<?= $perf['id'] ?>?month=<?= $selectedMonth ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top Customers</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($topCustomers)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($topCustomers as $index => $customer): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary me-2">#<?= $index + 1 ?></span>
                                                <strong><?= htmlspecialchars($customer['customer_name']) ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                <?= $customer['visit_count'] ?> kunjungan, 
                                                <?= $customer['order_count'] ?> order
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <strong class="text-success">
                                                Rp <?= number_format($customer['total_order_amount'], 0, ',', '.') ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">Tidak ada data</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit Trends Chart -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>Trend Kunjungan (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="visitTrendsChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Visits -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Kunjungan Terbaru</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentVisits)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Marketing</th>
                                        <th>Customer</th>
                                        <th>Tujuan</th>
                                        <th>Hasil</th>
                                        <th>Durasi</th>
                                        <th>Order</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentVisits as $visit): ?>
                                        <tr>
                                            <td><?= date('d M Y H:i', strtotime($visit['check_in_time'])) ?></td>
                                            <td><?= htmlspecialchars($visit['marketing_name']) ?></td>
                                            <td><?= htmlspecialchars($visit['customer_name']) ?></td>
                                            <td>
                                                <?php
                                                $purposes = [
                                                    'sales' => 'Sales',
                                                    'follow_up' => 'Follow Up',
                                                    'complaint' => 'Complaint',
                                                    'delivery' => 'Delivery',
                                                    'survey' => 'Survey',
                                                    'other' => 'Lainnya'
                                                ];
                                                echo $purposes[$visit['visit_purpose']] ?? $visit['visit_purpose'];
                                                ?>
                                            </td>
                                            <td>
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
                                                    <?= ucfirst(str_replace('_', ' ', $visit['visit_result'])) ?>
                                                </span>
                                            </td>
                                            <td><?= $visit['duration_minutes'] ?? '-' ?> mnt</td>
                                            <td>
                                                <?php if ($visit['has_order']): ?>
                                                    <span class="text-success">
                                                        Rp <?= number_format($visit['order_amount'], 0, ',', '.') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>customer-visits/<?= $visit['id'] ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">Tidak ada data kunjungan</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->end() ?>

<?php $this->section('js') ?>
<script src="<?= BASE_URL ?>assets/js/chart.js"></script>
<script>
    // Visit Trends Chart
    const visitTrendsData = <?= json_encode($visitTrends) ?>;
    
    const labels = visitTrendsData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    });
    
    const visitData = visitTrendsData.map(item => item.total_visits);
    const orderData = visitTrendsData.map(item => item.total_orders);
    
    const ctx = document.getElementById('visitTrendsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Kunjungan',
                    data: visitData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4
                },
                {
                    label: 'Order',
                    data: orderData,
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<?php $this->end() ?>

