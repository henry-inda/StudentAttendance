<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Edit Department</h2>
        <form action="<?php echo BASE_URL; ?>admin/departments/edit/<?php echo $data['department']->id; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Department Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['department']->name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Department Code</label>
                <input type="text" class="form-control" id="code" name="code" value="<?php echo $data['department']->code; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo $data['department']->description; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php echo ($data['department']->status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($data['department']->status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo BASE_URL; ?>admin/departments" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>