<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>My Schedule</h2>
        <a href="<?php echo BASE_URL; ?>lecturer/schedule/create" class="btn btn-primary mb-3">Add Schedule</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Day of Week</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Venue</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['schedules'] as $schedule): ?>
                <tr>
                    <td><?php echo $schedule->unit_name; ?></td>
                    <td><?php echo $schedule->day_of_week; ?></td>
                    <td><?php echo $schedule->start_time; ?></td>
                    <td><?php echo $schedule->end_time; ?></td>
                    <td><?php echo $schedule->venue; ?></td>
                    <td><?php echo $schedule->semester; ?></td>
                    <td><span class="badge bg-success"><?php echo $schedule->status; ?></span></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>lecturer/schedule/edit/<?php echo $schedule->id; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="<?php echo BASE_URL; ?>lecturer/schedule/delete/<?php echo $schedule->id; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
