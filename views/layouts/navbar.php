<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: rgb(27, 38, 44);">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" id="sidebar-toggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
            // Default brand link
            $brandHref = BASE_URL;
            // If user is logged in, point brand to their dashboard based on role
            if (get_session('user_id')) {
                $role = get_session('user_role');
                if ($role === 'admin') {
                    $brandHref = BASE_URL . 'admin/dashboard';
                } elseif ($role === 'lecturer') {
                    $brandHref = BASE_URL . 'lecturer/dashboard';
                } elseif ($role === 'student') {
                    $brandHref = BASE_URL . 'student/dashboard';
                }
            }
        ?>
        <a class="navbar-brand" href="<?php echo $brandHref; ?>"><i class="fas fa-graduation-cap"></i> Student Attendance</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger" id="notification-badge" style="display: none;"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notification-menu">
                        <li><a class="dropdown-item" href="#">No new notifications</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> <?php echo get_session('user_name'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile">Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile/change_password">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Toast container for real-time notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1500;"></div>