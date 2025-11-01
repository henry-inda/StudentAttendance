<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: rgb(27, 38, 44);">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" id="sidebar-toggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>"><i class="fas fa-graduation-cap"></i> Student Attendance</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <?php if ($data['unread_notification_count'] > 0): ?>
                            <span class="badge bg-danger"><?php echo $data['unread_notification_count']; ?></span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <?php if (!empty($data['recent_notifications'])): ?>
                            <?php foreach ($data['recent_notifications'] as $notification): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>notifications/view/<?php echo $notification->id; ?>"><?php echo $notification->title; ?></a></li>
                            <?php endforeach; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>notifications">View all</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="#">No new notifications</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
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