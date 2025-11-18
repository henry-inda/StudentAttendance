<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Student Dashboard</h2>


        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Attendance Overview</div>
                    <div class="card-body">
                        <canvas id="studentAttendanceChart"></canvas>
                        <a href="<?php echo BASE_URL; ?>student/myattendance/scan_qr" class="btn btn-primary mt-3">Scan QR Code</a>
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
                                        <?php echo $unit->unit_name; ?>
                                        <span class="badge bg-primary rounded-pill"><?php echo $unit->attendance_percentage; ?>%</span>
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

<script>
    const studentCtx = document.getElementById('studentAttendanceChart').getContext('2d');
    let studentAttendanceChart;

    function createStudentChart(chartData) { // Renamed data to chartData for clarity
        if (studentAttendanceChart) {
            studentAttendanceChart.destroy();
        }

        studentAttendanceChart = new Chart(studentCtx, {
            type: 'bar', // Changed to bar chart
            data: {
                labels: chartData.labels, // Use labels from new data
                datasets: [{
                    label: 'Attendance Percentage',
                    data: chartData.data, // Use data from new data
                    backgroundColor: 'rgba(40, 167, 69, 0.6)', // Green for attendance
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100, // Max percentage is 100
                        ticks: {
                            callback: function(value) {
                                return value + '%'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // No need for legend with single dataset
                    }
                }
            }
        });
    }

    // Fetch data and create chart on page load
    fetch(`<?php echo BASE_URL; ?>student/dashboard/getUnitAttendanceChartData`) // New URL
        .then(response => response.json())
        .then(data => {
            createStudentChart(data);
        });
</script>
<?php require_once 'views/layouts/footer.php'; ?>