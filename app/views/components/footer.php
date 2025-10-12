<!-- Footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="footer-left">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> - Developed by <a href="https://www.logics-ti.com" target="_blank">Logics Software</a></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="footer-right">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="footer-links" style="position: relative;">
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
</script>