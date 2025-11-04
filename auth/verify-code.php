<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$type = $_POST['type'] ?? ''; // 'email' or 'phone'
$code = $_POST['code'] ?? '';

if (empty($type) || empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$sessionKey = 'verification_' . $type;

if (!isset($_SESSION[$sessionKey])) {
    echo json_encode(['success' => false, 'message' => 'No verification code found. Please request a new one.']);
    exit;
}

$verification = $_SESSION[$sessionKey];

// Check if code expired
if (strtotime($verification['expires']) < time()) {
    unset($_SESSION[$sessionKey]);
    echo json_encode(['success' => false, 'message' => 'Verification code expired. Please request a new one.']);
    exit;
}

// Verify code
if ($code === $verification['code']) {
    $_SESSION[$type . '_verified'] = $verification['value'];
    unset($_SESSION[$sessionKey]);
    echo json_encode(['success' => true, 'message' => ucfirst($type) . ' verified successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code']);
}
?>
