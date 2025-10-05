<?php
// Load call center data
require_once APP_PATH . '/app/models/CallCenter.php';
$callCenterModel = new CallCenter();
$callCenters = $callCenterModel->getAll();

// Function to get greeting message based on time
if (!function_exists('getGreetingMessage')) {
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
}
?>

<!-- Top Header -->
<div class="top-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <!-- Sidebar Toggle -->
                    <button class="btn btn-link sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    
                    <!-- WhatsApp Contact -->
                    <button class="btn btn-link" id="whatsappToggle" data-bs-toggle="modal" data-bs-target="#whatsappModal" title="Hubungi via WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    
                    <!-- Greeting Message -->
                    <div class="greeting-message">
                        <h6 class="mb-0 ml-2 text-muted" id="greetingText">
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
                        <button class="btn btn-link position-relative" id="notificationToggle" data-bs-toggle="dropdown">
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
                                            <div class="avatar-fallback avatar-md d-none bg-gradient-success">O</div>
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
                                            <div class="avatar-fallback avatar-md d-none bg-gradient-success">O</div>
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
                                            <div class="avatar-fallback avatar-md d-none bg-gradient-warning">T</div>
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
                                            <div class="avatar-fallback avatar-md d-none bg-gradient-info">V</div>
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
                                            <div class="avatar-fallback avatar-md d-none bg-gradient-danger">R</div>
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
                        <button class="btn btn-link position-relative" data-bs-toggle="dropdown" id="messageToggle" title="Pesan">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="position-absolute badge rounded-pill bg-danger d-none" id="messageBadge">
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
                            <button class="btn btn-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" title="Profil User">
                                <div class="user-avatar me-2">
                                    <?php if (Session::get('user_picture')): ?>
                                        <img src="<?php echo APP_URL; ?>/<?php echo Session::get('user_picture'); ?>" alt="User" class="rounded-circle" width="32" height="32">
                                    <?php else: ?>
                                        <div class="avatar-fallback avatar-sm bg-gradient-primary"><?php echo strtoupper(substr(Session::get('user_name') ?? 'A', 0, 1)); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="user-info text-start">
                                    <h6><?php echo Session::get('user_name') ?? 'Admin'; ?></h6>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/messages">
                                    <i class="fas fa-envelope me-2"></i>Pesan
                                    <span class="badge bg-danger ms-auto d-none" id="unread-count-badge">0</span>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/profile">
                                    <i class="fas fa-user me-2"></i>Akun Saya
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/change-password">
                                    <i class="fas fa-key me-2"></i>Ganti Password
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

<!-- WhatsApp Contact Modal -->
<div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title me-3" id="whatsappModalLabel">
                    <i class="fab fa-whatsapp text-success me-2"></i>Hubungi Kami via WhatsApp
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (empty($callCenters)): ?>
                    <div class="text-center py-5">
                        <i class="fab fa-whatsapp fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No call center entries found</h5>
                        <p class="text-muted">Please add call center entries from the settings menu.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($callCenters as $index => $callCenter): ?>
                            <div class="col-md-6">
                                <div class="card h-100 whatsapp-contact-card" 
                                     data-whatsapp="<?php echo preg_replace('/[^0-9]/', '', $callCenter['nomorwa']); ?>" 
                                     data-name="<?php echo htmlspecialchars($callCenter['judul']); ?>">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fab fa-whatsapp fa-3x text-success"></i>
                                        </div>
                                        <h6 class="card-title"><?php echo htmlspecialchars($callCenter['judul']); ?></h6>
                                        <p class="card-text text-muted small"><?php echo htmlspecialchars($callCenter['nomorwa']); ?></p>
                                        <?php if (!empty($callCenter['deskripsi'])): ?>
                                            <p class="card-text small"><?php echo htmlspecialchars(substr($callCenter['deskripsi'], 0, 60)); ?><?php echo strlen($callCenter['deskripsi']) > 60 ? '...' : ''; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
.whatsapp-contact-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.whatsapp-contact-card:hover {
    border-color: #25d366;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.2);
    transform: translateY(-2px);
}

.whatsapp-contact-card:hover .fab {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

/* Dark theme support for WhatsApp modal */
[data-bs-theme="dark"] .whatsapp-contact-card {
    background: var(--bg-primary);
    border-color: var(--border-dark);
}

[data-bs-theme="dark"] .whatsapp-contact-card:hover {
    border-color: #25d366;
    background: var(--bg-secondary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // WhatsApp contact click handler
    document.querySelectorAll('.whatsapp-contact-card').forEach(card => {
        card.addEventListener('click', function() {
            const phoneNumber = this.getAttribute('data-whatsapp');
            const contactName = this.getAttribute('data-name');
            
            // Create WhatsApp URL
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=Halo ${contactName}, saya ingin bertanya tentang...`;
            
            // Open WhatsApp in new tab
            window.open(whatsappUrl, '_blank');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('whatsappModal'));
            modal.hide();
        });
    });
});
</script>
