<!-- Top Header -->
<div class="top-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <!-- Tablet & Mobile Hamburger Menu -->
                    <button class="btn btn-link mobile-hamburger-btn" 
                            id="mobileMenuToggle" 
                            style="display: none;"
                            aria-label="Toggle Menu">
                        <i class="fas fa-bars" aria-hidden="true"></i>
                    </button>
                    
                    <!-- Desktop Sidebar Toggle (Hidden on Mobile/Tablet) -->
                    <button class="btn btn-link sidebar-toggle-btn desktop-only" 
                            id="sidebarToggle" 
                            aria-label="Toggle Sidebar">
                        <i class="fas fa-bars" aria-hidden="true"></i>
                    </button>
                    
                    <!-- Mobile Search Toggle Icon -->
                    <button class="btn btn-link mobile-search-toggle" 
                            id="mobileSearchToggle" 
                            aria-label="Toggle Search">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                    
                    <!-- Quick Search Menu (Desktop Only) -->
                    <div class="menu-search-container desktop-only">
                        <div class="menu-search-wrapper">
                            <i class="fas fa-search menu-search-icon"></i>
                            <input type="text" 
                                   class="menu-search-input" 
                                   id="menuSearchInput" 
                                   placeholder="Cari menu / modul..." 
                                   autocomplete="off"
                                   aria-label="Search menu">
                            <button class="menu-search-clear" id="menuSearchClear" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="menu-search-results" id="menuSearchResults" style="display: none;">
                            <div class="menu-search-empty">
                                <i class="fas fa-search"></i>
                                <p>Ketik untuk mencari menu...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-end">
                    
                    <!-- Full Screen Toggle (Hidden on Mobile) -->
                    <div class="notification-dropdown">
                        <button class="btn btn-link tema-toggle-btn" id="fullscreenToggle">
                            <i class="fas fa-expand"></i>
                        </button>
                        
                        <!-- Theme Toggle -->
                        <button class="btn btn-link tema-toggle-btn" id="themeToggle">
                            <i class="fas fa-sun" id="themeIcon"></i>
                        </button>
                    </div>
                    
                    <!-- =================================================== -->
                    <!-- Notifications -->
                    <!-- =================================================== -->
                    <div class="notification-dropdown">
                        <button class="btn btn-link position-relative" id="notificationToggle" 
                                aria-label="Notifications" aria-expanded="false" aria-haspopup="true">
                            <i class="far fa-bell"></i>
                            <span class="position-absolute badge rounded-pill bg-danger" id="notificationBadge">
                                5
                            </span>
                        </button>
                        <div class="notification-menu">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notifications</h6>
                                <a href="#" class="text-secondary" id="clearAllNotificationsBtn">Clear All</a>
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
                            <div class="dropdown-footer text-center">
                                <a href="#" class="text-secondary">Tampilkan semua notifikasi</a>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================== -->

                    <!-- =================================================== -->
                    <!-- Messages -->
                    <!-- =================================================== -->
                    <div class="message-dropdown">
                        <button class="btn btn-link position-relative" id="messageToggle">
                            <i class="far fa-envelope"></i>
                            <span class="position-absolute badge rounded-pill bg-danger" id="messageBadge" style="display: none;">
                                0
                            </span>
                        </button>
                        <div class="message-menu">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Pesan</h6>
                                <a href="#" class="text-secondary" id="markAllAsReadBtn">Tandai Sudah Dibaca</a>
                            </div>
                            <div class="message-list" id="messageList">
                                <div class="text-center p-3">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 mb-0 text-muted">Memuat pesan...</p>
                                </div>
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="<?php echo APP_URL; ?>/messages" class="text-secondary">Buka Semua Pesan</a>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================== -->

                    <!-- User Profile -->
                    <div class="user-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle d-flex align-items-center" id="userProfileToggle" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <?php 
                                    $userPicture = Session::get('user_picture');
                                    $avatarUrl = $userPicture ? APP_URL . '/' . $userPicture : APP_URL . '/assets/images/users/avatar.svg';
                                    ?>
                                    <img src="<?php echo $avatarUrl; ?>" 
                                         alt="" 
                                         class="rounded-circle object-fit-cover user-avatar-img" 
                                         width="32" 
                                         height="32">
                                </div>
                                <div class="user-info text-start">
                                    <?php echo Session::get('user_name') ?? 'Admin'; ?>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile">
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

<!-- Mobile Search Overlay -->
<div class="mobile-search-overlay" id="mobileSearchOverlay">
    <div class="mobile-search-wrapper">
        <i class="fas fa-search menu-search-icon"></i>
        <input type="text" 
               class="menu-search-input" 
               id="mobileMenuSearchInput" 
               placeholder="Cari menu / modul..." 
               autocomplete="off"
               aria-label="Search menu">
        <button class="menu-search-clear" id="mobileMenuSearchClear" style="display: none;">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="menu-search-results" id="mobileMenuSearchResults" style="display: none;">
        <div class="menu-search-empty">
            <i class="fas fa-search"></i>
            <p>Ketik untuk mencari menu...</p>
        </div>
    </div>
</div>
