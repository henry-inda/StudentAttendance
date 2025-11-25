<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Enrollments Management</h2>
        <a href="<?php echo BASE_URL; ?>admin/enrollments/enroll" class="btn btn-primary mb-3">Enroll Student</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Student Email</th>
                    <th>Course</th>
                    <th>Enrollment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['enrollments'] as $enrollment): ?>
                <tr>
                    <td><?php echo $enrollment->id; ?></td>
                    <td><?php echo $enrollment->student_name; ?></td>
                    <td><?php echo $enrollment->student_email; ?></td>
                    <td><?php echo $enrollment->course_name; ?></td>
                    <td><?php echo $enrollment->enrollment_date; ?></td>
                    <td><span class="badge bg-success"><?php echo $enrollment->status; ?></span></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>admin/enrollments/unenroll/<?php echo $enrollment->id; ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Unenroll" data-confirm="Are you sure you want to unenroll this student?"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>