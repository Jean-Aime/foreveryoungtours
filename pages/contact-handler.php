<?php
session_start();
require_once '../includes/csrf.php';
require_once '../config/database.php';
require_once '../config/email.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Save to database
    $stmt = $pdo->prepare("INSERT INTO contact_messages (first_name, last_name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$first_name, $last_name, $email, $phone, $subject, $message]);
    
    // Send email notification
    $email_body = "New contact form submission:\n\n";
    $email_body .= "Name: $first_name $last_name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Phone: $phone\n";
    $email_body .= "Subject: $subject\n\n";
    $email_body .= "Message:\n$message";
    
    sendEmail('support@foreveryoungtours.com', 'New Contact Form Submission', $email_body);
    
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
