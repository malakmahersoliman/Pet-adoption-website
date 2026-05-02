<?php
/**
 * Legacy include expected by older scripts: mysqli $conn to application database.
 */
require_once __DIR__ . '/config.php';

$conn = stray_db_connect();
if (!$conn) {
    die('Connection failed: could not connect to database. Import sql/stray_station.sql.');
}
