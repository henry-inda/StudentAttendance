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
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
            $file = $_FILES['csv_file'];

            // Validate file upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                flash_message('error', 'Error uploading file.');
                redirect('admin/enrollments/bulk_enroll');
                return;
            }
            if ($file['type'] !== 'text/csv') {
                flash_message('error', 'Invalid file type. Please upload a CSV file.');
                redirect('admin/enrollments/bulk_enroll');
                return;
            }

            $handle = fopen($file['tmp_name'], 'r');
            if ($handle === FALSE) {
                flash_message('error', 'Could not open CSV file.');
                redirect('admin/enrollments/bulk_enroll');
                return;
            }

            // Skip header row
            fgetcsv($handle);

            $success_count = 0;
            $failure_count = 0;
            $already_enrolled_count = 0;

            while (($data = fgetcsv($handle)) !== FALSE) {
                // Assuming CSV format: student_email, course_id
                $student_email = $data[0] ?? null;
                $course_id = $data[1] ?? null;

                if (empty($student_email) || empty($course_id)) {
                    $failure_count++;
                    continue;
                }

                $student = $this->userModel->findByEmail($student_email);
                if (!$student || $student->role !== 'student') {
                    $failure_count++;
                    continue;
                }

                if ($this->enrollmentModel->isStudentEnrolled($student->id, $course_id)) {
                    $already_enrolled_count++;
                } else {
                    if ($this->enrollmentModel->enrollStudent($student->id, $course_id)) {
                        $success_count++;
                    } else {
                        $failure_count++;
                    }
                }
            }
            fclose($handle);

            $message = "Bulk enrollment complete: {$success_count} students enrolled, {$already_enrolled_count} already enrolled, {$failure_count} failed.";
            flash_message('success', $message);
            redirect('admin/enrollments');

        } else {
            $data = [
                'title' => 'Bulk Enroll Students'
            ];
            $this->view('admin/enrollments/bulk_enroll', $data);
        }
    }

    public function unenroll($id) {
        if ($this->enrollmentModel->delete($id)) {
            redirect('admin/enrollments');
        }
    }
}