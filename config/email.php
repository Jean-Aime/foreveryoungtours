<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendEmail($to, $subject, $body, $altBody = '') {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Change to your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Your email
        $mail->Password = 'your-app-password'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom('noreply@foreveryoungtours.com', 'Forever Young Tours');
        $mail->addAddress($to);
        $mail->addReplyTo('support@foreveryoungtours.com', 'Support');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

function getEmailTemplate($template, $data = []) {
    $templatePath = __DIR__ . '/../includes/email-templates/' . $template . '.php';
    if (!file_exists($templatePath)) {
        return '';
    }
    
    ob_start();
    extract($data);
    include $templatePath;
    return ob_get_clean();
}
