<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Units Management</h2>
        <a href="<?php echo BASE_URL; ?>admin/units/add" class="btn btn-primary mb-3">Add Unit</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Unit Name</th>
                    <th>Unit Code</th>
                    <th>Course</th>
                    <th>Lecturer</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['units'] as $unit): ?>
                <tr>
                    <td><?php echo $unit->id; ?></td>
                    <td><?php echo $unit->unit_name; ?></td>
                    <td><?php echo $unit->unit_code; ?></td>
                    <td><?php echo $unit->course_name; ?></td>
                    <td><?php echo $unit->lecturer_name; ?></td>
                    <td><?php echo $unit->semester; ?></td>
                    <td><span class="badge bg-success"><?php echo $unit->status; ?></span></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>admin/units/edit/<?php echo $unit->id; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="<?php echo BASE_URL; ?>admin/units/assign_lecturer/<?php echo $unit->id; ?>" class="btn btn-sm btn-info"><i class="fas fa-user-plus"></i></a>
                        <a href="<?php echo BASE_URL; ?>admin/units/delete/<?php echo $unit->id; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
