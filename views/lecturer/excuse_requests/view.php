<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Excuse Request Details</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Request from <?php echo $data['excuseRequest']->student_name; ?></h5>
                <p><strong>Unit:</strong> <?php echo $data['excuseRequest']->unit_name; ?></p>
                <p><strong>Date:</strong> <?php echo $data['excuseRequest']->date; ?></p>
                <p><strong>Day of Week:</strong> <?php echo $data['excuseRequest']->day_of_week; ?></p>
                <p><strong>Time:</strong> <?php echo $data['excuseRequest']->start_time . ' - ' . $data['excuseRequest']->end_time; ?></p>
                <p><strong>Venue:</strong> <?php echo $data['excuseRequest']->venue; ?></p>
                <p><strong>Reason:</strong> <?php echo $data['excuseRequest']->reason; ?></p>
                <p><strong>Status:</strong> <span class="badge bg-info"><?php echo $data['excuseRequest']->status; ?></span></p>
                <p><strong>Submitted On:</strong> <?php echo $data['excuseRequest']->created_at; ?></p>

                <?php if ($data['excuseRequest']->status == 'pending'): ?>
                    <a href="<?php echo BASE_URL; ?>lecturer/excuseRequests/approve/<?php echo $data['excuseRequest']->id; ?>" class="btn btn-success">Approve</a>
                    <a href="<?php echo BASE_URL; ?>lecturer/excuseRequests/reject/<?php echo $data['excuseRequest']->id; ?>" class="btn btn-danger">Reject</a>
                <?php else: ?>
                    <p><strong>Responded On:</strong> <?php echo $data['excuseRequest']->responded_at; ?></p>
                    <p><strong>Responded By:</strong> <?php echo $data['excuseRequest']->responded_by; ?></p>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>lecturer/excuseRequests" class="btn btn-secondary">Back to Requests</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>