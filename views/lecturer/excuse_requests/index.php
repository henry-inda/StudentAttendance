<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Excuse Requests</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['excuseRequests'] as $request): ?>
                <tr>
                    <td><?php echo $request->student_name; ?></td>
                    <td><?php echo $request->unit_name; ?></td>
                    <td><?php echo $request->date; ?></td>
                    <td><?php echo substr($request->reason, 0, 50) . (strlen($request->reason) > 50 ? '...' : ''); ?></td>
                    <td><span class="badge bg-info"><?php echo $request->status; ?></span></td>
                    <td><?php echo $request->created_at; ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>lecturer/excuseRequests/show/<?php echo $request->id; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                        <?php if ($request->status == 'pending'): ?>
                            <a href="<?php echo BASE_URL; ?>lecturer/excuseRequests/approve/<?php echo $request->id; ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                            <a href="<?php echo BASE_URL; ?>lecturer/excuseRequests/reject/<?php echo $request->id; ?>" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>