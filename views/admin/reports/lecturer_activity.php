<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lecturer Activity Report</h2>
            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/reports/lecturer_activity" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="lecturer_id" class="form-label">Select Lecturer</label>
                        <select class="form-select" id="lecturer_id" name="lecturer_id" required>
                            <option value="">-- Select Lecturer --</option>
                            <?php foreach ($data['lecturers'] as $lecturer): ?>
                                <option value="<?php echo $lecturer->id; ?>" <?php echo ($data['selected_lecturer'] == $lecturer->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($lecturer->full_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $data['start_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $data['end_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($data['selected_lecturer'])): ?>
            <?php if (!empty($data['units_taught'])): ?>
                <div class="card mb-4">
                    <div class="card-header">Units Taught</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($data['units_taught'] as $unit): ?>
                                <li class="list-group-item"><?php echo htmlspecialchars($unit->unit_name); ?> (<?php echo htmlspecialchars($unit->unit_code); ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No units found for the selected lecturer.</div>
            <?php endif; ?>

            <?php if (!empty($data['classes_attendance'])): ?>
                <div class="card mb-4">
                    <div class="card-header">Classes Taught and Attendance Marked (<?php echo htmlspecialchars($data['start_date']); ?> to <?php echo htmlspecialchars($data['end_date']); ?>)</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Unit Name</th>
                                        <th>Class Date</th>
                                        <th>Time</th>
                                        <th>Total Students Marked</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Excused</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['classes_attendance'] as $class): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($class->unit_name); ?></td>
                                            <td><?php echo htmlspecialchars($class->class_date); ?></td>
                                            <td><?php echo htmlspecialchars($class->start_time . ' - ' . $class->end_time); ?></td>
                                            <td><?php echo htmlspecialchars($class->total_students_marked); ?></td>
                                            <td><?php echo htmlspecialchars($class->present_count); ?></td>
                                            <td><?php echo htmlspecialchars($class->absent_count); ?></td>
                                            <td><?php echo htmlspecialchars($class->excused_count); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No classes or attendance records found for the selected lecturer in the specified date range.</div>
            <?php endif; ?>
        <?php elseif (isset($data['start_date']) && isset($data['end_date'])): ?>
            <div class="alert alert-info">Please select a lecturer to generate the report.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
