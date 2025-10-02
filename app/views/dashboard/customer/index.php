<?php
// app/views/dashboard/customer/index.php
// This file will be included by DashboardController
?>

<!-- Customer Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4><i class="fas fa-shopping-cart me-2"></i>Customer Dashboard</h4>
        <p class="text-muted">Your account overview and support</p>
    </div>
</div>

<!-- Customer Stats Cards -->
<div class="row dashboard-row">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>12</h3>
                    <p>Total Orders</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-check"></i> 10 completed
                        </span>
                        <span class="text-muted">This year</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>Rp. 3.2M</h3>
                    <p>Total Spent</p>
                    <div class="stats-change">
                        <span class="text-info">
                            <i class="fas fa-dollar-sign"></i> Lifetime
                        </span>
                        <span class="text-muted">Value</span>
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
                    <h3>2</h3>
                    <p>Support Tickets</p>
                    <div class="stats-change">
                        <span class="text-warning">
                            <i class="fas fa-ticket-alt"></i> Open
                        </span>
                        <span class="text-muted">Need attention</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-headset"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>Gold</h3>
                    <p>Membership Level</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-crown"></i> Premium
                        </span>
                        <span class="text-muted">Status</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-medal"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Content Row -->
<div class="row dashboard-row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD-001</td>
                                <td>2024-01-15</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>Rp. 450,000</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                            </tr>
                            <tr>
                                <td>#ORD-002</td>
                                <td>2024-01-20</td>
                                <td><span class="badge bg-warning">Processing</span></td>
                                <td>Rp. 750,000</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">Track</a></td>
                            </tr>
                            <tr>
                                <td>#ORD-003</td>
                                <td>2024-01-25</td>
                                <td><span class="badge bg-info">Shipped</span></td>
                                <td>Rp. 320,000</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">Track</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Place New Order
                    </a>
                    <a href="<?php echo APP_URL; ?>/call-center" class="btn btn-outline-success">
                        <i class="fab fa-whatsapp me-2"></i>Contact Support
                    </a>
                    <a href="<?php echo APP_URL; ?>/users/profile" class="btn btn-outline-primary">
                        <i class="fas fa-user me-2"></i>My Profile
                    </a>
                    <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-info">
                        <i class="fas fa-envelope me-2"></i>Messages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Charts -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Purchase History</h5>
            </div>
            <div class="card-body">
                <canvas id="purchaseHistoryChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Support Tickets</h5>
            </div>
            <div class="card-body">
                <canvas id="supportTicketsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Customer Support -->
<div class="row dashboard-row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Support & Contact</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3">
                            <i class="fab fa-whatsapp fa-2x text-success mb-2"></i>
                            <h6>WhatsApp Support</h6>
                            <p class="text-muted">24/7 customer support</p>
                            <a href="<?php echo APP_URL; ?>/call-center" class="btn btn-success btn-sm">Contact Now</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3">
                            <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                            <h6>Email Support</h6>
                            <p class="text-muted">Get help via email</p>
                            <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-primary btn-sm">Send Message</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3">
                            <i class="fas fa-phone fa-2x text-info mb-2"></i>
                            <h6>Phone Support</h6>
                            <p class="text-muted">Call us directly</p>
                            <a href="tel:+6281234567890" class="btn btn-info btn-sm">Call Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Purchase History Chart
    const purchaseCtx = document.getElementById('purchaseHistoryChart').getContext('2d');
    new Chart(purchaseCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Amount Spent (Rp)',
                data: [250000, 450000, 320000, 680000, 420000, 550000],
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

    // Support Tickets Chart
    const supportCtx = document.getElementById('supportTicketsChart').getContext('2d');
    new Chart(supportCtx, {
        type: 'doughnut',
        data: {
            labels: ['Resolved', 'In Progress', 'Pending'],
            datasets: [{
                data: [8, 2, 1],
                backgroundColor: [
                    '#28a745',
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
