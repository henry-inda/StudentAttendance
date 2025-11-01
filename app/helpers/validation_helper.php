<?php

// app/helpers/validation_helper.php

function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_phone($phone)
{
    // Basic phone number validation (e.g., starts with +, contains only digits and +)
    return preg_match('/^\+?[0-9]{7,15}$/', $phone);
}

function validate_password($password)
{
    // Minimum 8 characters, at least one uppercase, one number, one special character
    return preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-\=\[\]{};\'\":\\|,.<>\/?]).{8,}$/', $password);
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
