<?php
class Courses extends Controller {
    private $courseModel;
    private $departmentModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->courseModel = $this->model('Course');
        $this->departmentModel = $this->model('Department');
    }

    public function index() {
        $courses = $this->courseModel->getAll();
        $data = [
            'title' => 'Courses Management',
            'courses' => $courses
        ];
        $this->view('admin/courses/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'department_id' => trim($_POST['department_id']),
                'course_name' => trim($_POST['course_name']),
                'course_code' => trim($_POST['course_code'])
            ];
            if ($this->courseModel->create($data)) {
                redirect('admin/courses');
            }
        } else {
            $departments = $this->departmentModel->getAll();
            $data = ['departments' => $departments];
            $this->view('admin/courses/add', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'department_id' => trim($_POST['department_id']),
                'course_name' => trim($_POST['course_name']),
                'course_code' => trim($_POST['course_code']),
                'status' => trim($_POST['status'])
            ];
            if ($this->courseModel->update($id, $data)) {
                redirect('admin/courses');
            }
        } else {
            $course = $this->courseModel->findById($id);
            $departments = $this->departmentModel->getAll();
            $data = [
                'course' => $course,
                'departments' => $departments
            ];
            $this->view('admin/courses/edit', $data);
        }
    }

    public function delete($id) {
        if ($this->courseModel->delete($id)) {
            redirect('admin/courses');
        }
    }
}