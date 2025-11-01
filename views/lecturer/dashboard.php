<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Lecturer Dashboard</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Today's Classes</div>
                    <div class="card-body">
                        <?php if (!empty($data['todays_classes'])): ?>
                            <ul class="list-group">
                                <?php foreach ($data['todays_classes'] as $class): ?>
                                    <li class="list-group-item">
                                        <?php echo $class->unit_name; ?> - <?php echo $class->start_time; ?> to <?php echo $class->end_time; ?> (<?php echo $class->venue; ?>)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No classes scheduled for today.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">This Week's Schedule</div>
                    <div class="card-body">
                        <?php if (!empty($data['weekly_schedule'])): ?>
                            <ul class="list-group">
                                <?php foreach ($data['weekly_schedule'] as $schedule): ?>
                                    <li class="list-group-item">
                                        <?php echo $schedule->day_of_week; ?>: <?php echo $schedule->unit_name; ?> - <?php echo $schedule->start_time; ?> to <?php echo $schedule->end_time; ?> (<?php echo $schedule->venue; ?>)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No schedule found for this week.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Quick Stats</div>
                    <div class="card-body">
                        <p>Pending Excuse Requests: <span class="badge bg-warning"><?php echo $data['pending_excuse_requests']; ?></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>