<?php
$content = '
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            <i class="fas fa-map-marked-alt"></i> Customer Visits
        </h4>
        <span class="badge bg-primary">' . date('d M Y') . '</span>
    </div>

    <!-- Active Visit Banner -->
    ' . (!empty($active_visit) ? '
    <div class="alert alert-warning" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    <i class="fas fa-clock"></i> Kunjungan Aktif
                </h5>
                <p class="mb-0">' . htmlspecialchars($active_visit['customer_name']) . '</p>
                <small>Check-in: ' . date('H:i', strtotime($active_visit['check_in_time'])) . '</small>
            </div>
            <a href="' . BASE_URL . 'customer-visits/active/' . $active_visit['id'] . '" 
               class="btn btn-light btn-sm">
                <i class="fas fa-arrow-right"></i> Lanjutkan
            </a>
        </div>
    </div>
    ' : '') . '

    <!-- Statistics -->
    <div class="row mb-3">
        <div class="col-6 col-md-3">
            <div class="card text-center" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                <div class="card-body">
                    <small>Hari Ini</small>
                    <h3 class="mb-0">' . count($today_visits ?? []) . '</h3>
                    <small>Kunjungan</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="card-body">
                    <small>Bulan Ini</small>
                    <h3 class="mb-0">' . ($month_stats['total_visits'] ?? 0) . '</h3>
                    <small>Kunjungan</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="card-body">
                    <small>Order</small>
                    <h3 class="mb-0">' . ($month_stats['total_orders'] ?? 0) . '</h3>
                    <small>Berhasil</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <small>Success Rate</small>
                    <h3 class="mb-0">
                        ' . (function() use ($month_stats) {
                            $total = $month_stats['total_visits'] ?? 0;
                            $success = $month_stats['success_count'] ?? 0;
                            return $total > 0 ? round(($success / $total) * 100) : 0;
                        })() . '%
                    </h3>
                    <small>Conversion</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
        </div>
        <div class="card-body">
            ' . (empty($active_visit) ? '
            <a href="' . BASE_URL . 'customer-visits/select-customer" 
               class="btn btn-primary btn-lg w-100 mb-2">
                <i class="fas fa-plus-circle"></i> Mulai Kunjungan Baru
            </a>
            ' : '
            <a href="' . BASE_URL . 'customer-visits/active/' . $active_visit['id'] . '" 
               class="btn btn-success btn-lg w-100 mb-2">
                <i class="fas fa-check-circle"></i> Selesaikan Kunjungan Aktif
            </a>
            ') . '
            
            <a href="' . BASE_URL . 'customer-visits/history" 
               class="btn btn-outline-primary w-100 mb-2">
                <i class="fas fa-history"></i> Riwayat Kunjungan
            </a>
            
            <a href="' . BASE_URL . 'customer-visits-customers" 
               class="btn btn-outline-secondary w-100">
                <i class="fas fa-users"></i> Daftar Customer
            </a>
        </div>
    </div>

    <!-- Today Visits -->
    ' . (!empty($today_visits) ? '
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-calendar-day"></i> Kunjungan Hari Ini</h6>
        </div>
        <div class="card-body">
            ' . implode('', array_map(function($visit) {
                return '
            <div class="border-start border-primary border-4 p-2 mb-2" style="background: #f8f9fa; border-radius: 4px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>' . htmlspecialchars($visit['customer_name']) . '</strong>
                        <br>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> 
                            ' . date('H:i', strtotime($visit['check_in_time'])) . '
                            ' . ($visit['check_out_time'] ? 
                                '- ' . date('H:i', strtotime($visit['check_out_time'])) . ' (' . $visit['duration_minutes'] . ' menit)' 
                                : '<span class="badge bg-warning text-dark">Aktif</span>') . '
                        </small>
                    </div>
                    <a href="' . BASE_URL . 'customer-visits/' . $visit['id'] . '" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                ' . ($visit['has_order'] ? '
                <div class="mt-2">
                    <span class="badge bg-success">
                        <i class="fas fa-check"></i> Order: Rp ' . number_format($visit['order_amount'], 0, ',', '.') . '
                    </span>
                </div>
                ' : '') . '
            </div>';
            }, $today_visits)) . '
        </div>
    </div>
    ' : '') . '

    <!-- My Customers -->
    ' . (!empty($customers) && count($customers) > 0 ? '
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-building"></i> Customer Saya</h6>
        </div>
        <div class="card-body">
            <div class="row">
                ' . implode('', array_map(function($customer) {
                    return '
                <div class="col-6 mb-2">
                    <a href="' . BASE_URL . 'customer-visits/select-customer?customer_id=' . $customer['id'] . '" 
                       class="btn btn-outline-secondary btn-sm w-100 text-start text-truncate">
                        <i class="fas fa-store"></i> 
                        ' . htmlspecialchars($customer['customer_name']) . '
                    </a>
                </div>';
                }, array_slice($customers, 0, 4))) . '
            </div>
            ' . (count($customers) > 4 ? '
            <a href="' . BASE_URL . 'customer-visits-customers" class="btn btn-link btn-sm w-100 mt-2">
                Lihat semua (' . count($customers) . ' customer) →
            </a>
            ' : '') . '
        </div>
    </div>
    ' : '') . '
</div>
';
echo $content;
?>
