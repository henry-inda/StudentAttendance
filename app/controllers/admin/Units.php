<?php
class Units extends Controller {
    private $unitModel;
    private $courseModel;
    private $userModel;

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

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
                $file = $_FILES['csv_file']['tmp_name'];
                $handle = fopen($file, "r");
                
                // Skip the header row
                fgetcsv($handle);

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $lecturer = $this->userModel->findByEmail($data[3]);
                    if ($lecturer) {
                        $unitData = [
                            'course_id' => $data[0],
                            'unit_name' => $data[1],
                            'unit_code' => $data[2],
                            'lecturer_id' => $lecturer->id,
                            'semester' => $data[4]
                        ];
                        $this->unitModel->createFromCsv($unitData);
                    }
                }
                fclose($handle);
                flash_message('upload_success', 'Units uploaded successfully.');
                redirect('admin/units');
            } else {
                flash_message('upload_error', 'Error uploading file.', 'alert-danger');
                redirect('admin/units/upload');
            }
        } else {
            // Display form
            $this->view('admin/units/upload');
        }
    }
}