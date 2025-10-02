<?php
// app/views/dashboard/admin/index.php
// This file will be included by DashboardController
?>

<!-- Admin Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4><i class="fas fa-crown me-2"></i>Admin Dashboard</h4>
        <p class="text-muted">System overview and management tools</p>
    </div>
</div>

<!-- Admin Stats Cards -->
<div class="row dashboard-row">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>1,234</h3>
                    <p>Total Users</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 8.2%
                        </span>
                        <span class="text-muted">This month</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>98.5%</h3>
                    <p>System Uptime</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-check-circle"></i> Excellent
                        </span>
                        <span class="text-muted">Last 30 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-server"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>45</h3>
                    <p>Active Sessions</p>
                    <div class="stats-change">
                        <span class="text-info">
                            <i class="fas fa-eye"></i> Live
                        </span>
                        <span class="text-muted">Currently online</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-globe"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>2.1GB</h3>
                    <p>Storage Used</p>
                    <div class="stats-change">
                        <span class="text-warning">
                            <i class="fas fa-hdd"></i> 65%
                        </span>
                        <span class="text-muted">Of total capacity</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Charts Row -->
<div class="row dashboard-row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">System Performance</h5>
                <div class="card-actions">
                    <div class="btn-group mt-2" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active">Last 7 Days</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">Last 30 Days</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">Last 90 Days</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="systemPerformanceChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="userDistributionChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Admin Management Tools -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/users" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/konfigurasi" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-info w-100">
                            <i class="fas fa-envelope me-2"></i>Messages
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/call-center" class="btn btn-outline-success w-100">
                            <i class="fab fa-whatsapp me-2"></i>Call Center
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent System Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h6>New user registered</h6>
                            <p class="text-muted">John Doe registered as Sales</p>
                            <small class="text-muted">2 minutes ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="activity-content">
                            <h6>System configuration updated</h6>
                            <p class="text-muted">Company settings modified</p>
                            <small class="text-muted">15 minutes ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <h6>High memory usage detected</h6>
                            <p class="text-muted">Server memory usage at 85%</p>
                            <small class="text-muted">1 hour ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // System Performance Chart
    const systemCtx = document.getElementById('systemPerformanceChart').getContext('2d');
    new Chart(systemCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'CPU Usage (%)',
                data: [65, 70, 68, 75, 72, 68, 70],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }, {
                label: 'Memory Usage (%)',
                data: [45, 50, 48, 55, 52, 48, 50],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // User Distribution Chart
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Manajemen', 'User', 'Marketing', 'Sales', 'Customer'],
            datasets: [{
                data: [5, 10, 45, 15, 20, 5],
                backgroundColor: [
                    '#dc3545',
                    '#fd7e14',
                    '#ffc107',
                    '#20c997',
                    '#0dcaf0',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
