<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>System Settings</h2>
        <form action="<?php echo BASE_URL; ?>admin/settings/update" method="POST">
            <div class="card">
                <div class="card-header">System Information</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="system_name" class="form-label">System Name</label>
                        <input type="text" class="form-control" id="system_name" name="system_name" value="<?php echo $data['settings']['system_name']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="system_logo" class="form-label">System Logo</label>
                        <input type="file" class="form-control" id="system_logo" name="system_logo">
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Attendance Settings</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="attendance_threshold" class="form-label">Attendance Threshold (%)</label>
                        <input type="number" class="form-control" id="attendance_threshold" name="attendance_threshold" value="<?php echo $data['settings']['attendance_threshold']; ?>">
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Email Configuration</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="app_email" class="form-label">App Email</label>
                        <input type="email" class="form-control" id="app_email" name="app_email" value="<?php echo $data['settings']['app_email']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="app_password" class="form-label">App Password</label>
                        <input type="password" class="form-control" id="app_password" name="app_password" value="<?php echo $data['settings']['app_password']; ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>