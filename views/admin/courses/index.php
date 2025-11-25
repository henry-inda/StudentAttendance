<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Courses Management</h2>
        <a href="<?php echo BASE_URL; ?>admin/courses/add" class="btn btn-primary mb-3">Add Course</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['courses'] as $course): ?>
                <tr>
                    <td><?php echo $course->id; ?></td>
                    <td><?php echo $course->course_name; ?></td>
                    <td><?php echo $course->course_code; ?></td>
                    <td><?php echo $course->department_name; ?></td>
                    <td><span class="badge bg-success"><?php echo $course->status; ?></span></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>admin/courses/edit/<?php echo $course->id; ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="<?php echo BASE_URL; ?>admin/courses/delete/<?php echo $course->id; ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete" data-confirm="Are you sure you want to delete this course?"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
