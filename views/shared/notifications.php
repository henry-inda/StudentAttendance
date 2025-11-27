<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php 
    if (get_session('user_role') == 'admin') {
        require_once 'views/layouts/sidebar_admin.php';
    } elseif (get_session('user_role') == 'lecturer') {
        require_once 'views/layouts/sidebar_lecturer.php';
    } else {
        require_once 'views/layouts/sidebar_student.php';
    }
?>

<div class="content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="fas fa-bell me-2"></i>My Notifications</h2>
                    <?php if (!empty($data['notifications'])): ?>
                        <a href="<?php echo BASE_URL; ?>notifications/mark_all_read" 
                           class="btn btn-outline-primary"
                           onclick="return confirm('Mark all notifications as read?')">
                            <i class="fas fa-check-double me-1"></i> Mark All as Read
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (empty($data['notifications'])): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No notifications to display
            </div>
        <?php else: ?>
            <div class="notifications-list">
                <?php foreach ($data['notifications'] as $notification): ?>
                    <?php
                        // ... (icon and time formatting logic remains the same)
                        $icon = 'bell';
                        $bg_color = 'bg-light';
                        switch ($notification->type) {
                            case 'excuse_response':
                                $icon = 'clipboard-check';
                                $bg_color = 'bg-success bg-opacity-10';
                                break;
                            case 'excuse_request':
                                $icon = 'clipboard-question';
                                $bg_color = 'bg-info bg-opacity-10';
                                break;
                            case 'upcoming_class':
                                $icon = 'calendar-check';
                                $bg_color = 'bg-primary bg-opacity-10';
                                break;
                            case 'venue_change':
                                $icon = 'map-marker-alt';
                                $bg_color = 'bg-warning bg-opacity-10';
                                break;
                            case 'marked_absent':
                                $icon = 'user-times';
                                $bg_color = 'bg-danger bg-opacity-10';
                                break;
                            case 'class_cancelled':
                                $icon = 'calendar-xmark';
                                $bg_color = 'bg-danger bg-opacity-10';
                                break;
                        }

                        // Format date
                        $date = new DateTime($notification->created_at);
                        $now = new DateTime();
                        $diff = $now->diff($date);
                        
                        if ($diff->d == 0) {
                            if ($diff->h == 0) {
                                $time = $diff->i . ' minutes ago';
                            } else {
                                $time = $diff->h . ' hours ago';
                            }
                        } elseif ($diff->d == 1) {
                            $time = 'Yesterday at ' . $date->format('g:i A');
                        } else {
                            $time = $date->format('M j, Y g:i A');
                        }

                        // Generate action link based on notification type
                        $action_link = '';
                        if ($notification->related_id) {
                            switch ($notification->type) {
                                case 'excuse_response':
                                case 'excuse_request':
                                    $action_link = BASE_URL . 'student/excuseRequests/view/' . $notification->related_id;
                                    break;
                                case 'upcoming_class':
                                case 'venue_change':
                                case 'class_cancelled':
                                    $action_link = BASE_URL . 'student/schedule/view/' . $notification->related_id;
                                    break;
                                case 'marked_absent':
                                    $action_link = BASE_URL . 'student/attendance/view/' . $notification->related_id;
                                    break;
                                case 'new_request':
                                    $action_link = BASE_URL . 'admin/requests/show/' . $notification->id . '/' . $notification->related_id;
                                    break;
                            }
                        }
                    ?>
                    
                    <<?php echo !empty($action_link) ? 'a href="' . $action_link . '"' : 'div'; ?> class="card mb-3 notification-card <?php echo $notification->is_read ? 'border-0' : 'border-start border-4 border-primary'; ?> <?php echo $bg_color; ?> <?php echo !empty($action_link) ? 'text-decoration-none' : ''; ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon me-3">
                                        <i class="fas fa-<?php echo $icon; ?> fa-lg <?php echo $notification->is_read ? 'text-muted' : 'text-primary'; ?>"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 <?php echo $notification->is_read ? 'text-muted' : 'text-dark'; ?>">
                                            <?php echo htmlspecialchars($notification->title); ?>
                                        </h6>
                                        <p class="mb-1 text-secondary">
                                            <?php echo htmlspecialchars($notification->message); ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i><?php echo $time; ?>
                                        </small>
                                    </div>
                                </div>
                                <?php if (!$notification->is_read): ?>
                                    <div class="ms-2">
                                        <a href="<?php echo BASE_URL; ?>notifications/mark_read/<?php echo $notification->id; ?>" 
                                           class="btn btn-sm btn-link text-primary text-decoration-none"
                                           title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </<?php echo !empty($action_link) ? 'a' : 'div'; ?>>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php require_once 'views/layouts/footer.php'; ?>