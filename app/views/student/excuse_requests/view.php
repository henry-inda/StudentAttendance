<?php require_once APPROOT . '/views/layouts/header.php'; ?>

<div class="container">
    <h2>Excuse Request Details</h2>
    <?php if (isset($data['excuseRequest']) && $data['excuseRequest']): ?>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Unit Name</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->unit_name); ?></td>
                </tr>
                <tr>
                    <th>Student Name</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->student_name); ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->date); ?></td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->reason); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->status); ?></td>
                </tr>
                <tr>
                    <th>Day of Week</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->day_of_week); ?></td>
                </tr>
                <tr>
                    <th>Start Time</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->start_time); ?></td>
                </tr>
                <tr>
                    <th>End Time</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->end_time); ?></td>
                </tr>
                <tr>
                    <th>Venue</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->venue); ?></td>
                </tr>
                <tr>
                    <th>Submitted At</th>
                    <td><?php echo htmlspecialchars($data['excuseRequest']->created_at); ?></td>
                </tr>
                <?php if ($data['excuseRequest']->responded_at): ?>
                    <tr>
                        <th>Responded At</th>
                        <td><?php echo htmlspecialchars($data['excuseRequest']->responded_at); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="<?php echo URLROOT; ?>/student/excuseRequests" class="btn btn-secondary">Back to List</a>
    <?php else: ?>
        <p>Excuse request not found.</p>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/views/layouts/footer.php'; ?>
