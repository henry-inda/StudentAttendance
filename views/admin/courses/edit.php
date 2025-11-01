<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Edit Course</h2>
        <form action="<?php echo BASE_URL; ?>admin/courses/edit/<?php echo $data['course']->id; ?>" method="POST">
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo $data['course']->course_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" class="form-control" id="course_code" name="course_code" value="<?php echo $data['course']->course_code; ?>" required>
            </div>
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select class="form-select" id="department_id" name="department_id">
                    <?php foreach ($data['departments'] as $department): ?>
                        <option value="<?php echo $department->id; ?>" <?php echo ($data['course']->department_id == $department->id) ? 'selected' : ''; ?>><?php echo $department->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php echo ($data['course']->status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($data['course']->status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo BASE_URL; ?>admin/courses" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>