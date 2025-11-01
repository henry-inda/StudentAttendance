<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Weekly Schedule</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Week View</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Time Slot</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $timeSlots = ['07:00 - 10:00', '10:00 - 13:00', '13:00 - 16:00', '16:00 - 19:00'];
                        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

                        foreach ($timeSlots as $timeSlot) {
                            echo '<tr>';
                            echo '<td>' . $timeSlot . '</td>';
                            foreach ($daysOfWeek as $day) {
                                echo '<td>';
                                foreach ($data['weekly_schedule'] as $schedule) {
                                    if ($schedule->day_of_week == $day && $timeSlot == $schedule->start_time . ' - ' . $schedule->end_time) {
                                        echo $schedule->unit_name . ' (' . $schedule->venue . ')';
                                    }
                                }
                                echo '</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
