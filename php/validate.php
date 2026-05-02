<?php
/**
 * Server-side validation helpers (used by signup, adoption form, pet APIs).
 */

function stray_validate_email($email)
{
    $email = trim((string) $email);
    if ($email === '') {
        return 'Email is required.';
    }
    if (strlen($email) > 100) {
        return 'Email is too long.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Invalid email format.';
    }

    return null;
}

function stray_validate_password_rules($password)
{
    $password = (string) $password;
    if (strlen($password) < 6) {
        return 'Password must be at least 6 characters.';
    }
    if (strlen($password) > 72) {
        return 'Password is too long.';
    }

    return null;
}

function stray_validate_name($name, $maxLen = 100)
{
    $name = trim((string) $name);
    if ($name === '') {
        return 'Name is required.';
    }
    if (strlen($name) > $maxLen) {
        return 'Name is too long.';
    }

    return null;
}

function stray_validate_pet_type($type)
{
    $allowed = ['dogs', 'cats', 'diff'];
    if (!in_array($type, $allowed, true)) {
        return 'Invalid animal type.';
    }

    return null;
}

function stray_validate_phone($phone, $required = true)
{
    $phone = trim((string) $phone);
    if ($phone === '') {
        return $required ? 'Phone is required.' : null;
    }
    if (strlen($phone) > 20) {
        return 'Phone number is too long.';
    }
    if (!preg_match('/^[0-9+\-().\s]{7,20}$/', $phone)) {
        return 'Invalid phone format.';
    }

    return null;
}

/** Adoption form: allow typical international formatting */
function stray_validate_phone_adoption($phone)
{
    $phone = trim((string) $phone);
    if ($phone === '') {
        return 'Phone is required.';
    }
    if (strlen($phone) > 20) {
        return 'Phone number is too long.';
    }

    return null;
}

function stray_validate_age_int($age)
{
    $age = (int) $age;
    if ($age < 1 || $age > 120) {
        return 'Age must be between 1 and 120.';
    }

    return null;
}

function stray_validate_url_optional($url, $maxLen = 512)
{
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }
    if (strlen($url) > $maxLen) {
        return 'URL is too long.';
    }
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return 'Image must be a valid URL.';
    }

    return null;
}
