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
                    <h3>3,456</h3>
                    <p>Total Customers</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 12.5%
                        </span>
                        <span class="text-muted">Last 7 days</span>
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
                    <h5>Rp. 24,839,398</h5>
                    <p>Omset Penjualan</p>
                    <div class="stats-change">
                        <span class="text-warning">
                            <i class="fas fa-arrow-up"></i> 1.5%
                        </span>
                        <span class="text-muted">Last 7 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-money-check-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h4>Rp. 23,254</h4>
                    <p>Retur Penjualan</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 12.8%
                        </span>
                        <span class="text-muted">Last 7 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h5>Rp. 24,245,780</h5>
                    <p>Penerimaan/Inkaso</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> 18%
                        </span>
                        <span class="text-muted">Last 7 days</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row dashboard-row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Sales Overview</h5>
                <div class="card-actions">
                    <div class="btn-group mt-2" role="group">
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
</div>

<script>
// Chart initialization function with proper cleanup
function initializeCharts() {
    // Destroy all existing charts first
    Chart.helpers.each(Chart.instances, function(instance) {
        if (instance && typeof instance.destroy === "function") {
            instance.destroy();
        }
    });
    
    // Clear window references
    if (window.salesChart) {
        window.salesChart = null;
    }
    if (window.pipelineChart) {
        window.pipelineChart = null;
    }
    
    // Wait a bit to ensure cleanup is complete
    setTimeout(function() {
        // Sales Chart
        const salesCtx = document.getElementById("salesChart");
        if (salesCtx) {
            try {
                // Double check for existing chart
                const existingSalesChart = Chart.getChart(salesCtx);
                if (existingSalesChart) {
                    existingSalesChart.destroy();
                }
                
                // Clear canvas
                const ctx = salesCtx.getContext("2d");
                ctx.clearRect(0, 0, salesCtx.width, salesCtx.height);
                
                // Reset canvas size
                salesCtx.width = salesCtx.offsetWidth;
                salesCtx.height = salesCtx.offsetHeight;
                
                // Create new chart
                const salesChart = new Chart(salesCtx, {
                    type: "line",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
                        datasets: [{
                            label: "Penjualan",
                            data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000],
                            backgroundColor: "rgba(54, 162, 235, 0.2)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },{
                            label: "Pembelian",
                            data: [10000, 9000, 25000, 22000, 21000, 29000, 18000, 37000],
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderColor: "rgb(255, 99, 132)",
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },{
                            label: "Inkaso",
                            data: [11000, 13000, 21000, 23000, 24000, 31000, 22000, 32000],
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            borderColor: "rgb(75, 192, 192)",
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: "index"
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: "top",
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                backgroundColor: "rgba(0, 0, 0, 0.8)",
                                titleColor: "white",
                                bodyColor: "white",
                                borderColor: "rgba(255, 255, 255, 0.1)",
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: "rgba(0,0,0,0.1)",
                                    drawBorder: false
                                },
                                ticks: {
                                    color: "rgba(0,0,0,0.6)",
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: "rgba(0,0,0,0.6)",
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        elements: {
                            point: {
                                radius: 4,
                                hoverRadius: 6
                            }
                        }
                    }
                });
                
                // Store chart reference
                window.salesChart = salesChart;
                
            } catch (e) {
                // Chart creation failed silently
            }
        }
        
        // Pipeline Chart
        const pipelineCtx = document.getElementById("pipelineChart");
        if (pipelineCtx) {
            try {
                // Double check for existing chart
                const existingPipelineChart = Chart.getChart(pipelineCtx);
                if (existingPipelineChart) {
                    existingPipelineChart.destroy();
                }
                
                // Clear canvas
                const ctx = pipelineCtx.getContext("2d");
                ctx.clearRect(0, 0, pipelineCtx.width, pipelineCtx.height);
                
                // Reset canvas size
                pipelineCtx.width = pipelineCtx.offsetWidth;
                pipelineCtx.height = pipelineCtx.offsetHeight;
                
                // Create new chart
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
                
            } catch (e) {
                // Chart creation failed silently
            }
        }
    }, 100);
}

// Initialize charts when DOM is ready
document.addEventListener("DOMContentLoaded", function() {
    initializeCharts();
    
    // Add responsive functionality for sidebar toggle
    const sidebarToggle = document.getElementById("sidebarToggle");
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function() {
            // Resize charts after sidebar animation completes
            setTimeout(function() {
                if (window.salesChart && !window.salesChart.destroyed) {
                    window.salesChart.resize();
                }
                if (window.pipelineChart && !window.pipelineChart.destroyed) {
                    window.pipelineChart.resize();
                }
            }, 350); // Wait for sidebar animation (300ms + 50ms buffer)
        });
    }
    
    // Handle window resize for responsive behavior
    let resizeTimeout;
    window.addEventListener("resize", function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (window.salesChart && !window.salesChart.destroyed) {
                window.salesChart.resize();
            }
            if (window.pipelineChart && !window.pipelineChart.destroyed) {
                window.pipelineChart.resize();
            }
        }, 250);
    });
});

// Re-initialize charts when page becomes visible (handles tab switching)
document.addEventListener("visibilitychange", function() {
    if (!document.hidden) {
        setTimeout(initializeCharts, 200);
    }
});

// Cleanup charts when page is unloaded
window.addEventListener("beforeunload", function() {
    if (window.salesChart) {
        window.salesChart.destroy();
        window.salesChart = null;
    }
    if (window.pipelineChart) {
        window.pipelineChart.destroy();
        window.pipelineChart = null;
    }
});
</script>

';

// Echo the content
echo $content;
?>

