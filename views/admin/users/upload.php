<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Upload Users via CSV</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">CSV Upload</h5>
                <p class="card-text">Upload a CSV file with user data. The format should be: full_name, email, role, department_code, phone</p>
                <form action="<?php echo BASE_URL; ?>admin/users/upload" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">CSV File</label>
                        <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <a href="<?php echo BASE_URL; ?>admin/users/downloadTemplate" class="btn btn-secondary">Download Template</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>