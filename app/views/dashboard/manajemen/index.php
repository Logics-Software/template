<?php
// app/views/dashboard/manajemen/index.php
// This file will be included by DashboardController
?>

<!-- Management Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4><i class="fas fa-chart-line me-2"></i>Management Dashboard</h4>
        <p class="text-muted">Team performance and business insights</p>
    </div>
</div>

<!-- Management Stats Cards -->
<div class="row dashboard-row">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>156</h3>
                    <p>Team Members</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 12.5%
                        </span>
                        <span class="text-muted">This quarter</span>
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
                    <h3>Rp. 45.2M</h3>
                    <p>Revenue</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 8.3%
                        </span>
                        <span class="text-muted">This month</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>89%</h3>
                    <p>Customer Satisfaction</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 2.1%
                        </span>
                        <span class="text-muted">This month</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>234</h3>
                    <p>Active Projects</p>
                    <div class="stats-change">
                        <span class="text-info">
                            <i class="fas fa-project-diagram"></i> 15 new
                        </span>
                        <span class="text-muted">This week</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Management Charts Row -->
<div class="row dashboard-row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Revenue Trends</h5>
                <div class="card-actions">
                    <div class="btn-group mt-2" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active">This Month</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">This Quarter</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">This Year</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Team Performance</h5>
            </div>
            <div class="card-body">
                <canvas id="teamPerformanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Management Tools -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Management Tools</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/users" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i>Team Management
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-success w-100">
                            <i class="fas fa-envelope me-2"></i>Communications
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/call-center" class="btn btn-outline-warning w-100">
                            <i class="fas fa-phone me-2"></i>Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Team Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Project completed</h6>
                            <p class="text-muted">Marketing campaign finished by Sales team</p>
                            <small class="text-muted">1 hour ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h6>New team member</h6>
                            <p class="text-muted">Sarah joined the Marketing team</p>
                            <small class="text-muted">3 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Budget alert</h6>
                            <p class="text-muted">Marketing budget at 85% capacity</p>
                            <small class="text-muted">5 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Revenue (Million)',
                data: [8.5, 12.3, 15.7, 18.2],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Team Performance Chart
    const teamCtx = document.getElementById('teamPerformanceChart').getContext('2d');
    new Chart(teamCtx, {
        type: 'doughnut',
        data: {
            labels: ['Sales', 'Marketing', 'Support', 'Development'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107',
                    '#dc3545'
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
