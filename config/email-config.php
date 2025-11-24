<?php
/**
 * SMTP Email Configuration
 * Configure your email settings here
 */

// SMTP Settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // 'tls' or 'ssl'
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password'); // Use App Password for Gmail
define('SMTP_FROM_EMAIL', 'noreply@foreveryoungtours.com');
define('SMTP_FROM_NAME', 'iForYoungTours');

// Email Templates
define('EMAIL_LOGO_URL', 'https://yourdomain.com/assets/images/logo.png');
define('EMAIL_SUPPORT_EMAIL', 'support@foreveryoungtours.com');
define('EMAIL_COMPANY_ADDRESS', '123 Travel Street, Tourism City, TC 12345');

/**
 * Send email using SMTP
 */
function sendEmail($to, $subject, $body, $isHTML = true) {
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML($isHTML);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Get email template wrapper
 */
function getEmailTemplate($content, $title = '') {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #DAA520 0%, #B8860B 100%); color: white; padding: 30px; text-align: center; }
            .content { background: #fff; padding: 30px; border: 1px solid #ddd; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { display: inline-block; padding: 12px 30px; background: #DAA520; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>iForYoungTours</h1>
                " . ($title ? "<p>$title</p>" : "") . "
            </div>
            <div class='content'>
                $content
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " iForYoungTours. All rights reserved.</p>
                <p>" . EMAIL_COMPANY_ADDRESS . "</p>
                <p>Questions? Contact us at <a href='mailto:" . EMAIL_SUPPORT_EMAIL . "'>" . EMAIL_SUPPORT_EMAIL . "</a></p>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>
