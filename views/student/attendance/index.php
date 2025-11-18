<?php require_once 'views/layouts/header.php'; ?>
<?php require_once 'views/layouts/navbar.php'; ?>
<?php require_once 'views/layouts/sidebar_student.php'; ?>

<div class="content">
    <div class="container-fluid">


        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-check me-2"></i>My Attendance Overview</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-icon" data-bs-toggle="tooltip" title="Refresh Data" onclick="location.reload();">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <button class="btn btn-outline-secondary btn-icon" data-bs-toggle="tooltip" title="Download Report" id="downloadReport">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>

        <!-- Cards Row -->
        <div class="row" id="attendanceCards">
            <?php foreach ($data['attendanceData'] as $unitAttendance): ?>
            <div class="col-md-4 mb-4">
                <div class="card animate__animated animate__fadeIn">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title">
                                <i class="fas fa-book me-2"></i>
                                <?php echo $unitAttendance->unit_name; ?>
                            </h5>
                            <span class="badge bg-<?php echo $unitAttendance->percentage >= 75 ? 'success' : 'warning'; ?> rounded-pill">
                                <?php echo $unitAttendance->percentage; ?>%
                            </span>
                        </div>
                        
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-<?php echo $unitAttendance->percentage >= 75 ? 'success' : 'warning'; ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo $unitAttendance->percentage; ?>%;" 
                                 aria-valuenow="<?php echo $unitAttendance->percentage; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="<?php echo BASE_URL; ?>student/myAttendance/unit_details/<?php echo $unitAttendance->unit_id; ?>" 
                               class="btn btn-primary" 
                               data-bs-toggle="tooltip" 
                               title="View detailed attendance history">
                                <i class="fas fa-chart-bar me-2"></i>View Details
                            </a>
                            <button class="btn btn-icon btn-outline-secondary" 
                                    data-bs-toggle="tooltip" 
                                    title="Submit excuse request"
                                    onclick="window.location.href='<?php echo BASE_URL; ?>student/excuseRequests/create/<?php echo $unitAttendance->unit_id; ?>'">
                                <i class="fas fa-note-medical"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/attendance-view.js"></script>
<?php require_once 'views/layouts/footer.php'; ?>
