<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Add New Course</h2>
        <form action="<?php echo BASE_URL; ?>admin/courses/add" method="POST">
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" required>
            </div>
            <div class="mb-3">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" class="form-control" id="course_code" name="course_code" required>
            </div>
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select class="form-select" id="department_id" name="department_id">
                    <?php foreach ($data['departments'] as $department): ?>
                        <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?php echo BASE_URL; ?>admin/courses" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>