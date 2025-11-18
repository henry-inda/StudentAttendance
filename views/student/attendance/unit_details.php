<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2><?php echo $data['title']; ?></h2>

        <?php if (!empty($data['attendance_history'])): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day of Week</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['attendance_history'] as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record->date); ?></td>
                            <td><?php echo htmlspecialchars($record->day_of_week); ?></td>
                            <td><?php echo htmlspecialchars($record->start_time . ' - ' . $record->end_time); ?></td>
                            <td><?php echo htmlspecialchars($record->venue); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($record->status)); ?></td>
                            <td><?php echo htmlspecialchars($record->notes); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No detailed attendance history found for this unit.
            </div>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>student/myattendance" class="btn btn-secondary">Back to My Attendance</a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>