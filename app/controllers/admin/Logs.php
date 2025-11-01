<?php
class Logs extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->logModel = $this->model('SystemLog');
    }

    public function index() {
        $logs = $this->logModel->getAll();
        $data = [
            'title' => 'System Logs',
            'logs' => $logs
        ];
        $this->view('admin/logs/index', $data);
    }
}