<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Attendance Report</h2>
        <form action="<?php echo BASE_URL; ?>admin/reports/attendance_report" method="POST">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" class="form-control" name="start_date">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="end_date">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="course_id">
                        <option value="">Select Course</option>
                        <?php foreach ($data['courses'] as $course): ?>
                            <option value="<?php echo $course->id; ?>"><?php echo $course->course_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </div>
        </form>

        <div class="mt-4">
            <canvas id="attendanceReportChart"></canvas>
        </div>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Unit</th>
                    <th>Attendance %</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Report data will be loaded here -->
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
