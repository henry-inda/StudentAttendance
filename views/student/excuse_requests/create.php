<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Submit New Excuse Request</h2>
        <?php // Show flash messages ?>
        <?php if (function_exists('flash_message')) { flash_message('success'); } ?>
        <?php if (!empty($data['errors'])): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($data['errors'] as $err): ?>
                        <li><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (empty($data['schedules'])): ?>
            <div class="alert alert-warning">You have no upcoming classes to request an excuse for. Please check your schedule or contact administration.</div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>student/excuseRequests/create" method="POST" <?php echo empty($data['schedules']) ? 'onsubmit="return false;"' : ''; ?>>
            <div class="mb-3">
                <label for="schedule_id" class="form-label">Select Class</label>
                <select class="form-select" id="schedule_id" name="schedule_id">
                    <?php foreach ($data['schedules'] as $schedule): ?>
                        <option value="<?php echo $schedule->id; ?>" <?php echo (!empty($data['old']['schedule_id']) && $data['old']['schedule_id'] == $schedule->id) ? 'selected' : ''; ?>><?php echo $schedule->unit_name . ' - ' . $schedule->day_of_week . ' (' . $schedule->start_time . ' - ' . $schedule->end_time . ')'; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date of Absence</label>
                <input type="date" class="form-control" id="date" name="date" required value="<?php echo !empty($data['old']['date']) ? htmlspecialchars($data['old']['date']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Absence</label>
                <textarea class="form-control" id="reason" name="reason" rows="5" minlength="20" required><?php echo !empty($data['old']['reason']) ? htmlspecialchars($data['old']['reason']) : ''; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" <?php echo empty($data['schedules']) ? 'disabled' : ''; ?>>Submit Request</button>
            <a href="<?php echo BASE_URL; ?>student/excuseRequests" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>