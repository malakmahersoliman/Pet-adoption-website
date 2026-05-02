<?php
require_once dirname(__DIR__) . '/auth.php';
require_once dirname(__DIR__) . '/validate.php';
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

$name = isset($body['name']) ? trim((string) $body['name']) : '';
$type = isset($body['type']) ? (string) $body['type'] : '';
$description = isset($body['description']) ? trim((string) $body['description']) : '';
$image = isset($body['image']) ? trim((string) $body['image']) : (isset($body['image_url']) ? trim((string) $body['image_url']) : '');

$err = stray_validate_name($name, 120);
if ($err) {
    stray_json_response(['ok' => false, 'error' => $err], 400);
}
$err = stray_validate_pet_type($type);
if ($err) {
    stray_json_response(['ok' => false, 'error' => $err], 400);
}
if (strlen($description) > 2000) {
    stray_json_response(['ok' => false, 'error' => 'Description is too long.'], 400);
}
$err = stray_validate_url_optional($image);
if ($err) {
    stray_json_response(['ok' => false, 'error' => $err], 400);
}
if ($image === '') {
    stray_json_response(['ok' => false, 'error' => 'Image URL is required.'], 400);
}

$conn = stray_db_connect();
if (!$conn) {
    stray_json_response(['ok' => false, 'error' => 'Database unavailable'], 503);
}

$stmt = $conn->prepare('UPDATE pets SET name = ?, type = ?, description = ?, image_url = ? WHERE id = ?');
if (!$stmt) {
    $conn->close();
    stray_json_response(['ok' => false, 'error' => 'Server error'], 500);
}

$stmt->bind_param('ssssi', $name, $type, $description, $image, $id);
$ok = $stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();
$conn->close();

if (!$ok) {
    stray_json_response(['ok' => false, 'error' => 'Update failed'], 500);
}
if ($affected === 0) {
    stray_json_response(['ok' => false, 'error' => 'Pet not found'], 404);
}

stray_json_response([
    'ok' => true,
    'pet' => [
        'id' => $id,
        'name' => $name,
        'type' => $type,
        'description' => $description,
        'image' => $image,
    ],
]);
