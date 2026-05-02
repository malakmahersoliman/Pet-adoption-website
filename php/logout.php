<?php
require_once __DIR__ . '/auth.php';

stray_session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

$next = isset($_GET['next']) ? (string) $_GET['next'] : '../index.html';
if (strpos($next, '//') !== false || strpos($next, ':') !== false) {
    $next = '../index.html';
}
header('Location: ' . $next);
exit();
