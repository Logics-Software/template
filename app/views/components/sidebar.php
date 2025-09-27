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
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#menuCollapse" aria-expanded="false">
                    <i class="fas fa-th-large"></i>
                    <span>Menu</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="menuCollapse">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link ' . (($current_page ?? '') === 'crm' ? 'active' : '') . '" href="' . APP_URL . '/crm">
                                <i class="fas fa-users"></i>
                                <span>CRM</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ' . (($current_page ?? '') === 'analytics' ? 'active' : '') . '" href="' . APP_URL . '/analytics">
                                <i class="fa-regular fa-chart-line"></i>
                                <span>Analytics</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ' . (($current_page ?? '') === 'ecommerce' ? 'active' : '') . '" href="' . APP_URL . '/ecommerce">
                                <i class="fas fa-shopping-cart"></i>
                                <span>eCommerce</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ' . (($current_page ?? '') === 'projects' ? 'active' : '') . '" href="' . APP_URL . '/projects">
                                <i class="fas fa-folder"></i>
                                <span>Projects</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ' . (($current_page ?? '') === 'hrm' ? 'active' : '') . '" href="' . APP_URL . '/hrm">
                                <i class="fas fa-id-card"></i>
                                <span>HRM</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ' . (($current_page ?? '') === 'jobs' ? 'active' : '') . '" href="' . APP_URL . '/jobs">
                                <i class="fas fa-briefcase"></i>
                                <span>Jobs</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="' . APP_URL . '/settings">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapse" aria-expanded="false">
                    <i class="fas fa-file-alt"></i>
                    <span>Pages</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="pagesCollapse">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#authCollapse" aria-expanded="false">
                                <i class="fas fa-shield-alt"></i>
                                <span>Authentication</span>
                                <i class="fas fa-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse" id="authCollapse">
                                <ul class="nav nav-pills flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link" href="' . APP_URL . '/login">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span>Log In</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="' . APP_URL . '/register">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Register</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="' . APP_URL . '/recover-password">
                                            <i class="fas fa-key"></i>
                                            <span>Recover Password</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="' . APP_URL . '/lock-screen">
                                            <i class="fas fa-lock"></i>
                                            <span>Lock Screen</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#errorCollapse" aria-expanded="false">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Error Pages</span>
                                <i class="fas fa-chevron-down ms-auto"></i>
                            </a>
                            <div class="collapse" id="errorCollapse">
                                <ul class="nav nav-pills flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link" href="' . APP_URL . '/error-404">
                                            <i class="fas fa-square"></i>
                                            <span>Error 404</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="' . APP_URL . '/error-500">
                                            <i class="fas fa-square"></i>
                                            <span>Error 500</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#appsCollapse" aria-expanded="false">
                    <i class="fa-solid fa-grip"></i>
                    <span>Apps</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="appsCollapse">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/todo">
                                <i class="fas fa-square-check"></i>
                                <span>Todo List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/contacts">
                                <i class="fas fa-address-book"></i>
                                <span>Contacts</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/calendar">
                                <i class="fas fa-calendar-days"></i>
                                <span>Calendar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#componentsCollapse" aria-expanded="false">
                    <i class="fa-solid fa-computer"></i>
                    <span>Components</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="componentsCollapse">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/components/buttons">
                                <i class="fas fa-circle"></i>
                                <span>Buttons</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/components/cards">
                                <i class="fas fa-square"></i>
                                <span>Cards</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/components/forms">
                                <i class="fas fa-keyboard"></i>
                                <span>Forms</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#chartsCollapse" aria-expanded="false">
                    <i class="fas fa-bar-chart"></i>
                    <span>Apex Charts</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="chartsCollapse">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/charts/line">
                                <i class="fas fa-chart-line"></i>
                                <span>Line</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/charts/bar">
                                <i class="fas fa-chart-column"></i>
                                <span>Bar</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="' . APP_URL . '/charts/pie">
                                <i class="fas fa-chart-pie"></i>
                                <span>Pie</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
';
?>
