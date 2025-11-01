<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>System Logs</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['logs'] as $log): ?>
                <tr>
                    <td><?php echo $log->created_at; ?></td>
                    <td><?php echo $log->user_name; ?></td>
                    <td><?php echo $log->action; ?></td>
                    <td><?php echo $log->details; ?></td>
                    <td><?php echo $log->ip_address; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>