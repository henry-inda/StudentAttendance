<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Account - Student Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/auth.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card shadow-lg">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <!-- Placeholder for logo -->
                            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="System Logo" style="width: 100px; height: 100px;">
                            <h3>Attendance Monitoring System</h3>
                        </div>
                        <?php flash_message('request_success'); ?>
                        <?php flash_message('request_fail'); ?>
                        <form action="<?php echo BASE_URL; ?>auth/submit_request" method="POST">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="student">Student</option>
                                    <option value="lecturer">Lecturer</option>
                                </select>
                            </div>
                            <div id="student-fields" style="display: none;">
                                <div class="mb-3">
                                    <label for="reg_number" class="form-label">Registration Number</label>
                                    <input type="text" class="form-control" id="reg_number" name="reg_number">
                                </div>
                                <div class="mb-3">
                                    <label for="course" class="form-label">Course</label>
                                    <select class="form-select" id="course" name="course">
                                        <option value="">Select Course</option>
                                        <?php if (!empty($data['courses'])): ?>
                                            <?php foreach ($data['courses'] as $course): ?>
                                                <option value="<?php echo htmlspecialchars($course->course_name); ?>"><?php echo htmlspecialchars($course->course_name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div id="lecturer-fields" style="display: none;">
                                <div class="mb-3">
                                    <label for="employee_id" class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" id="employee_id" name="employee_id">
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-select" id="department" name="department">
                                        <option value="">Select Department</option>
                                        <?php if (!empty($data['departments'])): ?>
                                            <?php foreach ($data['departments'] as $department): ?>
                                                <option value="<?php echo htmlspecialchars($department->name); ?>"><?php echo htmlspecialchars($department->name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Request</button>
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
    <script>
        document.getElementById('role').addEventListener('change', function () {
            var studentFields = document.getElementById('student-fields');
            var lecturerFields = document.getElementById('lecturer-fields');
            if (this.value === 'student') {
                studentFields.style.display = 'block';
                lecturerFields.style.display = 'none';
            } else if (this.value === 'lecturer') {
                studentFields.style.display = 'none';
                lecturerFields.style.display = 'block';
            } else {
                studentFields.style.display = 'none';
                lecturerFields.style.display = 'none';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
