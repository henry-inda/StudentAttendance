<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Student Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/auth.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <!-- Placeholder for logo -->
                            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="System Logo" style="width: 100px; height: 100px;">
                            <h3>Attendance Monitoring System</h3>
                        </div>
                        <?php flash_message('forgot_password_fail'); ?>
                        <form action="<?php echo BASE_URL; ?>auth/requestPasswordReset" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Enter your email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo BASE_URL; ?>auth/login">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
