<?php
class ExcuseRequests extends Controller {
    private $excuseRequestModel;
    private $attendanceModel;
    private $scheduleModel;

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
            // Get excuse request details
            $excuseRequest = $this->excuseRequestModel->findById($id);
            
            // Mark student as excused in attendance
            if ($excuseRequest) {
                $this->attendanceModel->markAsExcused($excuseRequest->student_id, $excuseRequest->schedule_id, $excuseRequest->date);
            }
            
            // Send WebSocket notification
            require_once APP . '/helpers/websocket_helper.php';
            WebSocketNotifier::getInstance()->notify([
                'type' => 'excuse_response',
                'studentId' => $excuseRequest->student_id,
                'excuseId' => $id,
                'status' => 'approved',
                'message' => 'Your excuse request has been approved'
            ]);
            redirect('lecturer/excuseRequests');
        }
    }

    public function reject($id) {
        if ($this->excuseRequestModel->updateStatus($id, 'rejected', get_session('user_id'))) {
            // Get excuse request details
            $excuseRequest = $this->excuseRequestModel->findById($id);
            
            // Send WebSocket notification
            require_once APP . '/helpers/websocket_helper.php';
            WebSocketNotifier::getInstance()->notify([
                'type' => 'excuse_response',
                'studentId' => $excuseRequest->student_id,
                'excuseId' => $id,
                'status' => 'rejected',
                'message' => 'Your excuse request has been rejected'
            ]);
            redirect('lecturer/excuseRequests');
        }
    }
}
