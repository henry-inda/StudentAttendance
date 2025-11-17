<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_lecturer.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2>Attendance for <?php echo $data['schedule']->unit_name; ?></h2>
        <p><strong>Date:</strong> <?php echo date('Y-m-d'); ?></p>
        <p><strong>Time:</strong> <?php echo $data['schedule']->start_time . ' - ' . $data['schedule']->end_time; ?></p>
        <p><strong>Venue:</strong> <?php echo $data['schedule']->venue; ?></p>

        <?php if ($data['is_submitted']): ?>
            <div class="alert alert-info" role="alert">
                Attendance for this session has been submitted. You are viewing the submitted record.
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $attendance_map = [];
                    foreach ($data['existing_attendance'] as $att) {
                        $attendance_map[$att->student_id] = $att;
                    }
                    ?>
                    <?php foreach ($data['students'] as $student): ?>
                    <tr>
                        <td><?php echo $student->full_name; ?></td>
                        <td>
                            <?php echo htmlspecialchars($attendance_map[$student->id]->status ?? 'N/A'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($attendance_map[$student->id]->notes ?? ''); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="<?php echo BASE_URL; ?>lecturer/attendance" class="btn btn-secondary">Back to Schedules</a>
        <?php else: ?>
            <form action="<?php echo BASE_URL; ?>lecturer/attendance/save" method="POST">
                <input type="hidden" name="schedule_id" value="<?php echo $data['schedule']->id; ?>">
                <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Excused</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['students'] as $student): ?>
                        <tr>
                            <td><?php echo $student->full_name; ?></td>
                            <td><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="present" checked></td>
                            <td><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="absent"></td>
                            <td><input type="radio" name="attendance[<?php echo $student->id; ?>]" value="excused"></td>
                            <td><input type="text" name="notes[<?php echo $student->id; ?>]" class="form-control"></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Submit Attendance</button>
                <a href="<?php echo BASE_URL; ?>lecturer/attendance/generate_qr/<?php echo $data['schedule']->id; ?>" class="btn btn-info">Generate QR Code</a>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/websocket-client.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleId = <?php echo $data['schedule']->id; ?>;
        const lecturerId = <?php echo get_session('user_id'); ?>;
        const userRole = '<?php echo get_session('user_role'); ?>';

        // Initialize WebSocket connection
        const ws = new WebSocket('ws://localhost:8080'); // Adjust if your WebSocket server is on a different host/port

        ws.onopen = function() {
            console.log('WebSocket connected');
            // Authenticate with the WebSocket server
            ws.send(JSON.stringify({
                type: 'auth',
                userId: lecturerId,
                userRole: userRole
            }));
        };

        ws.onmessage = function(event) {
            const message = JSON.parse(event.data);
            console.log('WebSocket message received:', message);

            if (message.type === 'attendance_update' && message.scheduleId == scheduleId) {
                // Find the student row and update their status
                const studentId = message.studentId;
                const status = message.status;

                const studentRow = document.querySelector(`input[name="attendance[${studentId}]"][value="${status}"]`);
                if (studentRow) {
                    studentRow.checked = true;
                    // Optionally, highlight the row or add a visual cue
                    studentRow.closest('tr').style.backgroundColor = '#d4edda'; // Greenish background
                    setTimeout(() => {
                        studentRow.closest('tr').style.backgroundColor = ''; // Remove highlight after a short delay
                    }, 3000);
                }
            }
        };

        ws.onclose = function() {
            console.log('WebSocket disconnected');
        };

        ws.onerror = function(error) {
            console.error('WebSocket error:', error);
        };
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>