<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Unit Attendance Details</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Unit: <?php echo $data['unit']->unit_name; ?></h5>
                <p><strong>Total Classes:</strong> <?php echo $data['attendance_stats']->total_classes; ?></p>
                <p><strong>Present:</strong> <?php echo $data['attendance_stats']->present_count; ?></p>
                <p><strong>Absent:</strong> <?php echo $data['attendance_stats']->absent_count; ?></p>
                <p><strong>Excused:</strong> <?php echo $data['attendance_stats']->excused_count; ?></p>
                <p><strong>Percentage:</strong> <?php echo $data['attendance_stats']->percentage; ?>%</p>
                <a href="<?php echo BASE_URL; ?>student/myAttendance/trend/<?php echo $data['unit']->id; ?>" class="btn btn-info">View Trend Chart</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['attendance_records'])): ?>
                    <?php foreach ($data['attendance_records'] as $record): ?>
                        <tr>
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
                <?php else: ?>
                    <tr>
                        <td colspan="5">No attendance records found for this unit.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
