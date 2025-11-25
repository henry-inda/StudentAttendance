<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Single User Attendance Report</h2>
            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/reports/single_user_attendance" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">Select User</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">-- Select User --</option>
                            <?php foreach ($data['users'] as $user): ?>
                                <option value="<?php echo $user->id; ?>" <?php echo ($data['selected_user'] == $user->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($user->full_name); ?> (<?php echo ucfirst($user->role); ?>)
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

        <?php if (!empty($data['report'])): ?>
            <div class="card">
                <div class="card-header">
                    Attendance Details for <?php echo htmlspecialchars($data['report'][0]->user_name); ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Unit Name</th>
                                    <th>Class Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Attendance Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['report'] as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record->unit_name); ?></td>
                                        <td><?php echo htmlspecialchars($record->class_date); ?></td>
                                        <td><?php echo htmlspecialchars($record->start_time); ?></td>
                                        <td><?php echo htmlspecialchars($record->end_time); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($record->attendance_status)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif (isset($data['selected_user'])): ?>
            <div class="alert alert-info">No attendance records found for the selected user in the specified date range.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
