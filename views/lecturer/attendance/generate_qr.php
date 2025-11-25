<?php require_once VIEWS . '/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2>Scan to Mark Attendance for <?php echo $data['schedule']->unit_name; ?></h2>
            <p>This QR code is valid for 5 minutes.</p>
            <img src="data:image/png;base64,<?php echo base64_encode($data['qr_code']); ?>" alt="QR Code">
            <div class="mt-3">
                <a href="<?php echo BASE_URL; ?>lecturer/attendance/mark/<?php echo $data['schedule']->id; ?>" class="btn btn-secondary">Back to Attendance</a>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS . '/layouts/footer.php'; ?>
