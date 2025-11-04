<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$type = $_POST['type'] ?? ''; // 'email' or 'phone'
$value = $_POST['value'] ?? '';

if (empty($type) || empty($value)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Generate 6-digit verification code
$code = sprintf("%06d", mt_rand(1, 999999));
$expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// Store verification code in session
$_SESSION['verification_' . $type] = [
    'code' => $code,
    'value' => $value,
    'expires' => $expires
];

if ($type === 'email') {
    // Send email verification
    $to = $value;
    $subject = "iForYoungTours - Email Verification Code";
    $message = "Your verification code is: $code\n\nThis code will expire in 15 minutes.\n\nIf you didn't request this code, please ignore this email.";
    $headers = "From: noreply@iforyoungtours.com\r\n";
    $headers .= "Reply-To: support@iforyoungtours.com\r\n";
    
    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Verification code sent to your email']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send email']);
    }
} elseif ($type === 'phone') {
    // Send SMS verification (using a simple SMS API - replace with your provider)
    // For demo purposes, we'll just store it in session
    // In production, integrate with SMS gateway like Twilio, Africa's Talking, etc.
    
    echo json_encode([
        'success' => true, 
        'message' => 'Verification code sent to your phone',
        'demo_code' => $code // Remove this in production
    ]);
}
?>
