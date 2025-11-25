<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/auth.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card shadow-lg">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <!-- Placeholder for logo -->
                            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="System Logo" style="width: 100px; height: 100px;">
                            <h3>Attendance Monitoring System</h3>
                        </div>
                        <?php flash_message('login_fail'); ?>
                        <form action="<?php echo BASE_URL; ?>auth/authenticate" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $data['email'] ?? ''; ?>">
                                <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                            </div>
                            <div class="mb-3 input-group">
                                <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password">
                                <span class="input-group-text" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                </span>
                                <span class="invalid-feedback"><?php echo $data['password_err'] ?? ''; ?></span>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo BASE_URL; ?>auth/forgot_password">Forgot password?</a>
                            </div>
                            <div class="text-center mt-3">
                                <p>Don't have an account? <a href="<?php echo BASE_URL; ?>auth/request_account">Contact Admin</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const togglePasswordIcon = document.querySelector('#togglePasswordIcon');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye / eye slash icon
            togglePasswordIcon.classList.toggle('bi-eye');
            togglePasswordIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
