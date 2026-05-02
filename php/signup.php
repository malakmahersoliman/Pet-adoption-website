<?php
/**
 * Registration with server-side validation → adopters table.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.html');
    exit();
}

$name = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
$email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim((string) $_POST['phone']) : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

$v = stray_validate_name($name, 100);
if ($v) {
    header('Location: ../register.html?error=validation&field=name');
    exit();
}
$v = stray_validate_email($email);
if ($v) {
    header('Location: ../register.html?error=validation&field=email');
    exit();
}
$v = stray_validate_phone($phone, false);
if ($v) {
    header('Location: ../register.html?error=validation&field=phone');
    exit();
}
$v = stray_validate_password_rules($password);
if ($v) {
    header('Location: ../register.html?error=validation&field=password');
    exit();
}

$conn = stray_db_connect();
if (!$conn) {
    http_response_code(503);
    echo 'Database unavailable. Import sql/stray_station.sql and check php/config.php.';
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO adopters (name, email, phone, password) VALUES (?, ?, ?, ?)');
if (!$stmt) {
    $conn->close();
    http_response_code(500);
    echo 'Server error.';
    exit();
}

$stmt->bind_param('ssss', $name, $email, $phone, $hash);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    $qs = http_build_query([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
    ]);
    header('Location: signup_success.php?' . $qs);
    exit();
}

$errno = $stmt->errno;
$stmt->close();
$conn->close();

if ($errno === 1062) {
    header('Location: ../register.html?error=duplicate_email');
    exit();
}

header('Location: ../register.html?error=server');
exit();
