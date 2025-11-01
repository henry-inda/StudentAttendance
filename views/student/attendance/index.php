<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>My Attendance Overview</h2>
        <div class="row">
            <?php foreach ($data['attendanceData'] as $unitAttendance): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $unitAttendance['unit_name']; ?></h5>
                        <p class="card-text">Attendance: <?php echo $unitAttendance['percentage']; ?>%</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $unitAttendance['percentage']; ?>%;" aria-valuenow="<?php echo $unitAttendance['percentage']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <a href="<?php echo BASE_URL; ?>student/myAttendance/unit_details/<?php echo $unitAttendance['unit_id']; ?>" class="btn btn-primary mt-3">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
