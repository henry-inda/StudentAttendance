<?php
require_once APP . '/models/User.php';
require_once APP . '/models/Department.php';
require_once APP . '/models/Course.php';
require_once APP . '/models/Unit.php';
require_once APP . '/models/StudentEnrollment.php';

function parse_users_csv($file_path) {
    $users = [];
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle, 1000, ","); // Read header row
        if (!validate_csv_structure($headers, ['full_name', 'email', 'role', 'department_code', 'phone'])) {
            return ['errors' => ['Invalid CSV headers for users.']];
        }
        $row_num = 1;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_num++;
            if (count($data) != count($headers)) {
                // Skip malformed rows or add to errors
                continue;
            }
            $users[] = [
                'full_name' => $data[0],
                'email' => $data[1],
                'role' => $data[2],
                'department_code' => $data[3],
                'phone' => $data[4],
                'row_num' => $row_num
            ];
        }
        fclose($handle);
    }
    return $users;
}

function validate_user_row($row) {
    $errors = [];
    if (empty($row['full_name'])) {
        $errors[] = 'Full name is required.';
    }
    if (empty($row['email'])) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($row['role'])) {
        $errors[] = 'Role is required.';
    } elseif (!in_array($row['role'], ['admin', 'lecturer', 'student'])) {
        $errors[] = 'Invalid role.';
    }
    return $errors;
}

function import_users($data) {
    $userModel = new User();
    $departmentModel = new Department();
    $imported_count = 0;
    $errors = [];

    foreach ($data as $user_data) {
        $row_errors = validate_user_row($user_data);
        if (!empty($row_errors)) {
            $errors[] = 'Row ' . $user_data['row_num'] . ': ' . implode(', ', $row_errors);
            continue;
        }

        // Assuming findByCode method exists in Department model
        $department = $departmentModel->findByCode($user_data['department_code']);
        $department_id = $department ? $department->id : null;

        if ($user_data['role'] !== 'admin' && !$department_id) {
            $errors[] = 'Row ' . $user_data['row_num'] . ': Invalid department code.';
            continue;
        }

        $user_data['department_id'] = $department_id;
        $user_data['password'] = 'password123'; // Default password for bulk import

        if ($userModel->create($user_data)) {
            $imported_count++;
        } else {
            $errors[] = 'Row ' . $user_data['row_num'] . ': Failed to create user (possibly duplicate email). ';
        }
    }
    return ['imported_count' => $imported_count, 'errors' => $errors];
}

function parse_courses_csv($file_path) {
    $courses = [];
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle, 1000, ",");
        if (!validate_csv_structure($headers, ['department_code', 'course_name', 'course_code'])) {
            return ['errors' => ['Invalid CSV headers for courses.']];
        }
        $row_num = 1;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_num++;
            if (count($data) != count($headers)) {
                continue;
            }
            $courses[] = [
                'department_code' => $data[0],
                'course_name' => $data[1],
                'course_code' => $data[2],
                'row_num' => $row_num
            ];
        }
        fclose($handle);
    }
    return $courses;
}

function parse_units_csv($file_path) {
    $units = [];
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle, 1000, ",");
        if (!validate_csv_structure($headers, ['course_code', 'unit_name', 'unit_code', 'semester'])) {
            return ['errors' => ['Invalid CSV headers for units.']];
        }
        $row_num = 1;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_num++;
            if (count($data) != count($headers)) {
                continue;
            }
            $units[] = [
                'course_code' => $data[0],
                'unit_name' => $data[1],
                'unit_code' => $data[2],
                'semester' => $data[3],
                'row_num' => $row_num
            ];
        }
        fclose($handle);
    }
    return $units;
}

function parse_enrollments_csv($file_path) {
    $enrollments = [];
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        $headers = fgetcsv($handle, 1000, ",");
        if (!validate_csv_structure($headers, ['student_email', 'course_code', 'enrollment_date'])) {
            return ['errors' => ['Invalid CSV headers for enrollments.']];
        }
        $row_num = 1;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row_num++;
            if (count($data) != count($headers)) {
                continue;
            }
            $enrollments[] = [
                'student_email' => $data[0],
                'course_code' => $data[1],
                'enrollment_date' => $data[2],
                'row_num' => $row_num
            ];
        }
        fclose($handle);
    }
    return $enrollments;
}

function validate_csv_structure($actual_headers, $expected_headers) {
    return count($actual_headers) === count($expected_headers) && array_diff($actual_headers, $expected_headers) === [];
}

function handle_csv_errors($errors) {
    if (!empty($errors)) {
        // Log errors or display them to the user
        return implode('<br>', $errors);
    }
    return null;
}

function generate_users_template() {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_template.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['full_name', 'email', 'role', 'department_code', 'phone']);
    fputcsv($output, ['John Doe', 'john@example.com', 'student', 'CS', '0712345678']);
    fclose($output);
    exit();
}