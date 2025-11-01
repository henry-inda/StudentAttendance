<?php
class Settings extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->settingModel = $this->model('SystemSetting');
    }

    public function index() {
        $settings = $this->settingModel->getAll();
        $data = [
            'title' => 'System Settings',
            'settings' => $settings
        ];
        $this->view('admin/settings/index', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'system_name' => trim($_POST['system_name']),
                'attendance_threshold' => trim($_POST['attendance_threshold']),
                'app_email' => trim($_POST['app_email']),
                'app_password' => trim($_POST['app_password'])
            ];

            foreach ($data as $key => $value) {
                $this->settingModel->set($key, $value);
            }

            redirect('admin/settings');
        }
    }

    public function upload_logo() {
        // Handle logo upload
    }
}