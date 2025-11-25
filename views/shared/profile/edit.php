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
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch('<?php echo BASE_URL; ?>profile/edit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success message
                showToast('Profile updated successfully', 'success');
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                // Show error messages
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save Changes';
                
                if (result.errors) {
                    // Clear previous errors
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                    document.querySelectorAll('.invalid-feedback').forEach(el => {
                        el.remove();
                    });
                    
                    // Show new errors
                    Object.entries(result.errors).forEach(([field, message]) => {
                        const input = document.getElementById(field);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = message;
                            input.parentNode.appendChild(feedback);
                        }
                    });
                } else {
                    showToast(result.message || 'Failed to update profile', 'danger');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Changes';
            showToast('An error occurred while saving changes', 'danger');
        }
    });
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