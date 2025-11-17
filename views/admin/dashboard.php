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
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary" id="update_chart">Update Chart</button>
                            </div>
                        </div>
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
    let attendanceChart;

    function createChart(data) {
        if (attendanceChart) {
            attendanceChart.destroy();
        }

        const labels = data.map(item => item.date);
        const presentData = data.map(item => item.present_rate);
        const absentData = data.map(item => item.absent_rate);
        const excusedData = data.map(item => item.excused_rate);

        attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Present Rate',
                    data: presentData,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }, {
                    label: 'Absent Rate',
                    data: absentData,
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }, {
                    label: 'Excused Rate',
                    data: excusedData,
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '%'
                            }
                        }
                    }
                }
            }
        });
    }

    function updateChart() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        fetch(`<?php echo BASE_URL; ?>admin/dashboard/getAttendanceOverviewData?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                createChart(data);
            });
    }

    document.getElementById('update_chart').addEventListener('click', updateChart);

    // Initial chart load
    createChart(<?php echo json_encode($data['attendance_overview']); ?>);
</script>