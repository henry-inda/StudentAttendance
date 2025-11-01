<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Excuse Request Details</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Request for <?php echo $data['excuseRequest']->unit_name; ?> on <?php echo $data['excuseRequest']->date; ?></h5>
                <p><strong>Reason:</strong> <?php echo $data['excuseRequest']->reason; ?></p>
                <p><strong>Status:</strong> <span class="badge bg-info"><?php echo $data['excuseRequest']->status; ?></span></p>
                <p><strong>Submitted On:</strong> <?php echo $data['excuseRequest']->created_at; ?></p>

                <?php if ($data['excuseRequest']->status != 'pending'): ?>
                    <p><strong>Responded On:</strong> <?php echo $data['excuseRequest']->responded_at; ?></p>
                    <p><strong>Responded By:</strong> <?php echo $data['excuseRequest']->responded_by; ?></p>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>student/excuseRequests" class="btn btn-secondary">Back to Requests</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>