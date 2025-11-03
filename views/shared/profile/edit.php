<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php 
    if (get_session('user_role') == 'admin') {
        require_once 'views/layouts/sidebar_admin.php';
    } elseif (get_session('user_role') == 'lecturer') {
        require_once 'views/layouts/sidebar_lecturer.php';
    } else {
        require_once 'views/layouts/sidebar_student.php';
    }
?>

<div class="content">
    <div class="container-fluid">
        <h2>Edit Profile</h2>
        <form action="<?php echo BASE_URL; ?>profile/edit" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $data['user']->full_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $data['user']->phone; ?>">
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                <?php if ($data['user']->profile_picture): ?>
                    <img src="<?php echo BASE_URL . UPLOADS_PATH . $data['user']->profile_picture; ?>" alt="Profile Picture" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?php echo BASE_URL; ?>profile" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('<?php echo BASE_URL; ?>profile/edit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the name in the navbar
                    const profileDropdown = document.getElementById('profileDropdown');
                    profileDropdown.innerHTML = `<i class="fas fa-user"></i> ${data.user.full_name}`;

                    // Optional: show a success message
                    alert('Profile updated successfully!');
                } else {
                    // Optional: show an error message
                    alert('Failed to update profile. Please try again.');
                }
            });
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>