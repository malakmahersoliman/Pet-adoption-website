<?php
/**
 * Session-based login against MySQL adopters table.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/validate.php';

function stray_login_redirect_next($postNext)
{
    $allowed = ['index.html', 'pets.html', 'dashboard.html', 'adopt.html', 'register.html'];
    $next = isset($postNext) ? trim((string) $postNext) : '';
    if ($next === '') {
        return '../index.html';
    }
    $base = basename(parse_url($next, PHP_URL_PATH) ?: $next);
    if (in_array($base, $allowed, true)) {
        return '../' . $base;
    }

    return '../index.html';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html');
    exit();
}

$email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';
$nextRaw = $_POST['next'] ?? '';

$err = stray_validate_email($email);
if ($err || $password === '') {
    header('Location: ../login.html?error=' . ($err ? 'validation' : 'password'));
    exit();
}

$conn = stray_db_connect();
if (!$conn) {
    http_response_code(503);
    echo 'Database unavailable. Import sql/stray_station.sql and check php/config.php.';
    exit();
}

$stmt = $conn->prepare('SELECT id, password, name FROM adopters WHERE email = ? LIMIT 1');
if (!$stmt) {
    $conn->close();
    http_response_code(500);
    echo 'Server error.';
    exit();
}

$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result ? $result->fetch_assoc() : null;
$stmt->close();
$conn->close();

if ($row && password_verify($password, $row['password'])) {
    stray_session_start();
    $_SESSION['user_id'] = (int) $row['id'];
    $_SESSION['username'] = $email;
    $_SESSION['display_name'] = (string) $row['name'];
    $_SESSION['login_success'] = true;
    header('Location: ' . stray_login_redirect_next($nextRaw));
    exit();
}

header('Location: ../login.html?error=credentials');
exit();
