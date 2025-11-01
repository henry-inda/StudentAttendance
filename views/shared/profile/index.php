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
        <h2>My Profile</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Profile Information</h5>
                <p><strong>Full Name:</strong> <?php echo $data['user']->full_name; ?></p>
                <p><strong>Email:</strong> <?php echo $data['user']->email; ?></p>
                <p><strong>Role:</strong> <?php echo $data['user']->role; ?></p>
                <p><strong>Phone:</strong> <?php echo $data['user']->phone; ?></p>
                <p><strong>Profile Picture:</strong> 
                    <?php if ($data['user']->profile_picture): ?>
                        <img src="<?php echo BASE_URL . UPLOADS_PATH . $data['user']->profile_picture; ?>" alt="Profile Picture" width="100">
                    <?php else: ?>
                        No picture uploaded.
                    <?php endif; ?>
                </p>
                <a href="<?php echo BASE_URL; ?>profile/edit" class="btn btn-primary">Edit Profile</a>
                <a href="<?php echo BASE_URL; ?>profile/change_password" class="btn btn-secondary">Change Password</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>