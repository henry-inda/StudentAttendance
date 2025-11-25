<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>My Schedule List</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Unit</th>
                    <th>Lecturer</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['schedules'] as $schedule): ?>
                <tr>
                    <td><?php echo $schedule->day_of_week; ?></td>
                    <td><?php echo $schedule->start_time . ' - ' . $schedule->end_time; ?></td>
                    <td><?php echo $schedule->unit_name; ?></td>
                    <td><?php echo $schedule->lecturer_name; ?></td>
                    <td><?php echo $schedule->venue; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
