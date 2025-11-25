<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>All Users Attendance Summary Report</h2>
            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/reports/all_users_attendance_summary" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $data['start_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $data['end_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($data['report'])): ?>
            <div class="card">
                <div class="card-header">
                    Attendance Summary (<?php echo htmlspecialchars($data['start_date']); ?> to <?php echo htmlspecialchars($data['end_date']); ?>)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Role</th>
                                    <th>Total Classes</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Excused</th>
                                    <th>Attendance %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['report'] as $record): ?>
                                    <?php
                                        $total = (int)$record->total_classes;
                                        $present = (int)$record->present_count;
                                        $pct = $total > 0 ? round(($present / $total) * 100, 2) : 0;
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record->full_name); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($record->role)); ?></td>
                                        <td><?php echo htmlspecialchars($total); ?></td>
                                        <td><?php echo htmlspecialchars($present); ?></td>
                                        <td><?php echo htmlspecialchars($record->absent_count); ?></td>
                                        <td><?php echo htmlspecialchars($record->excused_count); ?></td>
                                        <td><?php echo $pct; ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif (isset($data['start_date']) && isset($data['end_date'])): ?>
            <div class="alert alert-info">No attendance records found for the specified date range.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
