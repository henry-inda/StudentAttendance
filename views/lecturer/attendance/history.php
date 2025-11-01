<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Attendance History for Unit: <?php echo $data['unit']->unit_name; ?></h2>
        <p>This page will display the past attendance records for a specific unit.</p>
        
        <?php if (!empty($data['attendance_records'])): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['attendance_records'] as $record): ?>
                        <tr>
                            <td><?php echo $record->student_name; ?></td>
                            <td><?php echo $record->date; ?></td>
                            <td><?php echo $record->start_time . ' - ' . $record->end_time; ?></td>
                            <td><?php echo $record->venue; ?></td>
                            <td><span class="badge bg-<?php 
                                if ($record->status == 'present') echo 'success';
                                elseif ($record->status == 'absent') echo 'danger';
                                else echo 'info';
                            ?>"><?php echo $record->status; ?></span></td>
                            <td><?php echo $record->notes; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No attendance records found for this unit.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>