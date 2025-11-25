<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Student Attendance'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/ui-enhancements.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/notifications.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/online-status.css">
    <!-- Centralized globals and utilities -->
    <script>
        // Inject BASE_URL and currentUser early so other scripts can rely on them
        window.BASE_URL = '<?php echo BASE_URL; ?>';
        window.currentUser = {
            id: <?php echo get_session('user_id') ?? 'null'; ?>,
            role: '<?php echo get_session('user_role') ?? ''; ?>'
        };
    </script>
    <script src="<?php echo BASE_URL; ?>assets/js/app-globals.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/form_validation.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/notifications.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/websocket-client.js"></script>
</head>
<body class="<?php echo $data['body_class'] ?? ''; ?>">
