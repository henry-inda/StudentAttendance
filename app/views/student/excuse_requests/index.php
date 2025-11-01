<?php require_once APPROOT . '/views/layouts/header.php'; ?>

<div class="container">
    <h2>My Excuse Requests</h2>
    <a href="<?php echo URLROOT; ?>/student/excuseRequests/create" class="btn btn-primary mb-3">Create New Excuse Request</a>

    <?php if (isset($data['excuseRequests']) && !empty($data['excuseRequests'])) : ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Unit Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['excuseRequests'] as $request) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request->unit_name); ?></td>
                        <td><?php echo htmlspecialchars($request->date); ?></td>
                        <td><?php echo htmlspecialchars($request->status); ?></td>
                        <td><?php echo htmlspecialchars($request->created_at); ?></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/student/excuseRequests/show/<?php echo $request->id; ?>" class="btn btn-info">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No excuse requests found.</p>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/views/layouts/footer.php'; ?>
