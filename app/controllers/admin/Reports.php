<?php
class Reports extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->reportModel = $this->model('Report');
        $this->courseModel = $this->model('Course'); // Include Course model
    }

    public function index() {
        $this->view('admin/reports/index');
    }

    public function attendance_report() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Generate report
        } else {
            $courses = $this->courseModel->getAll(); // Fetch all courses
            $data = [
                'courses' => $courses
            ];
            $this->view('admin/reports/attendance_report', $data);
        }
    }
}
