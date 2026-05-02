<?php
/**
 * JSON session state for the SPA nav (same-origin cookies).
 */
require_once __DIR__ . '/auth.php';

stray_session_start();

$out = [
    'loggedIn' => !empty($_SESSION['user_id']),
    'email' => isset($_SESSION['username']) ? (string) $_SESSION['username'] : null,
    'name' => isset($_SESSION['display_name']) ? (string) $_SESSION['display_name'] : null,
];

if ($out['loggedIn']) {
    $out['csrf'] = stray_csrf_token();
}

stray_json_response($out);
