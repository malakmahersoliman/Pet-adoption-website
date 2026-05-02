<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/http.php';

function stray_session_start()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Require logged-in user for JSON APIs; sends 401 and exits otherwise.
 */
function stray_require_login_json()
{
    stray_session_start();
    if (empty($_SESSION['user_id'])) {
        stray_json_response(['ok' => false, 'error' => 'Authentication required.'], 401);
    }
}

/**
 * Optional CSRF token (rotate when absent).
 */
function stray_csrf_token()
{
    stray_session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function stray_csrf_validate($token)
{
    stray_session_start();
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], (string) $token);
}
