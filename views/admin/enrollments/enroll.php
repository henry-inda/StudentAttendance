<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Enroll Student</h2>
        <form action="<?php echo BASE_URL; ?>admin/enrollments/enroll" method="POST">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select class="form-select" id="student_id" name="student_id">
                    <?php foreach ($data['students'] as $student): ?>
                        <option value="<?php echo $student->id; ?>"><?php echo $student->full_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="course_id" class="form-label">Course</label>
                <select class="form-select" id="course_id" name="course_id">
                    <?php foreach ($data['courses'] as $course): ?>
                        <option value="<?php echo $course->id; ?>"><?php echo $course->course_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="enrollment_date" class="form-label">Enrollment Date</label>
                <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Enroll</button>
            <a href="<?php echo BASE_URL; ?>admin/enrollments" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>