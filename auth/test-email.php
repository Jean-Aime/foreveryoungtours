<?php
require_once '../config/email-config.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<h2>Email Configuration Test</h2>";

// Check if credentials are set
if (SMTP_USERNAME === 'your-email@gmail.com') {
    echo "<p style='color: red;'>❌ Email not configured! Please update config/email-config.php</p>";
    echo "<p>See SETUP_EMAIL_INSTRUCTIONS.md for help</p>";
    exit;
}

echo "<p>Testing email with:</p>";
echo "<ul>";
echo "<li>Host: " . SMTP_HOST . "</li>";
echo "<li>Port: " . SMTP_PORT . "</li>";
echo "<li>Username: " . SMTP_USERNAME . "</li>";
echo "</ul>";

// Get test email from URL parameter
$testEmail = $_GET['email'] ?? '';

if (empty($testEmail)) {
    echo "<form method='GET'>";
    echo "<p>Enter your email to test:</p>";
    echo "<input type='email' name='email' required placeholder='your-email@gmail.com' style='padding: 10px; width: 300px;'>";
    echo "<button type='submit' style='padding: 10px 20px; margin-left: 10px;'>Send Test Email</button>";
    echo "</form>";
    exit;
}

// Send test email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;

    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress($testEmail);

    $mail->isHTML(true);
    $mail->Subject = 'Test Email - iForYoungTours';
    $mail->Body = "<h2>Success!</h2><p>Your email configuration is working correctly.</p>";

    $mail->send();
    echo "<p style='color: green; font-size: 20px;'>✅ Email sent successfully to $testEmail!</p>";
    echo "<p>Check your inbox (and spam folder)</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Failed to send email</p>";
    echo "<p>Error: {$mail->ErrorInfo}</p>";
    echo "<p>Please check your credentials in config/email-config.php</p>";
}
?>
