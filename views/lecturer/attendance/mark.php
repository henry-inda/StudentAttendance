<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Mark Attendance for <?php echo $data['schedule']->unit_name; ?></h2>
        <p><strong>Date:</strong> <?php echo date('Y-m-d'); ?></p>
        <p><strong>Time:</strong> <?php echo $data['schedule']->start_time . ' - ' . $data['schedule']->end_time; ?></p>
        <p><strong>Venue:</strong> <?php echo $data['schedule']->venue; ?></p>

        <form action="<?php echo BASE_URL; ?>lecturer/attendance/save" method="POST">
            <input type="hidden" name="schedule_id" value="<?php echo $data['schedule']->id; ?>">
            <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Excused</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['students'] as $student): ?>
                    <tr>
                        <td><?php echo $student->full_name; ?></td>
                        <td><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="present" checked></td>
                        <td><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="absent"></td>
                        <td><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="excused"></td>
                        <td><input type="text" name="notes[<?php echo $student->id; ?>]" class="form-control"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Submit Attendance</button>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>