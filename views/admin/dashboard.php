<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Admin Dashboard</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Students</h5>
                        <p class="card-text"><?php echo $data['total_students']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Lecturers</h5>
                        <p class="card-text"><?php echo $data['total_lecturers']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Courses</h5>
                        <p class="card-text"><?php echo $data['total_courses']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Units</h5>
                        <p class="card-text"><?php echo $data['total_units']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Attendance Overview</h5>
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="<?php echo BASE_URL; ?>admin/users/add" class="btn btn-primary">Add New User</a>
                            <a href="<?php echo BASE_URL; ?>admin/users/upload" class="btn btn-secondary">Upload Users</a>
                            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-info">Generate Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Low Attendance Alerts</div>
                    <div class="card-body">
                        <?php if (!empty($data['low_attendance_students'])): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Unit</th>
                                        <th>Attendance %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['low_attendance_students'] as $student): ?>
                                        <tr>
                                            <td><?php echo $student->student_name; ?></td>
                                            <td><?php echo $student->unit_name; ?></td>
                                            <td><?php echo round($student->attendance_percentage, 2); ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No students with low attendance.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Attendance Rate',
                data: [80, 92, 90, 85, 95, 93, 98],
                backgroundColor: 'rgba(15, 76, 117, 0.2)',
                borderColor: 'rgba(15, 76, 117, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>