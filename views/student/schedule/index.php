<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>My Schedule</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List View</h5>
                        <p class="card-text">View your schedule in a detailed list format.</p>
                        <a href="<?php echo BASE_URL; ?>student/mySchedule/list" class="btn btn-primary">View List</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Classes</h5>
                        <p class="card-text">See your upcoming classes for the next 7 days.</p>
                        <a href="<?php echo BASE_URL; ?>student/mySchedule/upcoming" class="btn btn-primary">View Upcoming</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>