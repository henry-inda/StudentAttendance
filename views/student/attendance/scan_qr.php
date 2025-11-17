<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Scan QR Code</h2>
        <div class="card">
            <div class="card-body">
                <div id="qr-reader" style="width: 500px"></div>
                <div id="qr-reader-results"></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        console.log("Decoded QR Code Text:", decodedText); // Debugging line
        // construct the full URL
        var fullUrl = "<?php echo BASE_URL; ?>student/myattendance/mark_by_qr/" + decodedText;
        console.log("Full URL:", fullUrl); // Debugging line
        // redirect to the decoded URL
        window.location.href = fullUrl;
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>

<?php require_once 'views/layouts/footer.php'; ?>