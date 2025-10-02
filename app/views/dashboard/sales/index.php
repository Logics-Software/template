<?php
// app/views/dashboard/sales/index.php
// This file will be included by DashboardController
?>

<!-- Sales Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4><i class="fas fa-handshake me-2"></i>Sales Dashboard</h4>
        <p class="text-muted">Sales pipeline and performance metrics</p>
    </div>
</div>

<!-- Sales Stats Cards -->
<div class="row dashboard-row">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>Rp. 15.2M</h3>
                    <p>Sales Target</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 78%
                        </span>
                        <span class="text-muted">Achieved</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-target"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>45</h3>
                    <p>Active Leads</p>
                    <div class="stats-change">
                        <span class="text-info">
                            <i class="fas fa-users"></i> Hot
                        </span>
                        <span class="text-muted">This month</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>23</h3>
                    <p>Deals Closed</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-check"></i> 12 this week
                        </span>
                        <span class="text-muted">This month</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>15.2%</h3>
                    <p>Conversion Rate</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 3.1%
                        </span>
                        <span class="text-muted">This month</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Charts Row -->
<div class="row dashboard-row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Sales Pipeline</h5>
                <div class="card-actions">
                    <div class="btn-group mt-2" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active">This Month</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">This Quarter</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">This Year</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="salesPipelineChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Lead Sources</h5>
            </div>
            <div class="card-body">
                <canvas id="leadSourcesChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Sales Tools -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Sales Tools</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus me-2"></i>New Lead
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-success w-100">
                            <i class="fas fa-envelope me-2"></i>Follow Up
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/call-center" class="btn btn-outline-info w-100">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp</a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/users" class="btn btn-outline-warning w-100">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Sales Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Deal closed</h6>
                            <p class="text-muted">Rp. 2.5M deal with PT ABC</p>
                            <small class="text-muted">1 hour ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h6>New lead</h6>
                            <p class="text-muted">Hot lead from website</p>
                            <small class="text-muted">3 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-warning">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Follow up scheduled</h6>
                            <p class="text-muted">Call with potential client tomorrow</p>
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
    // Sales Pipeline Chart
    const pipelineCtx = document.getElementById('salesPipelineChart').getContext('2d');
    new Chart(pipelineCtx, {
        type: 'bar',
        data: {
            labels: ['Prospects', 'Qualified', 'Proposal', 'Negotiation', 'Closed Won', 'Closed Lost'],
            datasets: [{
                label: 'Number of Deals',
                data: [45, 35, 25, 15, 23, 8],
                backgroundColor: [
                    '#6c757d',
                    '#17a2b8',
                    '#ffc107',
                    '#fd7e14',
                    '#28a745',
                    '#dc3545'
                ]
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

    // Lead Sources Chart
    const sourcesCtx = document.getElementById('leadSourcesChart').getContext('2d');
    new Chart(sourcesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Website', 'Referral', 'Social Media', 'Cold Call', 'Email'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    '#007bff',
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
