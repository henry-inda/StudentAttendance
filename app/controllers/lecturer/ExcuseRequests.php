<?php
class ExcuseRequests extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['lecturer']);
        $this->excuseRequestModel = $this->model('ExcuseRequest');
        $this->attendanceModel = $this->model('AttendanceModel');
        $this->scheduleModel = $this->model('ScheduleModel'); // Include ScheduleModel
    }

    public function index() {
        $excuseRequests = $this->excuseRequestModel->getByLecturer(get_session('user_id'));
        $data = [
            'title' => 'Excuse Requests',
            'excuseRequests' => $excuseRequests
        ];
        $this->view('lecturer/excuse_requests/index', $data);
    }

    public function show($id) { // Renamed from view($id) to show($id)
        $excuseRequest = $this->excuseRequestModel->findById($id);
        $data = [
            'title' => 'Excuse Request Details',
            'excuseRequest' => $excuseRequest
        ];
        $this->view('lecturer/excuse_requests/view', $data);
    }

    public function approve($id) {
        if ($this->excuseRequestModel->updateStatus($id, 'approved', get_session('user_id'))) {
            // Update attendance record to excused
            $excuseRequest = $this->excuseRequestModel->findById($id);
            // Assuming there's an attendance record to update based on schedule_id and date
            // This part needs more specific logic to find and update the correct attendance entry
            // For now, we'll just redirect
            redirect('lecturer/excuseRequests');
        }
    }

    public function reject($id) {
        if ($this->excuseRequestModel->updateStatus($id, 'rejected', get_session('user_id'))) {
            redirect('lecturer/excuseRequests');
        }
    }
}
