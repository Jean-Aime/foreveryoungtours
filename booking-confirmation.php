<?php
require_once 'config/database.php';

$booking_id = $_GET['id'] ?? null;
if (!$booking_id) {
    header('Location: /');
    exit;
}

// Get booking details
$stmt = $pdo->prepare("
    SELECT b.*, t.title as tour_title, t.main_image, c.name as country_name 
    FROM bookings b 
    JOIN tours t ON b.tour_id = t.id 
    JOIN countries c ON t.country_id = c.id 
    WHERE b.id = ?
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
    header('Location: /');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Success Message -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h1 class="text-2xl font-bold text-green-800">Booking Confirmed!</h1>
                        <p class="text-green-700">Your booking has been successfully submitted.</p>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Booking Details</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Booking ID:</span>
                        <span class="text-gray-600">#<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Tour:</span>
                        <span class="text-gray-600"><?= htmlspecialchars($booking['tour_title']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Country:</span>
                        <span class="text-gray-600"><?= htmlspecialchars($booking['country_name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Travel Date:</span>
                        <span class="text-gray-600"><?= date('F j, Y', strtotime($booking['travel_date'])) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Travelers:</span>
                        <span class="text-gray-600"><?= $booking['number_of_travelers'] ?> people</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Total Amount:</span>
                        <span class="text-xl font-bold text-green-600">$<?= number_format($booking['total_amount']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-blue-800 mb-3">What's Next?</h3>
                <ul class="space-y-2 text-blue-700">
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                        Our team will contact you within 24 hours to confirm details
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                        We'll send you a detailed itinerary and payment instructions
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                        Complete payment to secure your booking
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-lg font-bold mb-3">Need Help?</h3>
                <p class="text-gray-600 mb-4">Our team is here to assist you</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="https://wa.me/17374439646?text=Booking%20ID%20<?= $booking['id'] ?>" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp Support
                    </a>
                    <a href="mailto:info@iforeveryoungtours.com?subject=Booking%20ID%20<?= $booking['id'] ?>" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-envelope mr-2"></i>Email Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>