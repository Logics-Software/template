<?php
$content = '
<!-- Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4>Dashboard</h4>
    </div>
</div>

<!-- Upgrade Plan Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card upgrade-banner">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">Upgrade you plan for</h5>
                        <p class="mb-0 text-muted">Great experience</p>
                    </div>
                    <button class="btn btn-primary">Upgarde Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card analytics-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stats-content">
                        <h3>91.6K</h3>
                        <p>Website Traffic</p>
                        <div class="stats-change">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> 15%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card analytics-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-content">
                        <h3>15%</h3>
                        <p>Conversion Rate</p>
                        <div class="stats-change">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> 10%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card analytics-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-content">
                        <h3>90 Sec</h3>
                        <p>Session Duration</p>
                        <div class="stats-change">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> 25%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card analytics-stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-people"></i>
                    </div>
                    <div class="stats-content">
                        <h3>2,986</h3>
                        <p>Active Users</p>
                        <div class="stats-change">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> 4%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Earnings Section -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card earnings-card">
            <div class="card-body text-center">
                <h6 class="card-title">Earnings</h6>
                <h3 class="text-primary">$545.69</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card earnings-card">
            <div class="card-body text-center">
                <h6 class="card-title">Profit</h6>
                <h3 class="text-success">$256.34</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card earnings-card">
            <div class="card-body text-center">
                <h6 class="card-title">Expense</h6>
                <h3 class="text-danger">$74.19</h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5>Earning Reports</h5>
            </div>
            <div class="card-body">
                <canvas id="earningChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5>Traffic Source</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Browser</th>
                                <th>Sessions</th>
                                <th>Traffic</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Google</td>
                                <td>45,379</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Safari</td>
                                <td>78,379</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Edge</td>
                                <td>12,457</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Opera</td>
                                <td>6,570</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Firefox</td>
                                <td>6,568</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>UC Browser</td>
                                <td>4,800</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales by Countries -->
<div class="row mb-4">
        <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Sales by Countries</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary active">Today</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">This Week</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">Last Week</button>
                </div>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                        <div class="country-stats">
                            <h6>8,567</h6>
                            <p>United states</p>
                            <div class="progress">
                                <div class="progress-bar" style="width: 40.8%"></div>
                            </div>
                            <small class="text-muted">40.8%</small>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                        <div class="country-stats">
                            <h6>3,978</h6>
                            <p>Australia</p>
                            <div class="progress">
                                <div class="progress-bar" style="width: 35.8%"></div>
                            </div>
                            <small class="text-muted">35.8%</small>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                        <div class="country-stats">
                            <h6>9,874</h6>
                            <p>India</p>
                            <div class="progress">
                                <div class="progress-bar" style="width: 55.8%"></div>
                            </div>
                            <small class="text-muted">55.8%</small>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                        <div class="country-stats">
                            <h6>7,897</h6>
                            <p>Canada</p>
                            <div class="progress">
                                <div class="progress-bar" style="width: 30.0%"></div>
                            </div>
                            <small class="text-muted">30.0%</small>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                        <div class="country-stats">
                            <h6>6,487</h6>
                            <p>New Zealand</p>
                            <div class="progress">
                                <div class="progress-bar" style="width: 68.8%"></div>
                            </div>
                            <small class="text-muted">68.8%</small>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                        <div class="country-stats">
                            <h6>2,578</h6>
                            <p>France</p>
                            <div class="progress">
                                <div class="progress-bar" style="width: 68.8%"></div>
                            </div>
                            <small class="text-muted">68.8%</small>
                        </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
<!-- Visits by Source -->
<div class="row mb-4">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Visits by Source</h5>
                <button class="btn btn-sm btn-outline-primary">View All</button>
            </div>
                                <div class="card-body">
                <div class="visits-source-list">
                    <div class="visits-item">
                        <div class="visits-info">
                            <h6>Direct Marketing</h6>
                            <p>2,067</p>
                        </div>
                        <div class="visits-percentage">
                            <span class="badge bg-primary">2.6%</span>
                        </div>
                    </div>
                    <div class="visits-item">
                        <div class="visits-info">
                            <h6>Social Media Marketing</h6>
                            <p>7,895</p>
                        </div>
                        <div class="visits-percentage">
                            <span class="badge bg-success">4.8%</span>
                        </div>
                    </div>
                    <div class="visits-item">
                        <div class="visits-info">
                            <h6>Email Marketing</h6>
                            <p>45,150</p>
                        </div>
                        <div class="visits-percentage">
                            <span class="badge bg-info">6.5%</span>
                        </div>
                    </div>
                    <div class="visits-item">
                        <div class="visits-info">
                            <h6>Referrals</h6>
                            <p>1,478</p>
                        </div>
                        <div class="visits-percentage">
                            <span class="badge bg-warning">0.8%</span>
                        </div>
                    </div>
                    <div class="visits-item">
                        <div class="visits-info">
                            <h6>Digital Marketing</h6>
                            <p>25,058</p>
                        </div>
                        <div class="visits-percentage">
                            <span class="badge bg-secondary">2.02%</span>
                        </div>
                    </div>
                    <div class="visits-item">
                        <div class="visits-info">
                            <h6>Networing Marketing</h6>
                            <p>9,985</p>
                        </div>
                        <div class="visits-percentage">
                            <span class="badge bg-dark">3.08%</span>
                        </div>
                    </div>
                    <div class="visits-item">
                        <div class="visits-info">
                            <h6>Other</h6>
                            <p>6,124</p>
                        </div>
                        <div class="visits-percentage">
                            <span class="badge bg-danger">8.4%</span>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Campaign Source</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary">New Tickets</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">New Customer</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">New Contact</button>
                </div>
            </div>
                                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Medium</th>
                                <th>Impression</th>
                                <th>Campaign Name</th>
                                <th>Clicks</th>
                                <th>Cost</th>
                                <th>Conversion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Google</td>
                                <td>CPC</td>
                                <td>3,432,18</td>
                                <td>Summer Sale 2024</td>
                                <td>4,819,21</td>
                                <td>$2,876,23</td>
                                <td>3,218,49</td>
                            </tr>
                            <tr>
                                <td>Facebook</td>
                                <td>Social</td>
                                <td>4,432,18</td>
                                <td>Holiday Promo</td>
                                <td>1,224,56</td>
                                <td>$4,983,40</td>
                                <td>5,152,60</td>
                            </tr>
                            <tr>
                                <td>Instagram</td>
                                <td>Social</td>
                                <td>6,159,32</td>
                                <td>New Product Launch</td>
                                <td>8,951,34</td>
                                <td>$7,436,54</td>
                                <td>4,254,41</td>
                            </tr>
                            <tr>
                                <td>Twitter</td>
                                <td>Social</td>
                                <td>21,154,34</td>
                                <td>Flash Sale</td>
                                <td>12,018,30</td>
                                <td>$12,543,01</td>
                                <td>43,309,28</td>
                            </tr>
                            <tr>
                                <td>Affiliate</td>
                                <td>Affiliate</td>
                                <td>34,154,31</td>
                                <td>Partner Campaign</td>
                                <td>11,018,30</td>
                                <td>$18,650,58</td>
                                <td>89,309,28</td>
                            </tr>
                            <tr>
                                <td>YouTube</td>
                                <td>Video</td>
                                <td>14,154,31</td>
                                <td>Partner Campaign</td>
                                <td>18,018,30</td>
                                <td>$47,650,58</td>
                                <td>54,309,28</td>
                            </tr>
                        </tbody>
                    </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
<!-- Bottom Row -->
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5>Top Session</h5>
            </div>
                                <div class="card-body">
                <div class="session-stats">
                    <div class="session-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Chrome</span>
                            <span class="badge bg-primary">12.48%</span>
                        </div>
                    </div>
                    <div class="session-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Firefox</span>
                            <span class="badge bg-success">5.23%</span>
                        </div>
                    </div>
                    <div class="session-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Safari</span>
                            <span class="badge bg-info">15.58%</span>
                        </div>
                                        </div>
                    <div class="session-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Opera</span>
                            <span class="badge bg-warning">14.15%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Top Leads</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary">Sort by Created</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">Created</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">Converted</button>
                </div>
                                </div>
                                <div class="card-body">
                <div class="leads-list">
                    <div class="lead-item">
                        <div class="d-flex align-items-center">
                            <div class="lead-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-1.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="lead-info">
                                <h6 class="mb-0">John Hamilton</h6>
                                <small class="text-muted">johnehamilton@gmail.com</small>
                            </div>
                        </div>
                    </div>
                    <div class="lead-item">
                        <div class="d-flex align-items-center">
                            <div class="lead-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-2.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="lead-info">
                                <h6 class="mb-0">Janice Reese</h6>
                                <small class="text-muted">janicecreese@gmail.com</small>
                            </div>
                        </div>
                    </div>
                    <div class="lead-item">
                        <div class="d-flex align-items-center">
                            <div class="lead-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-3.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="lead-info">
                                <h6 class="mb-0">Andrew Kim</h6>
                                <small class="text-muted">andrewekim@gmail.com</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5>Top Performing Pages</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Pages</th>
                                <th>Click</th>
                                <th>Avg.position</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Index</td>
                                <td>1,101 -435</td>
                                <td>3.58 -2.45</td>
                            </tr>
                            <tr>
                                <td>Blog</td>
                                <td>657 -535</td>
                                <td>2.35 -1.05</td>
                            </tr>
                            <tr>
                                <td>Products</td>
                                <td>745 935</td>
                                <td>3.58 2.45</td>
                            </tr>
                            <tr>
                                <td>Licenses</td>
                                <td>1,587 235</td>
                                <td>7.47 -3.89</td>
                            </tr>
                            <tr>
                                <td>Affiliate</td>
                                <td>1,947 635</td>
                                <td>4.58 3.45</td>
                            </tr>
                            <tr>
                                <td>Socials</td>
                                <td>1,247 -735</td>
                                <td>4.41 -3.21</td>
                            </tr>
                            <tr>
                                <td>zoyothemes.com</td>
                                <td>847 -562</td>
                                <td>2.57 -1.21</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Earning Chart
const earningCtx = document.getElementById("earningChart");
if (earningCtx) {
    // Destroy existing chart if it exists
    if (window.earningChart && typeof window.earningChart.destroy === "function") {
        window.earningChart.destroy();
        window.earningChart = null;
    }
    
    // Check if chart already exists in Chart.js registry
    const existingChart = Chart.getChart(earningCtx);
    if (existingChart) {
        existingChart.destroy();
    }
    
    // Clear canvas
    const ctx = earningCtx.getContext("2d");
    ctx.clearRect(0, 0, earningCtx.width, earningCtx.height);
    
    const earningChart = new Chart(earningCtx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Earnings",
                data: [1200, 1900, 3000, 5000, 2000, 3000, 4500, 3800, 4200, 5100, 4800, 5500],
                backgroundColor: "rgba(54, 162, 235, 0.1)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }, {
                label: "Profit",
                data: [800, 1200, 2000, 3200, 1500, 2000, 3000, 2500, 2800, 3400, 3200, 3800],
                backgroundColor: "rgba(40, 167, 69, 0.1)",
                borderColor: "rgba(40, 167, 69, 1)",
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: "top"
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(0,0,0,0.1)"
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    // Store chart reference
    window.earningChart = earningChart;
}
</script>
';

// Echo the content
echo $content;
?>
