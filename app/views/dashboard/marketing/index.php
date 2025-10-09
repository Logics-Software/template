<?php
// app/views/dashboard/marketing/index.php
// This file will be included by DashboardController
?>

<!-- Marketing Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4><i class="fas fa-bullhorn me-2"></i>Marketing Dashboard</h4>
        <p class="text-muted">Campaign performance and lead generation</p>
    </div>
</div>

<!-- Marketing Stats Cards -->
<div class="row dashboard-row">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>2,456</h3>
                    <p>Total Leads</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 15.3%
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
                    <h3>12.5%</h3>
                    <p>Conversion Rate</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 2.1%
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

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>8</h3>
                    <p>Active Campaigns</p>
                    <div class="stats-change">
                        <span class="text-info">
                            <i class="fas fa-play"></i> Running
                        </span>
                        <span class="text-muted">Currently active</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>Rp. 2.8M</h3>
                    <p>Campaign ROI</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 18.7%
                        </span>
                        <span class="text-muted">This quarter</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Marketing Charts Row -->
<div class="row dashboard-row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Lead Generation Trends</h5>
                <div class="card-actions">
                    <div class="btn-group mt-2" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active">This Month</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">This Quarter</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">This Year</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="leadGenerationChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Campaign Performance</h5>
            </div>
            <div class="card-body">
                <canvas id="campaignPerformanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Marketing Tools -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Marketing Tools</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-outline-primary w-100">
                            <i class="fas fa-envelope me-2"></i>Email Campaign
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/callcenter" class="btn btn-outline-success w-100">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp Campaign
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>Analytics
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/users" class="btn btn-outline-warning w-100">
                            <i class="fas fa-users me-2"></i>Lead Management
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Marketing Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Campaign launched</h6>
                            <p class="text-muted">Summer promotion campaign started</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h6>New lead captured</h6>
                            <p class="text-muted">Lead from social media campaign</p>
                            <small class="text-muted">4 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Conversion milestone</h6>
                            <p class="text-muted">Reached 100 conversions this month</p>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lead Generation Chart
    const leadCtx = document.getElementById('leadGenerationChart').getContext('2d');
    new Chart(leadCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Leads Generated',
                data: [120, 150, 180, 200],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Conversions',
                data: [15, 18, 22, 25],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
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

    // Campaign Performance Chart
    const campaignCtx = document.getElementById('campaignPerformanceChart').getContext('2d');
    new Chart(campaignCtx, {
        type: 'doughnut',
        data: {
            labels: ['Email', 'Social Media', 'WhatsApp', 'Direct'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#20c997',
                    '#ffc107'
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
