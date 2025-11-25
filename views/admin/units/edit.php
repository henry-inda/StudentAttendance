<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Edit Unit</h2>
        <form action="<?php echo BASE_URL; ?>admin/units/edit/<?php echo $data['unit']->id; ?>" method="POST">
            <div class="mb-3">
                <label for="unit_name" class="form-label">Unit Name</label>
                <input type="text" class="form-control" id="unit_name" name="unit_name" value="<?php echo $data['unit']->unit_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="unit_code" class="form-label">Unit Code</label>
                <input type="text" class="form-control" id="unit_code" name="unit_code" value="<?php echo $data['unit']->unit_code; ?>" required>
            </div>
            <div class="mb-3">
                <label for="course_id" class="form-label">Course</label>
                <select class="form-select" id="course_id" name="course_id">
                    <?php foreach ($data['courses'] as $course): ?>
                        <option value="<?php echo $course->id; ?>" <?php echo ($data['unit']->course_id == $course->id) ? 'selected' : ''; ?>><?php echo $course->course_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" id="semester" name="semester">
                    <option value="JAN/APR" <?php echo ($data['unit']->semester == 'JAN/APR') ? 'selected' : ''; ?>>Jan/Apr</option>
                    <option value="MAY/AUG" <?php echo ($data['unit']->semester == 'MAY/AUG') ? 'selected' : ''; ?>>May/Aug</option>
                    <option value="SEP/DEC" <?php echo ($data['unit']->semester == 'SEP/DEC') ? 'selected' : ''; ?>>Sep/Dec</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php echo ($data['unit']->status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($data['unit']->status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo BASE_URL; ?>admin/units" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>