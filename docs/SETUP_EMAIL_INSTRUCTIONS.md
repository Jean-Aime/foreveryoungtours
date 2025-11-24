# Email Setup Instructions - REQUIRED

## ⚠️ IMPORTANT: You MUST configure email to allow user registration

Users cannot create accounts without receiving the verification code via email.

## Quick Setup (5 minutes)

### Step 1: Get Gmail App Password

1. Go to your Google Account: https://myaccount.google.com/security
2. Enable **2-Step Verification** (if not already enabled)
3. Go to: https://myaccount.google.com/apppasswords
4. Select "Mail" and your device
5. Click "Generate"
6. Copy the 16-character password (example: `abcd efgh ijkl mnop`)

### Step 2: Update Email Configuration

Open file: `config/email-config.php`

Replace these lines:
```php
define('SMTP_USERNAME', 'your-email@gmail.com'); // Your Gmail address
define('SMTP_PASSWORD', 'your-app-password');     // The 16-char password from Step 1
```

Example:
```php
define('SMTP_USERNAME', 'john.doe@gmail.com');
define('SMTP_PASSWORD', 'abcd efgh ijkl mnop');
```

### Step 3: Test

1. Go to: http://localhost:8000/auth/register.php
2. Fill the registration form with a real email
3. Click "Create Account"
4. Check your email inbox for the verification code
5. Enter the code and complete registration

## Troubleshooting

### "Failed to send email"
- Check your Gmail credentials in `config/email-config.php`
- Make sure 2-Step Verification is enabled
- Use App Password, not your regular Gmail password
- Check if Gmail is blocking "Less secure apps"

### "Email not received"
- Check spam/junk folder
- Wait 1-2 minutes (sometimes delayed)
- Try with a different email address
- Check Gmail account is active

### Still not working?
- Verify PHPMailer is installed: Check if `vendor/phpmailer` folder exists
- Check PHP error logs in `C:\xampp1\php\logs\php_error_log`
- Test with a different Gmail account

## Alternative: Use Your Company Email

If you have SMTP credentials for your company email:

```php
define('SMTP_HOST', 'smtp.yourcompany.com');
define('SMTP_PORT', 587); // or 465 for SSL
define('SMTP_USERNAME', 'noreply@yourcompany.com');
define('SMTP_PASSWORD', 'your-password');
```

## Security Note

⚠️ Never commit `email-config.php` with real credentials to Git!
Add to `.gitignore`:
```
config/email-config.php
```
