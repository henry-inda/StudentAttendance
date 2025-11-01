<?php
class Enrollments extends Controller {
    public function __construct() {
        require_once 'app/helpers/auth_middleware.php';
        check_role(['admin']);
        $this->enrollmentModel = $this->model('StudentEnrollment');
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
    }

    public function index() {
        $enrollments = $this->enrollmentModel->getAll();
        $data = [
            'title' => 'Enrollments Management',
            'enrollments' => $enrollments
        ];
        $this->view('admin/enrollments/index', $data);
    }

    public function enroll() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'student_id' => trim($_POST['student_id']),
                'course_id' => trim($_POST['course_id']),
                'enrollment_date' => trim($_POST['enrollment_date'])
            ];
            if ($this->enrollmentModel->create($data)) {
                redirect('admin/enrollments');
            }
        } else {
            $students = $this->userModel->getByRole('student');
            $courses = $this->courseModel->getAll();
            $data = [
                'students' => $students,
                'courses' => $courses
            ];
            $this->view('admin/enrollments/enroll', $data);
        }
    }

    public function bulk_enroll() {
        // To be implemented
        $this->view('admin/enrollments/bulk_enroll');
    }

    public function unenroll($id) {
        if ($this->enrollmentModel->delete($id)) {
            redirect('admin/enrollments');
        }
    }
}