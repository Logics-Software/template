<?php
// app/views/dashboard/user/index.php
// This file will be included by DashboardController
?>

<!-- User Dashboard Header -->
<div class="page-header">
    <div class="page-title">
        <h4><i class="fas fa-home me-2"></i>My Dashboard</h4>
        <p class="text-muted">Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</p>
    </div>
</div>

<!-- User Stats Cards -->
<div class="row dashboard-row">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>12</h3>
                    <p>My Tasks</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-check"></i> 8 completed
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

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>5</h3>
                    <p>New Messages</p>
                    <div class="stats-change">
                        <span class="text-info">
                            <i class="fas fa-envelope"></i> Unread
                        </span>
                        <span class="text-muted">Since last login</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>3</h3>
                    <p>Notifications</p>
                    <div class="stats-change">
                        <span class="text-warning">
                            <i class="fas fa-bell"></i> New
                        </span>
                        <span class="text-muted">Require attention</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="stats-info">
                    <h3>85%</h3>
                    <p>Profile Complete</p>
                    <div class="stats-change">
                        <span class="text-success">
                            <i class="fas fa-user-check"></i> Good
                        </span>
                        <span class="text-muted">Last updated</span>
                    </div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Content Row -->
<div class="row dashboard-row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">My Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="activity-item">
                        <div class="activity-icon bg-primary">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Message received</h6>
                            <p class="text-muted">New message from John Smith</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Task completed</h6>
                            <p class="text-muted">Updated project documentation</p>
                            <small class="text-muted">4 hours ago</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Profile updated</h6>
                            <p class="text-muted">Changed profile picture</p>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
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
                    <a href="<?php echo APP_URL; ?>/messages/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Message
                    </a>
                    <a href="<?php echo APP_URL; ?>/users/profile" class="btn btn-outline-primary">
                        <i class="fas fa-user me-2"></i>My Profile
                    </a>
                    <a href="<?php echo APP_URL; ?>/users/change-password" class="btn btn-outline-secondary">
                        <i class="fas fa-key me-2"></i>Change Password
                    </a>
                    <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-info">
                        <i class="fas fa-envelope me-2"></i>View Messages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Productivity Chart -->
<div class="row dashboard-row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">My Productivity</h5>
            </div>
            <div class="card-body">
                <canvas id="productivityChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Task Status</h5>
            </div>
            <div class="card-body">
                <canvas id="taskStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Productivity Chart
    const productivityCtx = document.getElementById('productivityChart').getContext('2d');
    new Chart(productivityCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Tasks Completed',
                data: [3, 5, 4, 6, 7, 2, 1],
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

    // Task Status Chart
    const taskCtx = document.getElementById('taskStatusChart').getContext('2d');
    new Chart(taskCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'In Progress', 'Pending'],
            datasets: [{
                data: [8, 3, 1],
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
