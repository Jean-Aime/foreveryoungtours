<?php
session_start();
require_once '../config.php';
require_once '../config/database.php';

$booking = null;
$error = null;

if ($_POST['booking_reference'] ?? false) {
    $booking_ref = trim($_POST['booking_reference']);
    $email = trim($_POST['email']);
    
    if ($booking_ref && $email) {
        $stmt = $pdo->prepare("
            SELECT b.*, t.name as tour_name, t.duration, c.name as country_name 
            FROM bookings b
            JOIN tours t ON b.tour_id = t.id
            JOIN countries c ON t.country_id = c.id
            WHERE b.booking_reference = ? AND b.email = ?
        ");
        $stmt->execute([$booking_ref, $email]);
        $booking = $stmt->fetch();
        
        if (!$booking) {
            $error = "Booking not found. Please check your booking reference and email.";
        }
    } else {
        $error = "Please enter both booking reference and email.";
    }
}

include '../includes/header.php';
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Track Your Booking</h1>
            <p class="text-xl text-gray-600">Enter your booking details to check the status</p>
        </div>
        
        <?php if (!$booking): ?>
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Booking Reference</label>
                    <input type="text" name="booking_reference" required 
                           placeholder="e.g., FYT20250115001123"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                           value="<?= htmlspecialchars($_POST['booking_reference'] ?? '') ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required 
                           placeholder="Enter the email used for booking"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                
                <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg transition-all">
                    Track Booking
                </button>
            </form>
        </div>
        <?php endif; ?>
        
        <?php if ($booking): ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold mb-2"><?= htmlspecialchars($booking['tour_name']) ?></h2>
                        <p class="text-yellow-100">Booking Reference: <?= htmlspecialchars($booking['booking_reference']) ?></p>
                    </div>
                    <div class="text-right">
                        <?php
                        $status_colors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'completed' => 'bg-blue-100 text-blue-800'
                        ];
                        $status_color = $status_colors[$booking['status']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold <?= $status_color ?>">
                            <?= ucfirst($booking['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tour:</span>
                                <span class="font-medium"><?= htmlspecialchars($booking['tour_name']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Destination:</span>
                                <span class="font-medium"><?= htmlspecialchars($booking['country_name']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium"><?= htmlspecialchars($booking['duration']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Participants:</span>
                                <span class="font-medium"><?= $booking['adults'] ?> Adults, <?= $booking['children'] ?> Children</span>
                            </div>
                            <?php if ($booking['preferred_date']): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Preferred Date:</span>
                                <span class="font-medium"><?= date('M j, Y', strtotime($booking['preferred_date'])) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact & Pricing</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium"><?= htmlspecialchars($booking['full_name']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium"><?= htmlspecialchars($booking['email']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium"><?= htmlspecialchars($booking['phone']) ?></span>
                            </div>
                            <div class="border-t pt-3 mt-4">
                                <div class="flex justify-between text-lg font-bold text-yellow-600">
                                    <span>Total Price:</span>
                                    <span>$<?= number_format($booking['total_price'], 0) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex gap-4">
                    <a href="<?= BASE_URL ?>/pages/contact.php" class="flex-1 bg-gray-100 text-gray-700 text-center py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                        Contact Support
                    </a>
                    <button onclick="window.print()" class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg transition-all">
                        Print Details
                    </button>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?= BASE_URL ?>/pages/track-booking.php" class="text-yellow-600 hover:text-yellow-700 font-medium">
                ← Track Another Booking
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>