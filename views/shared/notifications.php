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
        <h2>My Notifications</h2>
        <a href="<?php echo BASE_URL; ?>notifications/mark_all_read" class="btn btn-primary mb-3">Mark All as Read</a>
        <ul class="list-group">
            <?php foreach ($data['notifications'] as $notification): ?>
                <li class="list-group-item <?php echo $notification->is_read ? 'list-group-item-light' : 'list-group-item-info'; ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-bell me-2"></i>
                            <strong><?php echo $notification->title; ?></strong>
                            <p class="mb-0"><?php echo $notification->message; ?></p>
                            <small class="text-muted"><?php echo $notification->created_at; ?></small>
                        </div>
                        <?php if (!$notification->is_read): ?>
                            <a href="<?php echo BASE_URL; ?>notifications/mark_read/<?php echo $notification->id; ?>" class="btn btn-sm btn-outline-primary">Mark as Read</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>