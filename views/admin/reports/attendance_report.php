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

        <?php if (!empty($data['report'])): ?>
            <div class="mt-2">
                <form action="<?php echo BASE_URL; ?>admin/reports/exportAttendanceCsv" method="GET" style="display:inline-block;">
                    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($data['selected_course']); ?>">
                    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($data['start_date']); ?>">
                    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($data['end_date']); ?>">
                    <button type="submit" class="btn btn-success">Export CSV</button>
                </form>
            </div>
        <?php endif; ?>

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
                <?php if (!empty($data['report'])): ?>
                    <?php foreach ($data['report'] as $row): ?>
                        <?php
                            $total = (int)$row->total_classes;
                            $present = (int)$row->present_count;
                            $pct = $total > 0 ? round(($present / $total) * 100, 2) : 0;
                            $status = $pct < 75 ? 'At Risk' : 'OK';
                        ?>
                        <tr>
                            <td><?php echo $row->student_name; ?></td>
                            <td><?php echo $row->unit_name; ?></td>
                            <td><?php echo $pct; ?>%</td>
                            <td><?php echo $status; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No report data. Please select a course and date range, then click Generate.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (!empty($data['report'])): ?>
<script>
    (function(){
        // Prepare chart data
        const labels = <?php echo json_encode(array_map(function($r){ return $r->student_name; }, $data['report'])); ?>;
        const data = <?php echo json_encode(array_map(function($r){ $total = (int)$r->total_classes; $present = (int)$r->present_count; return $total>0 ? round(($present/$total)*100,2) : 0; }, $data['report'])); ?>;

        const ctx = document.getElementById('attendanceReportChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Attendance %',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
    })();
</script>
<?php endif; ?>

<?php require_once 'views/layouts/footer.php'; ?>
