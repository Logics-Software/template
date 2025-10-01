<?php
// Function to get greeting message based on time
function getGreetingMessage() {
    $hour = date('H');
    if ($hour >= 5 && $hour < 12) {
        return 'Selamat Pagi';
    } elseif ($hour >= 12 && $hour < 15) {
        return 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        return 'Selamat Sore';
    } else {
        return 'Selamat Malam';
    }
}

ob_start();
?>
<!-- Top Header -->
<div class="top-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <!-- Sidebar Toggle -->
                    <button class="btn btn-link me-3 sidebar-toggle-btn" id="sidebarToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    
                    <!-- Greeting Message -->
                    <div class="greeting-message">
                        <h6 class="mb-0 text-muted" id="greetingText">
                            <?php echo getGreetingMessage(); ?>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-end">
                    
                    <!-- Full Screen Toggle -->
                    <button class="btn btn-link" id="fullscreenToggle">
                        <i class="fa-solid fa-expand"></i>
                    </button>
                    
                    <!-- Theme Toggle -->
                    <button class="btn btn-link" id="themeToggle">
                        <i class="fa-solid fa-sun" id="themeIcon"></i>
                    </button>
                    
                    <!-- =================================================== -->
                    <!-- Notifications -->
                    <!-- =================================================== -->
                    <div class="notification-dropdown">
                        <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-bell"></i>
                            <span class="position-absolute badge rounded-pill bg-danger">
                                5
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-menu">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notifications</h6>
                                <button class="btn btn-sm btn-outline-primary">Clear All</button>
                            </div>
                            <div class="notification-list">
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="<?php echo APP_URL; ?>/assets/images/users/avatar.svg" alt="User" class="rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="avatar-fallback avatar-md" style="display:none; background:linear-gradient(135deg, #28a745 0%, #20c997 100%);">O</div>
                                        </div>
                                        <div class="notification-content">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0">Sudarmu</h6>
                                                <small class="text-muted">1 min ago</small>
                                            </div>
                                            <p class="mb-0 text-muted">Otorisasi Edit ACC Penjualan</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="<?php echo APP_URL; ?>/assets/images/users/avatar.svg" alt="User" class="rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="avatar-fallback avatar-md" style="display:none; background:linear-gradient(135deg, #28a745 0%, #20c997 100%);">O</div>
                                        </div>
                                        <div class="notification-content">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0">Ari Kusuma</h6>
                                                <small class="text-muted">1 min ago</small>
                                            </div>
                                            <p class="mb-0 text-muted">Otorisasi Edit Pembelian</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="<?php echo APP_URL; ?>/assets/images/users/avatar.svg" alt="User" class="rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="avatar-fallback avatar-md" style="display:none; background:linear-gradient(135deg, #fd7e14 0%, #e83e8c 100%);">T</div>
                                        </div>
                                        <div class="notification-content">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0">Eny Kurniawati</h6>
                                                <small class="text-muted">7 min ago</small>
                                            </div>
                                            <p class="mb-0 text-muted">Otorisasi/Approval Discount dan Harga Jualan Barang</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="<?php echo APP_URL; ?>/assets/images/users/avatar.svg" alt="User" class="rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="avatar-fallback avatar-md" style="display:none; background:linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">V</div>
                                        </div>
                                        <div class="notification-content">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0">Wiwin Lena</h6>
                                                <small class="text-muted">5 min ago</small>
                                            </div>
                                            <p class="mb-0 text-muted">Perubahan Harga Barang Amoxillyn 20mg 100 tablet</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="<?php echo APP_URL; ?>/assets/images/users/avatar.svg" alt="User" class="rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="avatar-fallback avatar-md" style="display:none; background:linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);">R</div>
                                        </div>
                                        <div class="notification-content">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0">Yulie Andriati</h6>
                                                <small class="text-muted">5 min ago</small>
                                            </div>
                                            <p class="mb-0 text-muted">Otorisasi Approval/ACC Piutang Customer</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-footer">
                                <a href="#" class="btn btn-outline-primary btn-sm w-100">View all</a>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================== -->

                    <!-- =================================================== -->
                    <!-- Messages -->
                    <!-- =================================================== -->
                    <div class="message-dropdown">
                        <button class="btn btn-link position-relative" data-bs-toggle="dropdown" id="messageToggle">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="position-absolute badge rounded-pill bg-danger" id="messageBadge" style="display: none;">
                                0
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end message-menu">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Pesan</h6>
                                <button class="btn btn-sm btn-outline-primary" id="markAllAsReadBtn">Tandai Sudah Dibaca</button>
                            </div>
                            <div class="message-list" id="messageList">
                                <div class="text-center p-3">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 mb-0 text-muted">Memuat pesan...</p>
                                </div>
                            </div>
                            <div class="dropdown-footer">
                                <a href="<?php echo APP_URL; ?>/messages" class="btn btn-outline-primary btn-sm w-100">Buka Semua Pesan</a>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================== -->

                    <!-- User Profile -->
                    <div class="user-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <?php if (Session::get('user_picture')): ?>
                                        <img src="<?php echo APP_URL; ?>/<?php echo Session::get('user_picture'); ?>" alt="User" class="rounded-circle" width="32" height="32">
                                    <?php else: ?>
                                        <div class="avatar-fallback avatar-sm" style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><?php echo strtoupper(substr(Session::get('user_name') ?? 'A', 0, 1)); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="user-info text-start">
                                    <h6><?php echo Session::get('user_name') ?? 'Admin'; ?></h6>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/messages">
                                    <i class="fas fa-envelope me-2"></i>Pesan
                                    <span class="badge bg-danger ms-auto" id="unread-count-badge" style="display: none;">0</span>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/profile">
                                    <i class="fas fa-user me-2"></i>My Account
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/change-password">
                                    <i class="fas fa-key me-2"></i>Change Password
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/lock-screen">
                                    <i class="fas fa-lock me-2"></i>Lock Screen
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$header = ob_get_clean();
?>
