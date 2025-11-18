<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Bulk Enroll Students</h2>
        <div class="card">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/enrollments/bulk_enroll" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Upload CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">Please upload a CSV file with 'student_id' and 'course_id' columns.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload and Enroll</button>
                    <a href="<?php echo BASE_URL; ?>assets/templates/enrollments_template.csv" class="btn btn-secondary" download>Download Template</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
