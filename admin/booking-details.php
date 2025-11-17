<?php

require_once 'config.php';
require_once '../config/database.php';

$booking_id = $_GET['id'] ?? 0;
$source = $_GET['source'] ?? 'booking';

if ($source == 'inquiry') {
    // Get booking inquiry details
    $stmt = $pdo->prepare("
        SELECT bi.*, 
               bi.client_name as customer_name,
               bi.email as customer_email,
               bi.phone as customer_phone,
               bi.travel_dates as travel_date,
               bi.adults as participants,
               bi.budget as total_amount,
               0 as commission_amount,
               'pending' as payment_status,
               NULL as payment_method,
               CONCAT('INQ-', bi.id) as booking_reference,
               t.name as tour_name, t.destination, t.duration, t.price as tour_price,
               c.name as country_name,
               NULL as advisor_name,
               NULL as mca_name
        FROM booking_inquiries bi
        LEFT JOIN tours t ON bi.tour_id = t.id
        LEFT JOIN countries c ON t.country_id = c.id
        WHERE bi.id = ?
    ");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();
} else {
    // Get booking details with all related information
    $stmt = $pdo->prepare("
        SELECT b.*, 
               t.name as tour_name, t.destination, t.duration, t.price as tour_price,
               CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
               CONCAT(m.first_name, ' ', m.last_name) as mca_name,
               c.name as country_name
        FROM bookings b 
        LEFT JOIN tours t ON b.tour_id = t.id
        LEFT JOIN users a ON b.advisor_id = a.id
        LEFT JOIN users m ON a.mca_id = m.id
        LEFT JOIN countries c ON t.country_id = c.id
        WHERE b.id = ?
    ");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch();
}

if (!$booking) {
    die('Booking not found');
}

// Get commission details
$stmt = $pdo->prepare("
    SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as recipient_name
    FROM commissions c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.booking_id = ?
");
$stmt->execute([$booking_id]);
$commissions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - <?= $booking['booking_reference'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-6">
                <h1 class="text-2xl font-bold">Booking Details</h1>
                <p class="text-blue-100">Reference: <?= $booking['booking_reference'] ?></p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Customer Information -->
                    <div>
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">Customer Information</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Name</label>
                                <p class="text-gray-900"><?= htmlspecialchars($booking['customer_name']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-900"><?= htmlspecialchars($booking['customer_email']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Phone</label>
                                <p class="text-gray-900"><?= htmlspecialchars($booking['customer_phone']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Emergency Contact</label>
                                <p class="text-gray-900"><?= htmlspecialchars($booking['emergency_contact'] ?: 'Not provided') ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Tour Information -->
                    <div>
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">Tour Information</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tour Name</label>
                                <p class="text-gray-900"><?= htmlspecialchars($booking['tour_name']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Destination</label>
                                <p class="text-gray-900"><?= htmlspecialchars($booking['destination']) ?>, <?= htmlspecialchars($booking['country_name']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Duration</label>
                                <p class="text-gray-900"><?= htmlspecialchars($booking['duration']) ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Travel Date</label>
                                <p class="text-gray-900">
                                    <?php 
                                    if ($source == 'inquiry') {
                                        echo htmlspecialchars($booking['travel_date']);
                                    } else {
                                        echo date('F d, Y', strtotime($booking['travel_date']));
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Booking Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Participants</label>
                            <p class="text-2xl font-bold text-blue-600"><?= $booking['participants'] ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Total Amount</label>
                            <p class="text-2xl font-bold text-green-600">$<?= number_format($booking['total_amount'], 2) ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Commission</label>
                            <p class="text-2xl font-bold text-purple-600">$<?= number_format($booking['commission_amount'], 2) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Status Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Booking Status</label>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                <?php 
                                switch($booking['status']) {
                                    case 'confirmed': echo 'bg-green-100 text-green-800'; break;
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'paid': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Payment Status</label>
                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                                <?php 
                                switch($booking['payment_status']) {
                                    case 'paid': echo 'bg-green-100 text-green-800'; break;
                                    case 'partial': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'pending': echo 'bg-orange-100 text-orange-800'; break;
                                    case 'refunded': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($booking['payment_status']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Sales Team -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Sales Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Advisor</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['advisor_name'] ?: 'Direct Booking') ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">MCA</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['mca_name'] ?: 'N/A') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Commission Breakdown -->
                <?php if (!empty($commissions)): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Commission Breakdown</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-50 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Recipient</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Type</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Rate</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Amount</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($commissions as $commission): ?>
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900"><?= htmlspecialchars($commission['recipient_name']) ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-900"><?= ucfirst($commission['commission_type']) ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-900"><?= $commission['commission_rate'] ?>%</td>
                                    <td class="px-4 py-2 text-sm text-gray-900">$<?= number_format($commission['commission_amount'], 2) ?></td>
                                    <td class="px-4 py-2 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            <?php 
                                            switch($commission['status']) {
                                                case 'approved': echo 'bg-green-100 text-green-800'; break;
                                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'paid': echo 'bg-blue-100 text-blue-800'; break;
                                            }
                                            ?>">
                                            <?= ucfirst($commission['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Inquiry Details -->
                <?php if ($source == 'inquiry'): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Inquiry Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if ($booking['categories']): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Tour Categories</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['categories']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($booking['destinations']): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Destinations</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['destinations']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($booking['activities']): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Activities</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['activities']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($booking['group_type']): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Group Type</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['group_type']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($booking['group_size']): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Group Size</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['group_size']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($booking['referral']): ?>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Referral Source</label>
                            <p class="text-gray-900"><?= htmlspecialchars($booking['referral']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Special Requests -->
                <?php if ($booking['special_requests']): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Special Requests</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-900"><?= nl2br(htmlspecialchars($booking['special_requests'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Notes -->
                <?php if ($booking['notes']): ?>
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Internal Notes</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-900"><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="mt-8 flex space-x-4">
                    <?php if ($booking['status'] == 'pending'): ?>
                        <button onclick="confirmBooking()" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                            Confirm Booking
                        </button>
                    <?php endif; ?>
                    <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Print Details
                    </button>
                    <button onclick="window.close()" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function confirmBooking() {
        if (confirm('Confirm this booking?')) {
            fetch('booking-actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({action: 'confirm', booking_id: <?= $booking_id ?>})
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking confirmed successfully!');
                    window.location.reload();
                }
            });
        }
    }
    </script>
</body>
</html>