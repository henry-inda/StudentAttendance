<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Upcoming Classes</h2>
        <div class="row">
            <?php if (!empty($data['upcoming_classes'])): ?>
                <?php foreach ($data['upcoming_classes'] as $class): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $class->unit_name; ?></h5>
                                <p class="card-text"><strong>Day:</strong> <?php echo $class->day_of_week; ?></p>
                                <p class="card-text"><strong>Time:</strong> <?php echo $class->start_time . ' - ' . $class->end_time; ?></p>
                                <p class="card-text"><strong>Venue:</strong> <?php echo $class->venue; ?></p>
                                <p class="card-text"><strong>Lecturer:</strong> <?php echo $class->lecturer_name; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No upcoming classes found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
