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
        <h2>Change Password</h2>
        <form action="<?php echo BASE_URL; ?>profile/change_password" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
            <a href="<?php echo BASE_URL; ?>profile" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>