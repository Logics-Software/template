<?php
$content = '
<!-- Dashboard Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 mb-0">Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, ' . (Session::get('user_name') ?? 'User') . '!</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download me-1"></i>Export
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-printer me-1"></i>Print
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus-circle me-1"></i>Add New
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Users
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . ($stats['total_users'] ?? 0) . '</div>
                        <div class="text-xs text-success">
                            <i class="fas fa-arrow-up me-1"></i>12% from last month
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Revenue
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$' . number_format($stats['total_revenue'] ?? 0) . '</div>
                        <div class="text-xs text-success">
                            <i class="fas fa-arrow-up me-1"></i>8% from last month
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-currency-dollar text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Conversion Rate
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . ($stats['conversion_rate'] ?? 0) . '%</div>
                        <div class="text-xs text-info">
                            <i class="fas fa-arrow-up me-1"></i>3% from last month
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Customers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . ($stats['total_customers'] ?? 0) . '</div>
                        <div class="text-xs text-warning">
                            <i class="fas fa-arrow-down me-1"></i>2% from last month
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-person-check text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Sales Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Sales Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-three-dots-vertical text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <a class="dropdown-item" href="#">This Month</a>
                        <a class="dropdown-item" href="#">Last Month</a>
                        <a class="dropdown-item" href="#">This Year</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Sales Pipeline</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2" style="height: 300px;">
                    <canvas id="pipelineChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="me-2">
                        <i class="fas fa-circle-fill text-primary"></i> Won
                    </span>
                    <span class="me-2">
                        <i class="fas fa-circle-fill text-success"></i> Discovery
                    </span>
                    <span class="me-2">
                        <i class="fas fa-circle-fill text-info"></i> Undiscovery
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity and Quick Actions -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Users</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';

foreach (($recentUsers ?? []) as $user) {
    $content .= '
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-person text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">' . htmlspecialchars($user['name'] ?? 'Unknown') . '</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>' . htmlspecialchars($user['email'] ?? 'N/A') . '</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>' . date('M d, Y', strtotime($user['created_at'] ?? 'now')) . '</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>';
}

$content .= '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="' . APP_URL . '/users/create" class="btn btn-outline-primary">
                        <i class="fas fa-person-plus me-2"></i>Add User
                    </a>
                    <button class="btn btn-outline-success">
                        <i class="fas fa-file-earmark-text me-2"></i>Generate Report
                    </button>
                    <button class="btn btn-outline-info">
                        <i class="fas fa-gear me-2"></i>Settings
                    </button>
                    <button class="btn btn-outline-warning">
                        <i class="fas fa-download me-2"></i>Export Data
                    </button>
                </div>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Server Status</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Database</span>
                        <span class="badge bg-success">Connected</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Last Backup</span>
                        <span class="text-muted">2 hours ago</span>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between">
                        <span>Uptime</span>
                        <span class="text-muted">99.9%</span>
                    </div>
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
                    color: "rgba(0,0,0,0.1)"
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
            backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc"],
            hoverBackgroundColor: ["#2e59d9", "#17a673", "#2c9faf"],
            borderWidth: 2,
            borderColor: "#fff"
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: "bottom"
            }
        }
    }
    });
    // Store chart reference
    window.pipelineChart = pipelineChart;
}
</script>
';
?>
