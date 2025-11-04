<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if email and phone are verified
if (!isset($_SESSION['email_verified']) || !isset($_SESSION['phone_verified'])) {
    echo json_encode(['success' => false, 'message' => 'Please verify your email and phone number first']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($name) || empty($email) || empty($phone) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Verify email and phone match verified ones
if ($email !== $_SESSION['email_verified'] || $phone !== $_SESSION['phone_verified']) {
    echo json_encode(['success' => false, 'message' => 'Email or phone number does not match verified credentials']);
    exit;
}

// Check if user already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
$stmt->execute([$email, $phone]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'User with this email or phone already exists']);
    exit;
}

// Create user account
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role, email_verified, phone_verified, status, created_at) VALUES (?, ?, ?, ?, 'client', 1, 1, 'active', NOW())");
    $stmt->execute([$name, $email, $phone, $hashedPassword]);
    
    $userId = $pdo->lastInsertId();
    
    // Set session
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = 'client';
    
    // Clear verification sessions
    unset($_SESSION['email_verified']);
    unset($_SESSION['phone_verified']);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Account created successfully',
        'redirect' => '/client/index.php'
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()]);
}
?>
