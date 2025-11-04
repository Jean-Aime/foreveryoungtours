<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode(['success' => false, 'message' => 'Invalid email']));
}

// Check database
if (file_exists('../config/database.php')) {
    @include '../config/database.php';
    if (isset($pdo)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                die(json_encode(['success' => false, 'message' => 'Email already registered']));
            }
        } catch (Exception $e) {
            // Continue
        }
    }
}

// Generate code
$code = sprintf("%06d", mt_rand(1, 999999));
$_SESSION['verification_code'] = $code;
$_SESSION['verification_email'] = $email;
$_SESSION['code_expires'] = time() + 600;

// Send email
$emailSent = false;

if (file_exists('../config/email-config.php') && file_exists('../vendor/autoload.php')) {
    @include '../config/email-config.php';
    @include '../vendor/autoload.php';
    
    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->setFrom(SMTP_USERNAME, SMTP_FROM_NAME);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification - iForYoungTours';
            $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; padding: 20px;'>
                <h2 style='color: #333;'>Email Verification</h2>
                <p>Your verification code is:</p>
                <h1 style='color: #4CAF50; font-size: 48px; letter-spacing: 10px; text-align: center;'>$code</h1>
                <p>This code will expire in 10 minutes.</p>
                <p>If you didn't request this, please ignore this email.</p>
                <br>
                <p>Best regards,<br><strong>iForYoungTours Team</strong></p>
            </body>
            </html>
            ";
            $mail->send();
            $emailSent = true;
        } catch (Exception $e) {
            // Email failed
        }
    }
}

if ($emailSent) {
    die(json_encode(['success' => true, 'message' => 'Verification code sent to your email']));
} else {
    die(json_encode(['success' => true, 'message' => 'Test mode - Check yellow box', 'code' => $code, 'test_mode' => true]));
}
