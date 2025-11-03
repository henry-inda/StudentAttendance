<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>My Excuse Requests</h2>
        <a href="<?php echo BASE_URL; ?>student/excuseRequests/create" class="btn btn-primary mb-3">Submit New Request</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Response Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['excuseRequests'] as $request): ?>
                <tr>
                    <td><?php echo $request->unit_name; ?></td>
                    <td><?php echo $request->date; ?></td>
                    <td><?php echo substr($request->reason, 0, 50) . (strlen($request->reason) > 50 ? '...' : ''); ?></td>
                    <td><span class="badge bg-info"><?php echo $request->status; ?></span></td>
                    <td><?php echo $request->created_at; ?></td>
                    <td><?php echo $request->responded_at ?? 'N/A'; ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>student/excuseRequests/viewRequest/<?php echo $request->id; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
