<?php
$sidebar = '
<!-- Sidebar Navigation -->
<nav class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <a href="' . APP_URL . '">
                <img src="' . APP_URL . '/assets/images/logo.png" alt="Hando" height="32">
            </a>
        </div>
    </div>
    
    <div class="sidebar-body">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link ' . (($current_page ?? '') === 'dashboard' ? 'active' : '') . '" href="' . APP_URL . '/dashboard">
                    <i class="fa-regular fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Separator -->
            <li class="nav-item">
                <hr class="sidebar-divider">
            </li>
            
            <li class="nav-item">
                <a class="nav-link ' . (($current_page ?? '') === 'users' ? 'active' : '') . '" href="' . APP_URL . '/users">
                    <i class="fas fa-users"></i>
                    <span>Manajemen Users</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
';
?>
