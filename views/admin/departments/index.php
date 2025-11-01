<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Departments Management</h2>
        <a href="<?php echo BASE_URL; ?>admin/departments/add" class="btn btn-primary mb-3">Add Department</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['departments'] as $department): ?>
                <tr>
                    <td><?php echo $department->id; ?></td>
                    <td><?php echo $department->name; ?></td>
                    <td><?php echo $department->code; ?></td>
                    <td><?php echo $department->description; ?></td>
                    <td><span class="badge bg-success"><?php echo $department->status; ?></span></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>admin/departments/edit/<?php echo $department->id; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="<?php echo BASE_URL; ?>admin/departments/delete/<?php echo $department->id; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
