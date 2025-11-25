<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Account Requests Report</h2>
            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/reports/account_requests_report" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $data['start_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $data['end_date'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All</option>
                            <option value="pending" <?php echo ($data['selected_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo ($data['selected_status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo ($data['selected_status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                        </select>
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
                    Account Requests (<?php echo htmlspecialchars($data['start_date']); ?> to <?php echo htmlspecialchars($data['end_date']); ?>)
                    <?php if ($data['selected_status']): ?>
                        - Status: <?php echo ucfirst(htmlspecialchars($data['selected_status'])); ?>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Reg/Emp ID</th>
                                    <th>Course/Dept</th>
                                    <th>Status</th>
                                    <th>Requested On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['report'] as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request->full_name); ?></td>
                                        <td><?php echo htmlspecialchars($request->email); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($request->role)); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($request->role == 'student' ? $request->reg_number : $request->employee_id); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($request->role == 'student' ? $request->course : $request->department); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars(ucfirst($request->status)); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($request->created_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif (isset($data['start_date']) && isset($data['end_date'])): ?>
            <div class="alert alert-info">No account requests found for the specified criteria.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
