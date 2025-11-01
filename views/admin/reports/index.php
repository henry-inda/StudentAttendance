<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Reports</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Attendance Report</h5>
                        <p class="card-text">Generate attendance reports for students, units, or courses.</p>
                        <a href="<?php echo BASE_URL; ?>admin/reports/attendance_report" class="btn btn-primary">Generate</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>