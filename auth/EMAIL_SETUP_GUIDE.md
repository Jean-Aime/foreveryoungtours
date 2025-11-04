# Email Setup Guide

## Current Setup (Local Development)

The verification code is currently displayed in:
1. Browser console (F12 → Console tab)
2. Modal popup (for easy testing)

This allows you to test the registration without configuring SMTP.

## For Production (Real Email Sending)

### Option 1: Using PHPMailer with Gmail (Recommended)

1. Install PHPMailer:
```bash
composer require phpmailer/phpmailer
```

2. Update `send-verification-code.php`:
```php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@gmail.com';
    $mail->Password = 'your-app-password';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('noreply@iForYoungTours.com', 'iForYoungTours');
    $mail->addAddress($email);
    $mail->Subject = 'Email Verification - iForYoungTours';
    $mail->Body = "Your verification code is: $code\n\nThis code will expire in 10 minutes.";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Verification code sent']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
}
```

### Option 2: Configure XAMPP SMTP

Edit `php.ini`:
```ini
[mail function]
SMTP=smtp.gmail.com
smtp_port=587
sendmail_from=your-email@gmail.com
sendmail_path="\"C:\xampp\sendmail\sendmail.exe\" -t"
```

Edit `sendmail.ini`:
```ini
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=your-email@gmail.com
auth_password=your-app-password
force_sender=your-email@gmail.com
```

## Testing Locally

1. Fill registration form
2. Click "Create Account"
3. Open browser console (F12)
4. Look for "Verification Code: XXXXXX"
5. Copy the code
6. Paste in the modal
7. Click "Verify & Register"

## Remove Debug Code for Production

In `send-verification-code.php`, remove:
```php
'code' => $code // Remove this line
```

In `register.php`, remove the console.log section.
