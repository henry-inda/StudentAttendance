<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Select Class to Mark Attendance</h2>
        <form action="<?php echo BASE_URL; ?>lecturer/attendance/mark" method="GET">
            <div class="mb-3">
                <label for="schedule_id" class="form-label">Select Class</label>
                <select class="form-select" id="schedule_id" name="schedule_id">
                    <?php foreach ($data['schedules'] as $schedule): ?>
                        <option value="<?php echo $schedule->id; ?>"><?php echo $schedule->unit_name . ' - ' . $schedule->day_of_week . ' (' . $schedule->start_time . ' - ' . $schedule->end_time . ')'; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mark Attendance</button>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>