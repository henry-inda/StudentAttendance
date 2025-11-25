<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Users Management</h2>
        <div class="d-flex justify-content-between mb-3">
            <div>
                <a href="<?php echo BASE_URL; ?>admin/users/add" class="btn btn-primary">Add User</a>
                <a href="<?php echo BASE_URL; ?>admin/users/upload" class="btn btn-secondary">Upload Users</a>
            </div>
            <input type="text" class="form-control w-25" placeholder="Search...">
        </div>

        <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="true">Students</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lecturers-tab" data-bs-toggle="tab" data-bs-target="#lecturers" type="button" role="tab" aria-controls="lecturers" aria-selected="false">Lecturers</button>
            </li>
        </ul>
        <div class="tab-content" id="userTabsContent">
            <div class="tab-pane fade show active" id="students" role="tabpanel" aria-labelledby="students-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['students'] as $user): ?>
                        <tr>
                            <td><?php echo $user->id; ?></td>
                            <td><?php echo htmlspecialchars($user->full_name); ?></td>
                            <td><?php echo htmlspecialchars($user->email); ?></td>
                            <td><?php echo htmlspecialchars($user->role); ?></td>
                            <td><?php echo htmlspecialchars($user->course_id ?? ''); ?></td>
                            <td><span class="badge bg-success"><?php echo htmlspecialchars($user->status); ?></span></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>admin/users/edit/<?php echo $user->id; ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?php echo BASE_URL; ?>admin/users/delete/<?php echo $user->id; ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete" data-confirm="Are you sure you want to delete this user?"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="lecturers" role="tabpanel" aria-labelledby="lecturers-tab">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['lecturers'] as $user): ?>
                        <tr>
                            <td><?php echo $user->id; ?></td>
                            <td><?php echo htmlspecialchars($user->full_name); ?></td>
                            <td><?php echo htmlspecialchars($user->email); ?></td>
                            <td><?php echo htmlspecialchars($user->role); ?></td>
                            <td><?php echo htmlspecialchars($user->department_id ?? ''); ?></td>
                            <td><span class="badge bg-success"><?php echo htmlspecialchars($user->status); ?></span></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>admin/users/edit/<?php echo $user->id; ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?php echo BASE_URL; ?>admin/users/delete/<?php echo $user->id; ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete" data-confirm="Are you sure you want to delete this user?"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
