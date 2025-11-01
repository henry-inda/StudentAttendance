<?php
class Units extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->unitModel = $this->model('Unit');
        $this->courseModel = $this->model('Course');
        $this->userModel = $this->model('User');
    }

    public function index() {
        $units = $this->unitModel->getAll();
        $data = [
            'title' => 'Units Management',
            'units' => $units
        ];
        $this->view('admin/units/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'course_id' => trim($_POST['course_id']),
                'unit_name' => trim($_POST['unit_name']),
                'unit_code' => trim($_POST['unit_code']),
                'semester' => trim($_POST['semester'])
            ];
            if ($this->unitModel->create($data)) {
                redirect('admin/units');
            }
        } else {
            $courses = $this->courseModel->getAll();
            $data = ['courses' => $courses];
            $this->view('admin/units/add', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'course_id' => trim($_POST['course_id']),
                'unit_name' => trim($_POST['unit_name']),
                'unit_code' => trim($_POST['unit_code']),
                'semester' => trim($_POST['semester']),
                'status' => trim($_POST['status'])
            ];
            if ($this->unitModel->update($id, $data)) {
                redirect('admin/units');
            }
        } else {
            $unit = $this->unitModel->findById($id);
            $courses = $this->courseModel->getAll();
            $data = [
                'unit' => $unit,
                'courses' => $courses
            ];
            $this->view('admin/units/edit', $data);
        }
    }

    public function assign_lecturer($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lecturer_id = trim($_POST['lecturer_id']);
            if ($this->unitModel->assignLecturer($id, $lecturer_id)) {
                redirect('admin/units');
            }
        } else {
            $unit = $this->unitModel->findById($id);
            $lecturers = $this->userModel->getByRole('lecturer');
            $data = [
                'unit' => $unit,
                'lecturers' => $lecturers
            ];
            $this->view('admin/units/assign_lecturer', $data);
        }
    }

    public function delete($id) {
        if ($this->unitModel->delete($id)) {
            redirect('admin/units');
        }
    }
}