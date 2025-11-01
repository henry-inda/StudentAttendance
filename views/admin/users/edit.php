<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Edit User</h2>
        <form action="<?php echo BASE_URL; ?>admin/users/edit/<?php echo $data['user']->id; ?>" method="POST">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $data['user']->full_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $data['user']->email; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="student" <?php echo ($data['user']->role == 'student') ? 'selected' : ''; ?>>Student</option>
                    <option value="lecturer" <?php echo ($data['user']->role == 'lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                    <option value="admin" <?php echo ($data['user']->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <select class="form-select" id="department" name="department_id">
                    <!-- Departments will be loaded dynamically -->
                    <option value="1" <?php echo ($data['user']->department_id == 1) ? 'selected' : ''; ?>>Computer Science</option>
                    <option value="2" <?php echo ($data['user']->department_id == 2) ? 'selected' : ''; ?>>Business IT</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $data['user']->phone; ?>">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php echo ($data['user']->status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($data['user']->status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>