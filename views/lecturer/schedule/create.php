<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Create New Schedule Entry</h2>
        <form action="<?php echo BASE_URL; ?>lecturer/schedule/create" method="POST">
            <div class="mb-3">
                <label for="unit_id" class="form-label">Unit</label>
                <select class="form-select" id="unit_id" name="unit_id">
                    <?php foreach ($data['units'] as $unit): ?>
                        <option value="<?php echo $unit->id; ?>"><?php echo $unit->unit_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="day_of_week" class="form-label">Day of Week</label>
                <select class="form-select" id="day_of_week" name="day_of_week">
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" required>
            </div>
            <div class="mb-3">
                <label for="venue" class="form-label">Venue</label>
                <input type="text" class="form-control" id="venue" name="venue" required>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" id="semester" name="semester">
                    <option value="JAN/APR">JAN/APR</option>
                    <option value="MAY/AUG">MAY/AUG</option>
                    <option value="SEP/DEC">SEP/DEC</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?php echo BASE_URL; ?>lecturer/schedule" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>