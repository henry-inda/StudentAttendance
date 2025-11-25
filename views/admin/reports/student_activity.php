<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Student Activity Report</h2>
            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/reports/student_activity" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="student_id" class="form-label">Select Student</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">-- Select Student --</option>
                            <?php foreach ($data['students'] as $student): ?>
                                <option value="<?php echo $student->id; ?>" <?php echo ($data['selected_student'] == $student->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($student->full_name); ?>
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

        <?php if (!empty($data['selected_student'])): ?>
            <?php if (!empty($data['courses_units'])): ?>
                <div class="card mb-4">
                    <div class="card-header">Courses and Units Enrolled</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($data['courses_units'] as $cu): ?>
                                <li class="list-group-item">
                                    <strong><?php echo htmlspecialchars($cu->course_name); ?> (<?php echo htmlspecialchars($cu->course_code); ?>)</strong> - 
                                    <?php echo htmlspecialchars($cu->unit_name); ?> (<?php echo htmlspecialchars($cu->unit_code); ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No courses or units found for the selected student.</div>
            <?php endif; ?>

            <?php if (!empty($data['classes_attended'])): ?>
                <div class="card mb-4">
                    <div class="card-header">Classes Attended (<?php echo htmlspecialchars($data['start_date']); ?> to <?php echo htmlspecialchars($data['end_date']); ?>)</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Unit Name</th>
                                        <th>Class Date</th>
                                        <th>Time</th>
                                        <th>Attendance Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['classes_attended'] as $class): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($class->unit_name); ?></td>
                                            <td><?php echo htmlspecialchars($class->class_date); ?></td>
                                            <td><?php echo htmlspecialchars($class->start_time . ' - ' . $class->end_time); ?></td>
                                            <td><?php echo htmlspecialchars(ucfirst($class->attendance_status)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No classes attended records found for the selected student in the specified date range.</div>
            <?php endif; ?>
        <?php elseif (isset($data['start_date']) && isset($data['end_date'])): ?>
            <div class="alert alert-info">Please select a student to generate the report.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
