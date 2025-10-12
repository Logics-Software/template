<?php
// Load call center data (with static caching)
if (!isset($callCenters)) {
    static $cachedCallCenters = null;
    
    if ($cachedCallCenters === null) {
        require_once APP_PATH . '/app/models/CallCenter.php';
        $callCenterModel = new CallCenter();
        $cachedCallCenters = $callCenterModel->getAll();
    }
    
    $callCenters = $cachedCallCenters;
}
?>

<!-- Footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="footer-left desktop-only">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> - Developed by <a href="https://www.logics-ti.com" target="_blank">Logics Software</a></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="footer-right">
                    <div class="d-flex align-items-center justify-content-end">
                        <!-- Mobile Navigation Icons (Hidden on Desktop) -->
                        <div class="mobile-nav-icons" style="display: none;">
                            <!-- Theme Toggle -->
                            <div class="mobile-icon-wrapper">
                                <button class="btn btn-link mobile-icon-btn" id="mobileThemeToggle">
                                    <i class="fas fa-sun" id="mobileThemeIcon"></i>
                                </button>
                                <span class="mobile-icon-label">Tema</span>
                            </div>
                            
                            <!-- Notifications -->
                            <div class="mobile-icon-wrapper">
                                <div class="notification-dropdown">
                                    <button class="btn btn-link position-relative mobile-icon-btn" id="mobileNotificationToggle">
                                        <i class="far fa-bell"></i>
                                        <span class="position-absolute badge rounded-pill bg-danger" id="mobileNotificationBadge" style="display: none;">0</span>
                                    </button>
                                </div>
                                <span class="mobile-icon-label">Notif</span>
                            </div>
                            
                            <!-- Messages -->
                            <div class="mobile-icon-wrapper">
                                <div class="message-dropdown">
                                    <button class="btn btn-link position-relative mobile-icon-btn" id="mobileMessageToggle">
                                        <i class="far fa-envelope"></i>
                                        <span class="position-absolute badge rounded-pill bg-danger" id="mobileMessageBadge" style="display: none;">0</span>
                                    </button>
                                </div>
                                <span class="mobile-icon-label">Pesan</span>
                            </div>
                        </div>
                        
                        <!-- Desktop Footer Links (Hidden on Mobile) -->
                        <div class="footer-links" style="position: relative;">
                            <i class="fab fa-whatsapp text-muted me-2" 
                               id="whatsappToggle" 
                               data-bs-toggle="modal" 
                               data-bs-target="#whatsappModal" 
                               style="cursor: pointer;" 
                               title="WhatsApp Contact"></i>
                            <i class="fas fa-question-circle text-muted me-2"></i>
                            <i class="fas fa-info-circle text-muted" id="appInfoIcon" style="cursor: pointer;" title="Application Info"></i>
                            
                            <!-- App Info Popover -->
                            <div id="appInfoPopover" class="app-info-popover" style="display: none;">
                                <div class="popover-header">
                                    <i class="fas fa-info-circle text-primary me-2"></i>Informasi Aplikasi
                                    <button type="button" class="popover-close" id="closeAppInfo">&times;</button>
                                </div>
                                <div class="popover-body">
                                    <table class="info-table">
                                        <tr>
                                            <td><i class="fas fa-cube text-primary me-2"></i>Nama Aplikasi</td>
                                            <td><?php echo APP_NAME; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-code-branch text-success me-2"></i>Versi</td>
                                            <td><?php echo APP_VERSION; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-globe text-info me-2"></i>Timezone</td>
                                            <td><?php echo APP_TIMEZONE; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-calendar-alt text-warning me-2"></i>Last Update</td>
                                            <td><?php echo date('d M Y, H:i', filemtime(APP_PATH . '/index.php')); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    position: relative;
    z-index: 200;
}

.app-info-popover {
    position: fixed;
    bottom: 50px;
    right: 10px;
    width: 350px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    z-index: 1100;
    animation: slideUpFade 0.2s ease;
}

@keyframes slideUpFade {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.app-info-popover::after {
    content: '';
    position: absolute;
    bottom: -10px;
    right: 20px;
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 10px solid var(--bg-primary);
}

.app-info-popover .popover-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    font-size: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-primary);
}

.app-info-popover .popover-close {
    background: none;
    border: none;
    font-size: 24px;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    line-height: 1;
    transition: color 0.2s;
}

.app-info-popover .popover-close:hover {
    color: var(--text-primary);
}

.app-info-popover .popover-body {
    padding: 12px 16px;
}

.app-info-popover .info-table {
    width: 100%;
    font-size: 13px;
}

.app-info-popover .info-table td {
    padding: 6px 0;
    color: var(--text-primary);
}

.app-info-popover .info-table td:first-child {
    font-weight: 600;
    width: 40%;
    white-space: nowrap;
}

.app-info-popover .info-table td:last-child {
    color: var(--text-secondary);
}
</style>

<script>
// App Info Popover Handler
document.addEventListener('DOMContentLoaded', function() {
    const appInfoIcon = document.getElementById('appInfoIcon');
    const appInfoPopover = document.getElementById('appInfoPopover');
    const closeAppInfo = document.getElementById('closeAppInfo');
    
    if (appInfoIcon && appInfoPopover) {
        // Toggle popover on icon click
        appInfoIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = appInfoPopover.style.display === 'block';
            appInfoPopover.style.display = isVisible ? 'none' : 'block';
        });
        
        // Close button
        if (closeAppInfo) {
            closeAppInfo.addEventListener('click', function(e) {
                e.stopPropagation();
                appInfoPopover.style.display = 'none';
            });
        }
        
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!appInfoPopover.contains(e.target) && e.target !== appInfoIcon) {
                appInfoPopover.style.display = 'none';
            }
        });
    }
});

// ============================================================================
// MOBILE RESPONSIVE JAVASCRIPT - Only for Mobile Devices
// ============================================================================
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on mobile
    function isMobile() {
        return window.innerWidth <= 768;
    }
    
    // ========================================
    // Mobile Hamburger Menu Toggle
    // ========================================
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.querySelector('.sidebar');
    let mobileOverlay = null;
    
    // Create mobile overlay
    function createMobileOverlay() {
        if (!mobileOverlay) {
            mobileOverlay = document.createElement('div');
            mobileOverlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1049; display: none;';
            mobileOverlay.id = 'mobileOverlay';
            document.body.appendChild(mobileOverlay);
            
            // Close sidebar when clicking overlay
            mobileOverlay.addEventListener('click', function() {
                if (isMobile() && sidebar) {
                    sidebar.classList.remove('active');
                    document.body.classList.remove('mobile-menu-open');
                    mobileOverlay.style.display = 'none';
                }
            });
        }
    }
    
    if (mobileMenuToggle && sidebar) {
        createMobileOverlay();
        
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (isMobile()) {
                const isActive = sidebar.classList.contains('active');
                
                if (isActive) {
                    // Close sidebar
                    sidebar.classList.remove('active');
                    document.body.classList.remove('mobile-menu-open');
                    if (mobileOverlay) mobileOverlay.style.display = 'none';
                } else {
                    // Open sidebar
                    sidebar.classList.add('active');
                    document.body.classList.add('mobile-menu-open');
                    if (mobileOverlay) mobileOverlay.style.display = 'block';
                }
            }
        });
        
        // Close sidebar on window resize if no longer mobile
        window.addEventListener('resize', function() {
            if (!isMobile() && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                document.body.classList.remove('mobile-menu-open');
                if (mobileOverlay) mobileOverlay.style.display = 'none';
            }
        });
    }
    
    // ========================================
    // Mobile Theme Toggle (Sync with Desktop)
    // ========================================
    const mobileThemeToggle = document.getElementById('mobileThemeToggle');
    const desktopThemeToggle = document.getElementById('themeToggle');
    const mobileThemeIcon = document.getElementById('mobileThemeIcon');
    const desktopThemeIcon = document.getElementById('themeIcon');
    
    if (mobileThemeToggle && desktopThemeToggle) {
        // Sync mobile icon with desktop on load
        if (desktopThemeIcon && mobileThemeIcon) {
            mobileThemeIcon.className = desktopThemeIcon.className;
        }
        
        // Mobile theme toggle click
        mobileThemeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            // Trigger desktop theme toggle
            desktopThemeToggle.click();
            
            // Sync icon
            setTimeout(function() {
                if (desktopThemeIcon && mobileThemeIcon) {
                    mobileThemeIcon.className = desktopThemeIcon.className;
                }
            }, 100);
        });
        
        // Watch for theme changes from desktop and sync to mobile
        if (desktopThemeIcon) {
            const observer = new MutationObserver(function() {
                if (mobileThemeIcon) {
                    mobileThemeIcon.className = desktopThemeIcon.className;
                }
            });
            observer.observe(desktopThemeIcon, { attributes: true, attributeFilter: ['class'] });
        }
    }
    
    // ========================================
    // Mobile Notification Toggle (Sync with Desktop)
    // ========================================
    const mobileNotificationToggle = document.getElementById('mobileNotificationToggle');
    const desktopNotificationToggle = document.getElementById('notificationToggle');
    const mobileNotificationBadge = document.getElementById('mobileNotificationBadge');
    const desktopNotificationBadge = document.getElementById('notificationBadge');
    
    if (mobileNotificationToggle && desktopNotificationToggle) {
        // Sync mobile badge with desktop on load
        if (desktopNotificationBadge && mobileNotificationBadge) {
            mobileNotificationBadge.textContent = desktopNotificationBadge.textContent;
            mobileNotificationBadge.style.display = desktopNotificationBadge.style.display;
        }
        
        // Mobile notification toggle click
        mobileNotificationToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Trigger desktop notification toggle
            desktopNotificationToggle.click();
        });
        
        // Watch for badge changes from desktop and sync to mobile
        if (desktopNotificationBadge) {
            const observer = new MutationObserver(function() {
                if (mobileNotificationBadge) {
                    mobileNotificationBadge.textContent = desktopNotificationBadge.textContent;
                    mobileNotificationBadge.style.display = desktopNotificationBadge.style.display;
                }
            });
            observer.observe(desktopNotificationBadge, { 
                childList: true, 
                attributes: true, 
                attributeFilter: ['style'] 
            });
        }
    }
    
    // ========================================
    // Mobile Message Toggle (Sync with Desktop)
    // ========================================
    const mobileMessageToggle = document.getElementById('mobileMessageToggle');
    const desktopMessageToggle = document.getElementById('messageToggle');
    const mobileMessageBadge = document.getElementById('mobileMessageBadge');
    const desktopMessageBadge = document.getElementById('messageBadge');
    
    if (mobileMessageToggle && desktopMessageToggle) {
        // Sync mobile badge with desktop on load
        if (desktopMessageBadge && mobileMessageBadge) {
            mobileMessageBadge.textContent = desktopMessageBadge.textContent;
            mobileMessageBadge.style.display = desktopMessageBadge.style.display;
        }
        
        // Mobile message toggle click
        mobileMessageToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Trigger desktop message toggle
            desktopMessageToggle.click();
        });
        
        // Watch for badge changes from desktop and sync to mobile
        if (desktopMessageBadge) {
            const observer = new MutationObserver(function() {
                if (mobileMessageBadge) {
                    mobileMessageBadge.textContent = desktopMessageBadge.textContent;
                    mobileMessageBadge.style.display = desktopMessageBadge.style.display;
                }
            });
            observer.observe(desktopMessageBadge, { 
                childList: true, 
                attributes: true, 
                attributeFilter: ['style'] 
            });
        }
    }
});
// ============================================================================
// END OF MOBILE RESPONSIVE JAVASCRIPT
// ============================================================================
</script>

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