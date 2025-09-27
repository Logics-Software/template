<?php
$content = '
<!-- Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4>Dashboard</h4>
    </div>
</div>

<!-- Stats Cards -->
<div class="row dashboard-row">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>' . ($stats['total_customers'] ?? '3,456') . '</h3>
                    <p>Total Customers</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 12.5%
                        </span>
                        <span class="text-muted">Last 7 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>' . ($stats['task_pending'] ?? '2,839') . '</h3>
                    <p>Task Pending</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 1.5%
                        </span>
                        <span class="text-muted">Last 7 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-list-task"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>' . ($stats['total_deals'] ?? '2,254') . '</h3>
                    <p>Total Deals</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 12.8%
                        </span>
                        <span class="text-muted">Last 7 days</span>
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
                    <h3>$' . ($stats['total_revenue'] ?? '4,578') . '</h3>
                    <p>Total Revenue</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 18%
                        </span>
                        <span class="text-muted">Last 7 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>' . ($stats['conversion_rate'] ?? '14.57') . '%</h3>
                    <p>Conversion Rate</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 5.8%
                        </span>
                        <span class="text-muted">Last 7 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row dashboard-row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5>Sales Overview</h5>
                <div class="card-actions">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active">This Month</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">Last Month</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5>Sales Pipeline</h5>
            </div>
            <div class="card-body">
                <canvas id="pipelineChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tasks and Leads Row -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5>Tasks List</h5>
            </div>
            <div class="card-body">
                <div class="task-list">
                    <div class="task-item">
                        <div class="task-info">
                            <h6>Plan Product Launch Event</h6>
                            <p>Coordinate with the event planning with team</p>
                        </div>
                        <div class="task-status">
                            <span class="badge bg-primary">In Progress</span>
                        </div>
                    </div>
                    <div class="task-item">
                        <div class="task-info">
                            <h6>Prepare Monthly Sales Report</h6>
                            <p>Analyze sales trends and compile data</p>
                        </div>
                        <div class="task-status">
                            <span class="badge bg-warning">Pending</span>
                        </div>
                    </div>
                    <div class="task-item">
                        <div class="task-info">
                            <h6>Organize Team Meeting</h6>
                            <p>Set up a recurring weekly meeting</p>
                        </div>
                        <div class="task-status">
                            <span class="badge bg-success">Completed</span>
                        </div>
                    </div>
                    <div class="task-item">
                        <div class="task-info">
                            <h6>Sales Accounting</h6>
                            <p>Meeting for the sales team regarding growth</p>
                        </div>
                        <div class="task-status">
                            <span class="badge bg-info">Scheduled</span>
                        </div>
                    </div>
                    <div class="task-item">
                        <div class="task-info">
                            <h6>Update User Database</h6>
                            <p>Hando project s2 to amazon cloud database setup</p>
                        </div>
                        <div class="task-status">
                            <span class="badge bg-warning">Pending</span>
                        </div>
                    </div>
                    <div class="task-item">
                        <div class="task-info">
                            <h6>Update Front-end project UI</h6>
                            <p>For the handover of the project, update the new UI design</p>
                        </div>
                        <div class="task-status">
                            <span class="badge bg-primary">In Progress</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5>Leads Overview</h5>
            </div>
            <div class="card-body">
                <div class="leads-stats">
                    <div class="leads-item">
                        <div class="leads-info">
                            <h6>New Leads</h6>
                            <h3>45</h3>
                        </div>
                        <div class="leads-progress">
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="leads-item">
                        <div class="leads-info">
                            <h6>Qualified Leads</h6>
                            <h3>32</h3>
                        </div>
                        <div class="leads-progress">
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="leads-item">
                        <div class="leads-info">
                            <h6>Converted</h6>
                            <h3>18</h3>
                        </div>
                        <div class="leads-progress">
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row dashboard-row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Latest Transactions</h5>
            </div>
            <div class="card-body">
                <div class="transaction-list">
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-1.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="transaction-details">
                                <h6>Bob Dean</h6>
                                <p>Transfer to bank account</p>
                            </div>
                        </div>
                        <div class="transaction-amount">
                            <span class="amount">$158.00 USD</span>
                            <span class="date">24 Jan, 2024</span>
                            <span class="status badge bg-warning">Pending</span>
                        </div>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-2.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="transaction-details">
                                <h6>Bank of America</h6>
                                <p>Withdrawal to account</p>
                            </div>
                        </div>
                        <div class="transaction-amount">
                            <span class="amount">$258.00 USD</span>
                            <span class="date">26 June, 2024</span>
                            <span class="status badge bg-success">Completed</span>
                        </div>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-3.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="transaction-details">
                                <h6>Slack</h6>
                                <p>Subscription to plan</p>
                            </div>
                        </div>
                        <div class="transaction-amount">
                            <span class="amount">-$154.00 USD</span>
                            <span class="date">12 May, 2024</span>
                            <span class="status badge bg-danger">Failed</span>
                        </div>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-1.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="transaction-details">
                                <h6>Asana</h6>
                                <p>Subscription payment</p>
                            </div>
                        </div>
                        <div class="transaction-amount">
                            <span class="amount">$258.00 USD</span>
                            <span class="date">15 Feb, 2024</span>
                            <span class="status badge bg-success">Completed</span>
                        </div>
                    </div>
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-avatar">
                                <img src="' . APP_URL . '/assets/images/users/user-2.jpg" alt="User" class="rounded-circle">
                            </div>
                            <div class="transaction-details">
                                <h6>Github Copilot</h6>
                                <p>Renew A Plan</p>
                            </div>
                        </div>
                        <div class="transaction-amount">
                            <span class="amount">$89.00 USD</span>
                            <span class="date">25 April, 2024</span>
                            <span class="status badge bg-success">Completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance and Leads Report Row -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5>Your Recent Performance</h5>
            </div>
            <div class="card-body">
                <div class="performance-stats">
                    <div class="performance-info">
                        <h6>78% increase in company growth.</h6>
                        <div class="performance-values">
                            <div class="value-item">
                                <h3>$32.5k</h3>
                                <p>Previous Period</p>
                            </div>
                            <div class="value-item">
                                <h3>$41.2k</h3>
                                <p>Current Period</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5>Leads Report</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Lead</th>
                                <th>Email</th>
                                <th>Phone No</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="' . APP_URL . '/assets/images/users/user-1.jpg" alt="User" class="rounded-circle me-2" width="32" height="32">
                                        <span>John Hamilton</span>
                                    </div>
                                </td>
                                <td>johnehamilton@gmail.com</td>
                                <td>+48, 65610085</td>
                                <td>Mufti</td>
                                <td><span class="badge bg-primary">New Lead</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="' . APP_URL . '/assets/images/users/user-2.jpg" alt="User" class="rounded-circle me-2" width="32" height="32">
                                        <span>Janice Reese</span>
                                    </div>
                                </td>
                                <td>janicecreese@gmail.com</td>
                                <td>+45, 32678972</td>
                                <td>Gucci</td>
                                <td><span class="badge bg-warning">In Progress</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="' . APP_URL . '/assets/images/users/user-3.jpg" alt="User" class="rounded-circle me-2" width="32" height="32">
                                        <span>Andrew Kim</span>
                                    </div>
                                </td>
                                <td>andrewekim@gmail.com</td>
                                <td>+30, 84787124</td>
                                <td>Vans</td>
                                <td><span class="badge bg-danger">Loss</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="' . APP_URL . '/assets/images/users/user-1.jpg" alt="User" class="rounded-circle me-2" width="32" height="32">
                                        <span>Kathryn Sanchez</span>
                                    </div>
                                </td>
                                <td>kathryntsanchez@gmail.com</td>
                                <td>+30, 23794209</td>
                                <td>Myntra</td>
                                <td><span class="badge bg-success">Won</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="' . APP_URL . '/assets/images/users/user-2.jpg" alt="User" class="rounded-circle me-2" width="32" height="32">
                                        <span>Diane Richards</span>
                                    </div>
                                </td>
                                <td>dianetrichards@gmail.com</td>
                                <td>+78, 37569176</td>
                                <td>HCLTech</td>
                                <td><span class="badge bg-info">Converted</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Sales Chart
const salesCtx = document.getElementById("salesChart");
if (salesCtx) {
    // Destroy all existing charts on this canvas
    try {
        // Destroy from window reference
        if (window.salesChart && typeof window.salesChart.destroy === "function") {
            window.salesChart.destroy();
            window.salesChart = null;
        }
        
        // Destroy from Chart.js registry
        const existingChart = Chart.getChart(salesCtx);
        if (existingChart) {
            existingChart.destroy();
        }
        
        // Clear canvas completely
        const ctx = salesCtx.getContext("2d");
        ctx.clearRect(0, 0, salesCtx.width, salesCtx.height);
        
        // Reset canvas size to force complete refresh
        salesCtx.width = salesCtx.offsetWidth;
        salesCtx.height = salesCtx.offsetHeight;
        
    } catch (e) {
        console.warn("Error destroying existing chart:", e);
    }
    
    const salesChart = new Chart(salesCtx, {
        type: "line",
        data: {
            labels: ' . json_encode($chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) . ',
            datasets: ' . json_encode($chartData['datasets'] ?? [[
                'label' => 'Sales',
                'data' => [12, 19, 3, 5, 2, 3],
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 2,
                'tension' => 0.4
            ]]) . '
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
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
    window.salesChart = salesChart;
}

// Pipeline Chart
const pipelineCtx = document.getElementById("pipelineChart");
if (pipelineCtx) {
    // Destroy all existing charts on this canvas
    try {
        // Destroy from window reference
        if (window.pipelineChart && typeof window.pipelineChart.destroy === "function") {
            window.pipelineChart.destroy();
            window.pipelineChart = null;
        }
        
        // Destroy from Chart.js registry
        const existingChart = Chart.getChart(pipelineCtx);
        if (existingChart) {
            existingChart.destroy();
        }
        
        // Clear canvas completely
        const ctx = pipelineCtx.getContext("2d");
        ctx.clearRect(0, 0, pipelineCtx.width, pipelineCtx.height);
        
        // Reset canvas size to force complete refresh
        pipelineCtx.width = pipelineCtx.offsetWidth;
        pipelineCtx.height = pipelineCtx.offsetHeight;
        
    } catch (e) {
        console.warn("Error destroying existing chart:", e);
    }
    
    const pipelineChart = new Chart(pipelineCtx, {
        type: "doughnut",
        data: {
            labels: ["Won", "Discovery", "Undiscovery"],
            datasets: [{
                data: [12.48, 5.23, 15.58],
                backgroundColor: [
                    "rgba(54, 162, 235, 0.8)",
                    "rgba(255, 206, 86, 0.8)",
                    "rgba(255, 99, 132, 0.8)"
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        }
    });
    
    // Store chart reference
    window.pipelineChart = pipelineChart;
}
</script>

<!-- Test Content for Sticky Header -->
<div class="row dashboard-row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Test Sticky Header</h5>
            </div>
            <div class="card-body">
                <p>Scroll down to test if the header remains sticky at the top.</p>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Test Content 1</h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Test Content 2</h6>
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Test Content -->
<div class="row dashboard-row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>More Test Content</h5>
                <p>This content is added to test the sticky header functionality. The header should remain visible at the top when scrolling through this content.</p>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6>Card 1</h6>
                                <p>Content for testing sticky header behavior.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6>Card 2</h6>
                                <p>More content for testing sticky header behavior.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6>Card 3</h6>
                                <p>Additional content for testing sticky header behavior.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Even More Test Content -->
<div class="row dashboard-row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Extended Test Content</h5>
                <p>This section contains more content to ensure there is enough scrollable content to test the sticky header functionality.</p>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Section A</h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Section B</h6>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Final Test Content -->
<div class="row dashboard-row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Final Test Section</h5>
                <p>This is the final section to test sticky header behavior. Scroll up and down to verify that the header remains sticky at the top of the viewport.</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Sticky Header Test:</strong> The header should remain visible at the top when scrolling through this content.
                </div>
            </div>
        </div>
    </div>
</div>
';

// Echo the content
echo $content;
?>

