<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config.php';
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #DAA520 0%, #FF8C00 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; background: #DAA520; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; margin: 20px 0; }
        .credentials { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #DAA520; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌍 Welcome to Forever Young Tours!</h1>
        </div>
        <div class="content">
            <p>Dear <?= htmlspecialchars($first_name) ?>,</p>
            <p>Welcome to Forever Young Tours! Your travel advisor <strong><?= htmlspecialchars($advisor_name) ?></strong> has created an account for you.</p>
            
            <div class="credentials">
                <h3>Your Login Credentials</h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                <p><strong>Password:</strong> <?= htmlspecialchars($password) ?></p>
                <p><small style="color: #666;">Please change your password after first login</small></p>
            </div>
            
            <p>With your account, you can:</p>
            <ul>
                <li>Browse and book amazing African tours</li>
                <li>Track your bookings and travel history</li>
                <li>Save your favorite destinations</li>
                <li>Get personalized travel recommendations</li>
            </ul>
            
            <center>
                <a href="<?= BASE_URL ?>/auth/login.php" class="button">Login to Your Account</a>
            </center>
            
            <p>If you have any questions, your advisor <?= htmlspecialchars($advisor_name) ?> is here to help!</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Forever Young Tours. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
