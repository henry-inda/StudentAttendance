<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Edit Schedule Entry</h2>
        <form action="<?php echo BASE_URL; ?>lecturer/schedule/edit/<?php echo $data['schedule']->id; ?>" method="POST">
            <div class="mb-3">
                <label for="unit_id" class="form-label">Unit</label>
                <select class="form-select" id="unit_id" name="unit_id">
                    <?php foreach ($data['units'] as $unit): ?>
                        <option value="<?php echo $unit->id; ?>" <?php echo ($data['schedule']->unit_id == $unit->id) ? 'selected' : ''; ?>><?php echo $unit->unit_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="day_of_week" class="form-label">Day of Week</label>
                <select class="form-select" id="day_of_week" name="day_of_week">
                    <option value="Monday" <?php echo ($data['schedule']->day_of_week == 'Monday') ? 'selected' : ''; ?>>Monday</option>
                    <option value="Tuesday" <?php echo ($data['schedule']->day_of_week == 'Tuesday') ? 'selected' : ''; ?>>Tuesday</option>
                    <option value="Wednesday" <?php echo ($data['schedule']->day_of_week == 'Wednesday') ? 'selected' : ''; ?>>Wednesday</option>
                    <option value="Thursday" <?php echo ($data['schedule']->day_of_week == 'Thursday') ? 'selected' : ''; ?>>Thursday</option>
                    <option value="Friday" <?php echo ($data['schedule']->day_of_week == 'Friday') ? 'selected' : ''; ?>>Friday</option>
                    <option value="Saturday" <?php echo ($data['schedule']->day_of_week == 'Saturday') ? 'selected' : ''; ?>>Saturday</option>
                    <option value="Sunday" <?php echo ($data['schedule']->day_of_week == 'Sunday') ? 'selected' : ''; ?>>Sunday</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo $data['schedule']->start_time; ?>" required>
            </div>
            <div class="mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo $data['schedule']->end_time; ?>" required>
            </div>
            <div class="mb-3">
                <label for="venue" class="form-label">Venue</label>
                <input type="text" class="form-control" id="venue" name="venue" value="<?php echo $data['schedule']->venue; ?>" required>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" id="semester" name="semester">
                    <option value="JAN/APR" <?php echo ($data['schedule']->semester == 'JAN/APR') ? 'selected' : ''; ?>>Jan/Apr</option>
                    <option value="MAY/AUG" <?php echo ($data['schedule']->semester == 'MAY/AUG') ? 'selected' : ''; ?>>May/Aug</option>
                    <option value="SEP/DEC" <?php echo ($data['schedule']->semester == 'SEP/DEC') ? 'selected' : ''; ?>>Sep/Dec</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" <?php echo ($data['schedule']->status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="cancelled" <?php echo ($data['schedule']->status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?php echo BASE_URL; ?>lecturer/schedule" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>