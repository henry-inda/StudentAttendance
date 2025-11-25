<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Account Requests</h2>
        <?php flash_message('request_approve_success'); ?>
        <?php flash_message('request_approve_fail'); ?>
        <?php flash_message('request_reject_success'); ?>
        <?php flash_message('request_reject_fail'); ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['requests'] as $request) : ?>
                    <tr>
                        <td><?php echo $request->full_name; ?></td>
                        <td><?php echo $request->email; ?></td>
                        <td><?php echo $request->role; ?></td>
                        <td>
                            <?php if ($request->role == 'student') : ?>
                                Reg Number: <?php echo $request->reg_number; ?><br>
                                Course: <?php echo $request->course; ?>
                            <?php else : ?>
                                Employee ID: <?php echo $request->employee_id; ?><br>
                                Department: <?php echo $request->department; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>admin/requests/approve/<?php echo $request->id; ?>" class="btn btn-success">Approve</a>
                            <a href="<?php echo BASE_URL; ?>admin/requests/reject/<?php echo $request->id; ?>" class="btn btn-danger">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
