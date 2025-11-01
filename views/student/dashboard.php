<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Student Dashboard</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Welcome, <?php echo get_session('user_name'); ?>!</h5>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Attendance Overview</div>
                    <div class="card-body">
                        <p>Overall Attendance: <?php echo $data['overall_attendance_percentage']; ?>%</p>
                        <?php if (!empty($data['units_below_threshold'])): ?>
                            <div class="alert alert-warning mt-2">
                                <strong>Warning!</strong> Your attendance is below threshold in:
                                <ul>
                                    <?php foreach ($data['units_below_threshold'] as $unitName): ?>
                                        <li><?php echo $unitName; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Upcoming Classes</div>
                    <div class="card-body">
                        <?php if (!empty($data['upcoming_classes'])): ?>
                            <ul class="list-group">
                                <?php foreach ($data['upcoming_classes'] as $class): ?>
                                    <li class="list-group-item">
                                        <?php echo $class->unit_name; ?> with <?php echo $class->lecturer_name; ?> on <?php echo $class->day_of_week; ?> at <?php echo $class->start_time; ?> in <?php echo $class->venue; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No upcoming classes.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Enrolled Units</div>
                    <div class="card-body">
                        <?php if (!empty($data['enrolled_units'])): ?>
                            <ul class="list-group">
                                <?php foreach ($data['enrolled_units'] as $unit): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo $unit['unit_name']; ?>
                                        <span class="badge bg-primary rounded-pill"><?php echo $unit['percentage']; ?>%</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Not enrolled in any units.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recent Notifications</div>
                    <div class="card-body">
                        <?php if (!empty($data['recent_notifications'])): ?>
                            <ul class="list-group">
                                <?php foreach ($data['recent_notifications'] as $notification): ?>
                                    <li class="list-group-item">
                                        <strong><?php echo $notification->title; ?></strong><br>
                                        <small><?php echo $notification->message; ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No recent notifications.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>