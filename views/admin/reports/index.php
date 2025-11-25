<?php require_once VIEWS . '/layouts/header.php'; ?>
<?php require_once VIEWS . '/layouts/sidebar_admin.php'; ?>
<?php require_once VIEWS . '/layouts/navbar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4"><?= htmlspecialchars($data['title']) ?></h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Generate New Report</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>/admin/reports" method="POST" id="report-filter-form">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="form-group d-flex align-items-center">
                                        <label for="report_type" class="col-form-label me-2">Report Type:</label>
                                        <select name="report_type" id="report_type" class="form-control flex-grow-1">
                                            <option value="">Select Report Type</option>
                                            <?php foreach ($data['report_types'] as $key => $value) : ?>
                                                <option value="<?= htmlspecialchars($key) ?>" <?= ($data['filters']['report_type'] ?? '') == $key ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($value) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span id="report_type_error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group d-flex align-items-center">
                                        <label for="semester" class="col-form-label me-2">Semester:</label>
                                        <select name="semester" id="semester" class="form-control flex-grow-1">
                                            <option value="">All Semesters</option>
                                            <?php foreach ($data['semesters'] as $semester) : ?>
                                                <option value="<?= htmlspecialchars($semester) ?>" <?= ($data['filters']['semester'] ?? '') == $semester ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars(get_semester_name($semester)) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group d-flex align-items-center">
                                        <label for="start_date" class="col-form-label me-2">Start Date:</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control flex-grow-1" value="<?= htmlspecialchars($data['filters']['start_date'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group d-flex align-items-center">
                                        <label for="end_date" class="col-form-label me-2">End Date:</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control flex-grow-1" value="<?= htmlspecialchars($data['filters']['end_date'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name_filter">Filter by Name (User/Lecturer/Student)</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Select User</option>
                                        <?php foreach ($data['users'] as $user) : ?>
                                            <option value="<?= htmlspecialchars($user->id) ?>" <?= ($data['filters']['user_id'] ?? '') == $user->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($user->full_name) ?> (<?= htmlspecialchars($user->role) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span id="user_id_error" class="text-danger"></span>
                                </div>
                                <div class="form-group col-md-6" id="course-filter-group" style="display: none;">
                                    <label for="course_id">Course</label>
                                    <select name="course_id" id="course_id" class="form-control">
                                        <option value="">Select Course</option>
                                        <?php foreach ($data['courses'] as $course) : ?>
                                            <option value="<?= htmlspecialchars($course->id) ?>" <?= ($data['filters']['course_id'] ?? '') == $course->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($course->course_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span id="course_id_error" class="text-danger"></span>
                                </div>
                                <div class="form-group col-md-6" id="unit-filter-group" style="display: none;">
                                    <label for="unit_id">Unit</label>
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="">Select Unit</option>
                                        <?php foreach ($data['units'] as $unit) : ?>
                                            <option value="<?= htmlspecialchars($unit->id) ?>" <?= ($data['filters']['unit_id'] ?? '') == $unit->id ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($unit->unit_name) ?> (<?= htmlspecialchars($unit->course_name) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span id="unit_id_error" class="text-danger"></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary"><?= htmlspecialchars($data['report_title']) ?></h6>
                        <div>
                            <a href="#" id="export-csv-btn" class="btn btn-success btn-sm">Export CSV</a>
                            <a href="#" id="export-pdf-btn" class="btn btn-info btn-sm">Export PDF</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php if (!empty($data['generated_report'])) : ?>
                                <?php if ($data['filters']['report_type'] == 'course_report') : ?>
                                    <h4>Course Report: <?= htmlspecialchars($data['courses'][array_search($data['filters']['course_id'], array_column($data['courses'], 'id'))]->course_name ?? 'N/A') ?></h4>
                                    <table class="table table-bordered" id="courseReportTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Unit Name</th>
                                                <th>Unit Code</th>
                                                <th>Unit Semester</th>
                                                <th>Lecturer</th>
                                                <th>Student Name</th>
                                                <th>Enrollment Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['generated_report'] as $row) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row->unit_name) ?></td>
                                                    <td><?= htmlspecialchars($row->unit_code) ?></td>
                                                    <td><?= htmlspecialchars(get_semester_name($row->unit_semester)) ?></td>
                                                    <td><?= htmlspecialchars($row->lecturer_name ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($row->student_name ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($row->enrollment_date ?? 'N/A') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php elseif ($data['filters']['report_type'] == 'unit_report') : ?>
                                    <?php
                                        $unitName = 'N/A';
                                        if (isset($data['filters']['unit_id'])) {
                                            $unitId = $data['filters']['unit_id'];
                                            foreach ($data['units'] as $unit) {
                                                if ($unit->id == $unitId) {
                                                    $unitName = $unit->unit_name;
                                                    break;
                                                }
                                            }
                                        }
                                    ?>
                                    <h4>Unit Report: <?= htmlspecialchars($unitName) ?></h4>
                                    <table class="table table-bordered" id="unitReportTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Unit Name</th>
                                                <th>Unit Code</th>
                                                <th>Semester</th>
                                                <th>Course Name</th>
                                                <th>Lecturer Name</th>
                                                <th>Lecturer Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['generated_report'] as $row) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row->unit_name) ?></td>
                                                    <td><?= htmlspecialchars($row->unit_code) ?></td>
                                                    <td><?= htmlspecialchars(get_semester_name($row->semester)) ?></td>
                                                    <td><?= htmlspecialchars($row->course_name) ?></td>
                                                    <td><?= htmlspecialchars($row->lecturer_name ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($row->lecturer_email ?? 'N/A') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php elseif ($data['filters']['report_type'] == 'user_report') : ?>
                                    <h4>User Report for: <?= htmlspecialchars($data['users'][array_search($data['filters']['user_id'], array_column($data['users'], 'id'))]->full_name ?? 'N/A') ?></h4>
                                    <?php if (!empty($data['generated_report']['courses_units_summary'])) : ?>
                                        <h5>Attendance Progress per Unit (Student)</h5>
                                        <table class="table table-bordered" id="userReportStudentSummaryTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Course</th>
                                                    <th>Unit</th>
                                                    <th>Total Classes</th>
                                                    <th>Present</th>
                                                    <th>Absent</th>
                                                    <th>Excused</th>
                                                    <th>Attendance %</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['generated_report']['courses_units_summary'] as $row) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row->course_name) ?></td>
                                                        <td><?= htmlspecialchars($row->unit_name) ?></td>
                                                        <td><?= htmlspecialchars($row->total_classes) ?></td>
                                                        <td><?= htmlspecialchars($row->present_count) ?></td>
                                                        <td><?= htmlspecialchars($row->absent_count) ?></td>
                                                        <td><?= htmlspecialchars($row->excused_count) ?></td>
                                                        <td><?= htmlspecialchars(number_format($row->attendance_percentage ?? 0, 2)) ?>%</td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                    <?php if (!empty($data['generated_report']['classes_attended'])) : ?>
                                        <h5>Classes Attended (Student)</h5>
                                        <table class="table table-bordered" id="userReportStudentClassesTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Unit</th>
                                                    <th>Status</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['generated_report']['classes_attended'] as $row) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row->class_date) ?></td>
                                                        <td><?= htmlspecialchars($row->unit_name) ?></td>
                                                        <td><?= htmlspecialchars($row->attendance_status) ?></td>
                                                        <td><?= htmlspecialchars($row->start_time) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                    <?php if (!empty($data['generated_report']['units_taught'])) : ?>
                                        <h5>Units Taught (Lecturer)</h5>
                                        <table class="table table-bordered" id="userReportLecturerUnitsTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Unit Name</th>
                                                    <th>Unit Code</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['generated_report']['units_taught'] as $row) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row->unit_name) ?></td>
                                                        <td><?= htmlspecialchars($row->unit_code) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                    <?php if (!empty($data['generated_report']['classes_and_attendance'])) : ?>
                                        <h5>Classes and Attendance Marked (Lecturer)</h5>
                                        <table class="table table-bordered" id="userReportLecturerClassesTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Unit</th>
                                                    <th>Date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Total Students Marked</th>
                                                    <th>Present</th>
                                                    <th>Absent</th>
                                                    <th>Excused</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['generated_report']['classes_and_attendance'] as $row) : ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row->unit_name) ?></td>
                                                        <td><?= htmlspecialchars($row->class_date) ?></td>
                                                        <td><?= htmlspecialchars($row->start_time) ?></td>
                                                        <td><?= htmlspecialchars($row->end_time) ?></td>
                                                        <td><?= htmlspecialchars($row->total_students_marked) ?></td>
                                                        <td><?= htmlspecialchars($row->present_count) ?></td>
                                                        <td><?= htmlspecialchars($row->absent_count) ?></td>
                                                        <td><?= htmlspecialchars($row->excused_count) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                <?php elseif ($data['filters']['report_type'] == 'overall_system_report') : ?>
                                    <h4>Overall System Report</h4>
                                    <table class="table table-bordered" id="overallSystemReportTable" width="100%" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <th>Total Classes Taught (in range)</th>
                                                <td><?= htmlspecialchars($data['generated_report']['total_classes_taught'] ?? 0) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Active Students (in range)</th>
                                                <td><?= htmlspecialchars($data['generated_report']['active_students'] ?? 0) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Active Lecturers (in range)</th>
                                                <td><?= htmlspecialchars($data['generated_report']['active_lecturers'] ?? 0) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p>Select a report type and generate to see results.</p>
                                <?php endif; ?>
                            <?php else : ?>
                                <p>Select a report type and generate to see results.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportTypeSelect = document.getElementById('report_type');
        const userIdSelect = document.getElementById('user_id');
        const courseIdSelect = document.getElementById('course_id');
        const unitIdSelect = document.getElementById('unit_id'); // Get unit_id select
        const reportFilterForm = document.getElementById('report-filter-form');

        const reportTypeSelectError = document.getElementById('report_type_error');
        const userIdSelectError = document.getElementById('user_id_error');
        const courseIdSelectError = document.getElementById('course_id_error');
        const unitIdSelectError = document.getElementById('unit_id_error'); // Get unit_id error span

        const courseFilterGroup = document.getElementById('course-filter-group');
        const unitFilterGroup = document.getElementById('unit-filter-group'); // Get unit_filter_group

        function clearErrors() {
            reportTypeSelectError.textContent = '';
            userIdSelectError.textContent = '';
            courseIdSelectError.textContent = '';
            unitIdSelectError.textContent = '';
        }

        function toggleFilterVisibility() {
            const selectedReportType = reportTypeSelect.value;

            // Reset visibility for all specific filters
            userIdSelect.closest('.form-group').style.display = 'none';
            courseFilterGroup.style.display = 'none';
            unitFilterGroup.style.display = 'none';

            // Show filters based on selected report type
            if (selectedReportType === 'user_report') {
                userIdSelect.closest('.form-group').style.display = 'block';
            } else if (selectedReportType === 'unit_report') {
                unitFilterGroup.style.display = 'block';
            } else if (selectedReportType === 'course_report') {
                courseFilterGroup.style.display = 'block';
            }
            // overall_system_report does not require specific filters beyond date range and semester
        }

        // Initial call to set correct visibility based on pre-selected value (if any)
        toggleFilterVisibility();

        // Add event listener for changes
        reportTypeSelect.addEventListener('change', function() {
            clearErrors(); // Clear errors when report type changes
            toggleFilterVisibility();
        });

        // Add submit event listener for validation
        reportFilterForm.addEventListener('submit', function(e) {
            clearErrors(); // Clear previous errors on submit attempt
            let isValid = true;

            const selectedReportType = reportTypeSelect.value;

            if (selectedReportType === '') {
                reportTypeSelectError.textContent = 'Please select a report type.';
                isValid = false;
            } else if (selectedReportType === 'user_report') {
                if (userIdSelect.value === '') {
                    userIdSelectError.textContent = 'Please select a user.';
                    isValid = false;
                }
            } else if (selectedReportType === 'unit_report') {
                if (unitIdSelect.value === '') {
                    unitIdSelectError.textContent = 'Please select a unit.';
                    isValid = false;
                }
            } else if (selectedReportType === 'course_report') {
                if (courseIdSelect.value === '') {
                    courseIdSelectError.textContent = 'Please select a course.';
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault(); // Prevent form submission
            }
        });

        // Handle export button click
        document.getElementById('export-csv-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('report-filter-form');
            const originalAction = form.action;
            const originalMethod = form.method;

            // Temporarily change form action to export CSV
            form.action = '<?= BASE_URL ?>/admin/reports/exportAttendanceCsv';
            form.method = 'GET'; // CSV export typically uses GET for parameters

            // Submit the form
            form.submit();

            // Revert form action and method
            form.action = originalAction;
            form.method = originalMethod;
        });

        document.getElementById('export-pdf-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('report-filter-form');
            const originalAction = form.action;
            const originalMethod = form.method;

            // Temporarily change form action to export PDF
            form.action = '<?= BASE_URL ?>/admin/reports/exportAttendancePdf';
            form.method = 'GET'; // PDF export can also use GET

            // Submit the form
            form.submit();

            // Revert form action and method
            form.action = originalAction;
            form.method = originalMethod;
        });
    });
</script>

<?php require_once VIEWS . '/layouts/footer.php'; ?>

