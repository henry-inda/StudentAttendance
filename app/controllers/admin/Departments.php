<?php
class Departments extends Controller {
    private $departmentModel;

    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->departmentModel = $this->model('Department');
    }

    public function index() {
        $departments = $this->departmentModel->getAll();
        $data = [
            'title' => 'Departments Management',
            'departments' => $departments
        ];
        $this->view('admin/departments/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code']),
                'description' => trim($_POST['description'])
            ];
            if ($this->departmentModel->create($data)) {
                redirect('admin/departments');
            }
        } else {
            $this->view('admin/departments/add');
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'code' => trim($_POST['code']),
                'description' => trim($_POST['description']),
                'status' => trim($_POST['status'])
            ];
            if ($this->departmentModel->update($id, $data)) {
                redirect('admin/departments');
            }
        } else {
            $department = $this->departmentModel->findById($id);
            $data = ['department' => $department];
            $this->view('admin/departments/edit', $data);
        }
    }

    public function delete($id) {
        if ($this->departmentModel->delete($id)) {
            redirect('admin/departments');
        }
    }
}