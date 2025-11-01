<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Attendance Trend for Unit: <?php echo $data['unit']->unit_name; ?></h2>
        <div class="card">
            <div class="card-body">
                <canvas id="attendanceTrendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<script>
    const ctx = document.getElementById('attendanceTrendChart').getContext('2d');
    const attendanceTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($data['trend_data']['labels']); ?>,
            datasets: [{
                label: 'Attendance Percentage',
                data: <?php echo json_encode($data['trend_data']['data']); ?>,
                backgroundColor: 'rgba(15, 76, 117, 0.2)',
                borderColor: 'rgba(15, 76, 117, 1)',
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
