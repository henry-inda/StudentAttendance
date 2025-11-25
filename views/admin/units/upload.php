<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Upload Units</h2>
        <p>Upload a CSV file with unit data to bulk-create units.</p>
        <p>The CSV file should have the following columns: `course_id`, `unit_name`, `unit_code`, `lecturer_id`, `semester`</p>
        
        <?php flash_message('upload_success'); ?>
        <?php flash_message('upload_error'); ?>

        <form action="<?php echo BASE_URL; ?>admin/units/upload" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="<?php echo BASE_URL; ?>admin/units" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
