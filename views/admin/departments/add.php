<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Add New Department</h2>
        <form action="<?php echo BASE_URL; ?>admin/departments/add" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Department Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Department Code</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?php echo BASE_URL; ?>admin/departments" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>