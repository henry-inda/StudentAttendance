<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_admin.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit User</h2>
            <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>

        <?php if (isset($data['errors'])): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($data['errors'] as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/users/edit/<?php echo $data['user']->id; ?>" method="POST" id="editUserForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                value="<?php echo isset($data['user']->full_name) ? htmlspecialchars($data['user']->full_name) : ''; ?>" 
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?php echo isset($data['user']->email) ? htmlspecialchars($data['user']->email) : ''; ?>" 
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="student" <?php echo ($data['user']->role == 'student') ? 'selected' : ''; ?>>Student</option>
                                <option value="lecturer" <?php echo ($data['user']->role == 'lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                                <option value="admin" <?php echo ($data['user']->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                value="<?php echo isset($data['user']->phone) ? htmlspecialchars($data['user']->phone) : ''; ?>">
                        </div>
                    </div>

                    <div class="mb-4" id="role_specific_fields">
                        <?php if ($data['user']->role == 'student'): ?>
                            <label for="course_id" class="form-label">Course</label>
                            <?php if (isset($data['courses']) && is_array($data['courses'])): ?>
                                <select class="form-select" id="course_id" name="course_id">
                                    <option value="">Select Course</option>
                                    <?php foreach ($data['courses'] as $course): ?>
                                        <option value="<?php echo $course->id; ?>" 
                                            <?php echo ($data['user']->course_id == $course->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($course->course_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <select class="form-select" id="course_id" name="course_id">
                                    <option value="">No courses available</option>
                                </select>
                            <?php endif; ?>
                        <?php else: // lecturer or admin ?>
                            <label for="department_id" class="form-label">Department</label>
                            <?php if (isset($data['departments']) && is_array($data['departments'])): ?>
                                <select class="form-select" id="department_id" name="department_id">
                                    <option value="">Select Department</option>
                                    <?php foreach ($data['departments'] as $dept): ?>
                                        <option value="<?php echo $dept->id; ?>" 
                                            <?php echo ($data['user']->department_id == $dept->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($dept->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <select class="form-select" id="department_id" name="department_id">
                                    <option value="">No departments available</option>
                                </select>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?php echo ($data['user']->status == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($data['user']->status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="password" name="password" 
                            placeholder="Enter only if changing password">
                        <div class="form-text">
                            Password must be at least 8 characters long and include uppercase, numbers, and special characters.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const roleSpecificFieldsDiv = document.getElementById('role_specific_fields');

    // Store the HTML for course and department fields for easy switching
    const courseHtml = `
        <label for="course_id" class="form-label">Course</label>
        <?php if (isset($data['courses']) && is_array($data['courses'])): ?>
            <select class="form-select" id="course_id" name="course_id">
                <option value="">Select Course</option>
                <?php foreach ($data['courses'] as $course): ?>
                    <option value="<?php echo $course->id; ?>" 
                        <?php echo ($data['user']->course_id == $course->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course->course_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <select class="form-select" id="course_id" name="course_id">
                <option value="">No courses available</option>
            </select>
        <?php endif; ?>
    `;

    const departmentHtml = `
        <label for="department_id" class="form-label">Department</label>
        <?php if (isset($data['departments']) && is_array($data['departments'])): ?>
            <select class="form-select" id="department_id" name="department_id">
                <option value="">Select Department</option>
                <?php foreach ($data['departments'] as $dept): ?>
                    <option value="<?php echo $dept->id; ?>" 
                        <?php echo ($data['user']->department_id == $dept->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($dept->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <select class="form-select" id="department_id" name="department_id">
                <option value="">No departments available</option>
            </select>
        <?php endif; ?>
    `;

    function updateRoleSpecificFields() {
        const selectedRole = roleSelect.value;
        roleSpecificFieldsDiv.innerHTML = ''; // Clear current content

        let courseField = document.getElementById('course_id');
        let departmentField = document.getElementById('department_id');

        // Reset names to avoid submitting incorrect fields if they were previously rendered
        if (courseField) courseField.name = '';
        if (departmentField) departmentField.name = '';

        if (selectedRole === 'student') {
            roleSpecificFieldsDiv.innerHTML = courseHtml;
            document.getElementById('course_id').name = 'course_id';
            // Ensure department_id is not submitted for students
            if (departmentField) departmentField.name = '';
        } else if (selectedRole === 'lecturer' || selectedRole === 'admin') {
            roleSpecificFieldsDiv.innerHTML = departmentHtml;
            document.getElementById('department_id').name = 'department_id';
            // Ensure course_id is not submitted for lecturers/admins
            if (courseField) courseField.name = '';
        }
    }

    // Initial call to set the correct fields based on the loaded user's role
    updateRoleSpecificFields();

    // Listen for changes on the role select
    roleSelect.addEventListener('change', updateRoleSpecificFields);
});
</script>