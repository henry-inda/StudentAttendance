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
                            <div class="col-md-6">
                                <label for="unit_select" class="form-label">Filter by Unit</label>
                                <select class="form-control" id="unit_select">
                                    <option value="">Select a Unit</option>
                                    <?php foreach ($data['units'] as $unit): ?>
                                        <option value="<?php echo $unit->id; ?>"><?php echo $unit->unit_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div id="chartContainer">
                            <canvas id="attendanceChart"></canvas>
                        </div>
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
    const chartContainer = document.getElementById('chartContainer');
    let attendanceChart;
    let ctx;

    function createOrUpdateCanvas() {
        chartContainer.innerHTML = '<canvas id="attendanceChart"></canvas>';
        ctx = document.getElementById('attendanceChart').getContext('2d');
    }

    function createChart(data) {
        if (attendanceChart) {
            attendanceChart.destroy();
        }

        const labels = data.map(item => item.unit_name);
        const presentData = data.map(item => item.average_present_rate);
        const absentData = data.map(item => item.average_absent_rate);
        const excusedData = data.map(item => item.average_excused_rate);

        attendanceChart = new Chart(ctx, {
            type: 'bar',
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
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
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

    const unitSelect = document.getElementById('unit_select');

    function fetchAttendanceDataByUnit() {
        const unitId = unitSelect.value;
        if (unitId) {
            fetch(`<?php echo BASE_URL; ?>admin/dashboard/getAttendanceOverviewDataByUnit?unit_id=${unitId}`)
                .then(response => response.json())
                .then(data => {
                    createOrUpdateCanvas();
                    if (data && data.length > 0) {
                        createChart(data);
                    } else {
                        chartContainer.innerHTML = '<p class="text-center">No attendance data for this unit.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching attendance data:', error);
                    chartContainer.innerHTML = '<p class="text-center text-danger">Error loading attendance data.</p>';
                });
        } else {
            chartContainer.innerHTML = '<p class="text-center">Please select a unit to view attendance overview.</p>';
        }
    }

    // Event listener for unit select change
    unitSelect.addEventListener('change', fetchAttendanceDataByUnit);

    // Initial chart load
    fetchAttendanceDataByUnit();
</script>