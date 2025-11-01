<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Assign Lecturer to <?php echo $data['unit']->unit_name; ?></h2>
        <form action="<?php echo BASE_URL; ?>admin/units/assign_lecturer/<?php echo $data['unit']->id; ?>" method="POST">
            <div class="mb-3">
                <label for="lecturer_id" class="form-label">Lecturer</label>
                <select class="form-select" id="lecturer_id" name="lecturer_id">
                    <?php foreach ($data['lecturers'] as $lecturer): ?>
                        <option value="<?php echo $lecturer->id; ?>" <?php echo ($data['unit']->lecturer_id == $lecturer->id) ? 'selected' : ''; ?>><?php echo $lecturer->full_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign</button>
            <a href="<?php echo BASE_URL; ?>admin/units" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>