<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>
<?php 
    if (get_session('user_role') == 'admin') {
        require_once __DIR__ . '/../../layouts/sidebar_admin.php';
    } elseif (get_session('user_role') == 'lecturer') {
        require_once __DIR__ . '/../../layouts/sidebar_lecturer.php';
    } else {
        require_once __DIR__ . '/../../layouts/sidebar_student.php';
    }
?>

<div class="content">
    <div class="container-fluid">
        <h2>Account Request Details</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $data['request']->full_name; ?></h5>
                <p class="card-text"><strong>Email:</strong> <?php echo $data['request']->email; ?></p>
                <p class="card-text"><strong>Role:</strong> <?php echo ucfirst($data['request']->role); ?></p>
                <?php if ($data['request']->role == 'student') : ?>
                    <p class="card-text"><strong>Registration Number:</strong> <?php echo $data['request']->reg_number; ?></p>
                    <p class="card-text"><strong>Course:</strong> <?php echo $data['request']->course; ?></p>
                <?php else : ?>
                    <p class="card-text"><strong>Employee ID:</strong> <?php echo $data['request']->employee_id; ?></p>
                    <p class="card-text"><strong>Department:</strong> <?php echo $data['request']->department; ?></p>
                <?php endif; ?>
                <p class="card-text"><strong>Status:</strong> <?php echo ucfirst($data['request']->status); ?></p>
                <p class="card-text"><strong>Requested On:</strong> <?php echo date('F j, Y, g:i a', strtotime($data['request']->created_at)); ?></p>
                <a href="<?php echo BASE_URL; ?>admin/requests/approve/<?php echo $data['request']->id; ?>" class="btn btn-success">Approve</a>
                <a href="<?php echo BASE_URL; ?>admin/requests/reject/<?php echo $data['request']->id; ?>" class="btn btn-danger">Reject</a>
                <a href="<?php echo BASE_URL; ?>admin/requests" class="btn btn-secondary">Back to Requests</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
