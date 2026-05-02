<?php
/**
 * Adoption application → adoption_submissions with FK to pets (relational integrity).
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../adopt.html');
    exit();
}

$name = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
$email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim((string) $_POST['phone']) : '';
$address = isset($_POST['address']) ? trim((string) $_POST['address']) : '';
$age = isset($_POST['age']) ? (int) $_POST['age'] : 0;
$adoption_interest = isset($_POST['adoption_interest']) ? trim((string) $_POST['adoption_interest']) : '';
$pet_id_raw = isset($_POST['pet_id']) ? trim((string) $_POST['pet_id']) : '';
$pet_id = $pet_id_raw !== '' ? (int) $pet_id_raw : null;

$v = stray_validate_name($name, 255);
if ($v) {
    header('Location: ../adopt.html?error=validation');
    exit();
}
$v = stray_validate_email($email);
if ($v) {
    header('Location: ../adopt.html?error=validation');
    exit();
}
$v = stray_validate_phone_adoption($phone);
if ($v) {
    header('Location: ../adopt.html?error=validation');
    exit();
}
if ($address === '' || strlen($address) > 255) {
    header('Location: ../adopt.html?error=validation');
    exit();
}
$v = stray_validate_age_int($age);
if ($v) {
    header('Location: ../adopt.html?error=validation');
    exit();
}
if ($adoption_interest === '' || strlen($adoption_interest) > 50000) {
    header('Location: ../adopt.html?error=validation');
    exit();
}

$conn = stray_db_connect();
if (!$conn) {
    http_response_code(503);
    echo 'Database unavailable.';
    exit();
}

if ($pet_id !== null && $pet_id > 0) {
    $chk = $conn->prepare('SELECT id FROM pets WHERE id = ? LIMIT 1');
    if ($chk) {
        $chk->bind_param('i', $pet_id);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows === 0) {
            $chk->close();
            $conn->close();
            header('Location: ../adopt.html?error=invalid_pet');
            exit();
        }
        $chk->close();
    }
} else {
    $pet_id = null;
}

if ($pet_id === null || $pet_id < 1) {
    $stmt = $conn->prepare(
        'INSERT INTO adoption_submissions (adopter_name, email, phone, address, age, adoption_interest) VALUES (?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        $conn->close();
        header('Location: ../adopt.html?error=server');
        exit();
    }
    $stmt->bind_param('ssssis', $name, $email, $phone, $address, $age, $adoption_interest);
} else {
    $stmt = $conn->prepare(
        'INSERT INTO adoption_submissions (adopter_name, email, phone, address, age, adoption_interest, pet_id) VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        $conn->close();
        header('Location: ../adopt.html?error=server');
        exit();
    }
    $stmt->bind_param('ssssisi', $name, $email, $phone, $address, $age, $adoption_interest, $pet_id);
}

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Location: ../adopt.html?submitted=1');
    exit();
}

$stmt->close();
$conn->close();
header('Location: ../adopt.html?error=server');
exit();
