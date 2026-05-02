<?php
/**
 * Database: single schema `stray_station` (see sql/stray_station.sql).
 * Override with STRAY_DB_* environment variables.
 */

function stray_db_host()
{
    return getenv('STRAY_DB_HOST') ?: 'localhost';
}

function stray_db_user()
{
    return getenv('STRAY_DB_USER') ?: 'root';
}

function stray_db_pass()
{
    return getenv('STRAY_DB_PASS') !== false ? getenv('STRAY_DB_PASS') : '';
}

function stray_db_name()
{
    return getenv('STRAY_DB_NAME') ?: 'stray_station';
}

/**
 * Primary application database connection.
 *
 * @return mysqli|null
 */
function stray_db_connect()
{
    $conn = new mysqli(
        stray_db_host(),
        stray_db_user(),
        stray_db_pass(),
        stray_db_name()
    );
    if ($conn->connect_error) {
        return null;
    }
    $conn->set_charset('utf8mb4');

    return $conn;
}

/** @deprecated Use stray_db_connect(); kept for older includes */
function stray_db_connect_users()
{
    return stray_db_connect();
}

/** @deprecated Use stray_db_connect(); kept for older includes */
function stray_db_connect_adoption()
{
    return stray_db_connect();
}
