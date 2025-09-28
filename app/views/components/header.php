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

$header = '
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
                            ' . getGreetingMessage() . ', ' . (Session::get('user_name') ?? 'Admin') . '
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-end">
                    
                    <!-- Full Screen Toggle -->
                    <button class="btn btn-link me-3" id="fullscreenToggle">
                        <i class="fa-solid fa-expand"></i>
                    </button>
                    
                    <!-- Theme Toggle -->
                    <button class="btn btn-link me-3" id="themeToggle">
                        <i class="fa-solid fa-sun" id="themeIcon"></i>
                    </button>
                    
                    <!-- Notifications -->
                    <div class="notification-dropdown me-3">
                        <button class="btn btn-link position-relative" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-bell"></i>
                            <span class="position-absolute badge rounded-pill bg-danger">
                                9
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
                                            <img src="' . APP_URL . '/assets/images/users/user-1.jpg" alt="User" class="rounded-circle" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                            <div class="avatar-fallback" style="display:none; width:40px; height:40px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:50%; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:14px;">C</div>
                                        </div>
                                        <div class="notification-content">
                                            <h6 class="mb-1">Carl Steadham</h6>
                                            <p class="mb-0 text-muted">Completed Improve workflow in Figma</p>
                                            <small class="text-muted">5 min ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="' . APP_URL . '/assets/images/users/user-2.jpg" alt="User" class="rounded-circle" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                            <div class="avatar-fallback" style="display:none; width:40px; height:40px; background:linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius:50%; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:14px;">O</div>
                                        </div>
                                        <div class="notification-content">
                                            <h6 class="mb-1">Olivia McGuire</h6>
                                            <p class="mb-0 text-muted">dark-themes.zip 2.4 MB</p>
                                            <small class="text-muted">1 min ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="' . APP_URL . '/assets/images/users/user-3.jpg" alt="User" class="rounded-circle" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                            <div class="avatar-fallback" style="display:none; width:40px; height:40px; background:linear-gradient(135deg, #fd7e14 0%, #e83e8c 100%); border-radius:50%; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:14px;">T</div>
                                        </div>
                                        <div class="notification-content">
                                            <h6 class="mb-1">Travis Williams</h6>
                                            <p class="mb-0 text-muted">@Patryk Please make sure that you\'re....</p>
                                            <small class="text-muted">7 min ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="' . APP_URL . '/assets/images/users/user-1.jpg" alt="User" class="rounded-circle" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                            <div class="avatar-fallback" style="display:none; width:40px; height:40px; background:linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); border-radius:50%; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:14px;">V</div>
                                        </div>
                                        <div class="notification-content">
                                            <h6 class="mb-1">Violette Lasky</h6>
                                            <p class="mb-0 text-muted">Completed Create new components</p>
                                            <small class="text-muted">5 min ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="' . APP_URL . '/assets/images/users/user-2.jpg" alt="User" class="rounded-circle" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                            <div class="avatar-fallback" style="display:none; width:40px; height:40px; background:linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); border-radius:50%; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:14px;">R</div>
                                        </div>
                                        <div class="notification-content">
                                            <h6 class="mb-1">Ralph Edwards</h6>
                                            <p class="mb-0 text-muted">Completed Improve workflow in React</p>
                                            <small class="text-muted">5 min ago</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-item">
                                    <div class="d-flex">
                                        <div class="notification-avatar">
                                            <img src="' . APP_URL . '/assets/images/users/user-3.jpg" alt="User" class="rounded-circle" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                            <div class="avatar-fallback" style="display:none; width:40px; height:40px; background:linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); border-radius:50%; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:14px;">J</div>
                                        </div>
                                        <div class="notification-content">
                                            <h6 class="mb-1">Jocab jones</h6>
                                            <p class="mb-0 text-muted">@Patryk Please make sure that you\'re....</p>
                                            <small class="text-muted">7 min ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-footer">
                                <a href="#" class="btn btn-outline-primary btn-sm w-100">View all</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Profile -->
                    <div class="user-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    ' . (Session::get('user_avatar') ? 
                                        '<img src="' . APP_URL . '/assets/images/users/' . Session::get('user_avatar') . '" alt="User" class="rounded-circle" width="32" height="32" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">' .
                                        '<div class="avatar-fallback" style="display:none; width:32px; height:32px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:16px;">' . strtoupper(substr(Session::get('user_name') ?? 'A', 0, 1)) . '</div>' :
                                        '<div class="avatar-fallback" style="width:32px; height:32px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:13px;">' . strtoupper(substr(Session::get('user_name') ?? 'A', 0, 1)) . '</div>'
                                    ) . '
                                </div>
                                <div class="user-info text-start" >
                                    <h6>' . (Session::get('user_name') ?? 'Admin') . '</h6>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="' . APP_URL . '/profile">
                                    <i class="fas fa-user me-2"></i>My Account
                                </a></li>
                                <li><a class="dropdown-item" href="' . APP_URL . '/settings">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a></li>
                                <li><a class="dropdown-item" href="' . APP_URL . '/lock-screen">
                                    <i class="fas fa-lock me-2"></i>Lock Screen
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="' . APP_URL . '/logout">
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
';
?>
