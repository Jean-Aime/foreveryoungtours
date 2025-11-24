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
        .details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Booking Confirmed!</h1>
        </div>
        <div class="content">
            <p>Dear <?= htmlspecialchars($customer_name) ?>,</p>
            <p>Thank you for booking with Forever Young Tours! Your adventure awaits.</p>
            
            <div class="details">
                <h3>Booking Details</h3>
                <p><strong>Tour:</strong> <?= htmlspecialchars($tour_name) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($tour_date) ?></p>
                <p><strong>Travelers:</strong> <?= $travelers ?></p>
                <p><strong>Total Amount:</strong> $<?= number_format($total_price, 2) ?></p>
                <p><strong>Booking Reference:</strong> #<?= $booking_id ?></p>
            </div>
            
            <p>We'll send you more details about your tour closer to the departure date.</p>
            
            <center>
                <a href="<?= BASE_URL ?>/client/bookings.php" class="button">View My Bookings</a>
            </center>
            
            <p>If you have any questions, feel free to contact us at support@foreveryoungtours.com</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Forever Young Tours. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
