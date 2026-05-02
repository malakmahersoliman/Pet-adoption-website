<?php
require_once dirname(__DIR__) . '/auth.php';
require_once dirname(__DIR__) . '/http.php';

stray_require_login_json();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    stray_json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

$body = stray_json_body();
if (!stray_csrf_validate($body['csrf'] ?? '')) {
    stray_json_response(['ok' => false, 'error' => 'Invalid or missing CSRF token.'], 403);
}

$id = isset($body['id']) ? (int) $body['id'] : 0;
if ($id < 1) {
    stray_json_response(['ok' => false, 'error' => 'Invalid id'], 400);
}

$conn = stray_db_connect();
if (!$conn) {
    stray_json_response(['ok' => false, 'error' => 'Database unavailable'], 503);
}

$stmt = $conn->prepare('DELETE FROM pets WHERE id = ?');
if (!$stmt) {
    $conn->close();
    stray_json_response(['ok' => false, 'error' => 'Server error'], 500);
}

$stmt->bind_param('i', $id);
$stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();
$conn->close();

if ($affected === 0) {
    stray_json_response(['ok' => false, 'error' => 'Pet not found'], 404);
}

stray_json_response(['ok' => true]);
