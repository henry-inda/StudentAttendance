<?php
// Validation helper functions with vh_ prefix to avoid naming conflicts
function vh_validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function vh_is_unique_email($email) {
    // Lazy-load User model to check uniqueness
    require_once APP . '/models/User.php';
    $userModel = new User();
    return $userModel->findByEmail($email) ? false : true;
}

function vh_validate_password_strength($password) {
    // Minimum 8 characters, at least one uppercase, one number, one special character
    return preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-\=\[\]{};\'\":\\|,.<>\/?]).{8,}$/', $password);
}

function vh_validate_date_format($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function vh_validate_phone($phone) {
    // Basic phone number validation (e.g., starts with +, contains only digits and +)
    return preg_match('/^\+?[0-9]{7,15}$/', $phone);
}

function vh_validate_uploaded_file($file, $allowed_types = ['image/jpeg','image/png','text/csv'], $max_size = 2097152) {
    // $file is from $_FILES
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return false;
    if ($file['size'] > $max_size) return false;
    if (!in_array(mime_content_type($file['tmp_name']), $allowed_types)) return false;
    return true;
}

function validate_required(array $fields, array $data)
{
    $errors = [];
    foreach ($fields as $field) {
        if (empty($data[$field])) {
            $errors[$field] = ucfirst($field) . ' is required.';
        }
    }
    return $errors;
}
