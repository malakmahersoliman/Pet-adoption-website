<?php
/**
 * Public JSON list of pets for browse page.
 */
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/http.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    stray_json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

$conn = stray_db_connect();
if (!$conn) {
    stray_json_response(['ok' => false, 'error' => 'Database unavailable'], 503);
}

$res = $conn->query('SELECT id, name, type, description, image_url FROM pets ORDER BY id ASC');
if (!$res) {
    $conn->close();
    stray_json_response(['ok' => false, 'error' => 'Query failed'], 500);
}

$pets = [];
while ($row = $res->fetch_assoc()) {
    $pets[] = [
        'id' => (int) $row['id'],
        'name' => $row['name'],
        'type' => $row['type'],
        'description' => $row['description'],
        'image' => $row['image_url'],
    ];
}
$res->free();
$conn->close();

stray_json_response(['ok' => true, 'pets' => $pets]);
