<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$code = trim($_POST['code'] ?? '');

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Please enter verification code']);
    exit;
}

if (!isset($_SESSION['verification_code']) || !isset($_SESSION['code_expires'])) {
    echo json_encode(['success' => false, 'message' => 'No verification code found. Please request a new one.']);
    exit;
}

if (time() > $_SESSION['code_expires']) {
    unset($_SESSION['verification_code'], $_SESSION['code_expires'], $_SESSION['verification_email']);
    echo json_encode(['success' => false, 'message' => 'Verification code expired. Please request a new one.']);
    exit;
}

if ($code !== $_SESSION['verification_code']) {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code']);
    exit;
}

$_SESSION['email_verified'] = true;
echo json_encode(['success' => true, 'message' => 'Email verified successfully']);
