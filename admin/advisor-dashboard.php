<?php

require_once 'config.php';
require_once '../config/database.php';

// For demo, assume Advisor ID = 5 (you can add login system later)
$advisor_id = 5;

// Get advisor info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$advisor_id]);
$advisor_info = $stmt->fetch();

// Get advisor's bookings
$stmt = $pdo->prepare("
    SELECT b.*, t.name as tour_name, t.destination
    FROM bookings b
    JOIN tours t ON b.tour_id = t.id
    WHERE b.advisor_id = ?
    ORDER BY b.booking_date DESC
    LIMIT 10
");
$stmt->execute([$advisor_id]);
$bookings = $stmt->fetchAll();

// Get advisor stats
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_bookings,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
        COALESCE(SUM(total_amount), 0) as total_sales,
        COALESCE(SUM(commission_amount), 0) as total_commissions
    FROM bookings 
    WHERE advisor_id = ?
");
$stmt->execute([$advisor_id]);
$stats = $stmt->fetch();

// Get commission details
$stmt = $pdo->prepare("
    SELECT c.*, b.booking_reference, b.customer_name
    FROM commissions c
    JOIN bookings b ON c.booking_id = b.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
    LIMIT 10
");
$stmt->execute([$advisor_id]);
$commissions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Dashboard - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Advisor Dashboard - <?= $advisor_info['first_name'] . ' ' . $advisor_info['last_name'] ?></h1>
        
        <!-- Performance Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Bookings</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $stats['total_bookings'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Confirmed</h3>
                <p class="text-3xl font-bold text-green-600"><?= $stats['confirmed_bookings'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Sales</h3>
                <p class="text-3xl font-bold text-purple-600">$<?= number_format($stats['total_sales'], 2) ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Commissions</h3>
                <p class="text-3xl font-bold text-indigo-600">$<?= number_format($stats['total_commissions'], 2) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold">Recent Bookings</h2>
                </div>
                <div class="p-6">
                    <?php foreach ($bookings as $booking): ?>
                    <div class="flex items-center justify-between py-3 border-b">
                        <div>
                            <h3 class="font-medium"><?= $booking['booking_reference'] ?></h3>
                            <p class="text-sm text-gray-500"><?= $booking['customer_name'] ?></p>
                            <p class="text-sm text-gray-500"><?= $booking['tour_name'] ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$<?= number_format($booking['total_amount'], 2) ?></p>
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                switch($booking['status']) {
                                    case 'confirmed': echo 'bg-green-100 text-green-800'; break;
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Commission History -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold">Commission History</h2>
                </div>
                <div class="p-6">
                    <?php foreach ($commissions as $commission): ?>
                    <div class="flex items-center justify-between py-3 border-b">
                        <div>
                            <h3 class="font-medium"><?= $commission['booking_reference'] ?></h3>
                            <p class="text-sm text-gray-500"><?= $commission['customer_name'] ?></p>
                            <p class="text-sm text-gray-500"><?= ucfirst($commission['commission_type']) ?> (<?= $commission['commission_rate'] ?>%)</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$<?= number_format($commission['commission_amount'], 2) ?></p>
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                switch($commission['status']) {
                                    case 'approved': echo 'bg-green-100 text-green-800'; break;
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'paid': echo 'bg-blue-100 text-blue-800'; break;
                                }
                                ?>">
                                <?= ucfirst($commission['status']) ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Share Links Section -->
        <div class="bg-white rounded-lg shadow mt-8">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-semibold">Generate Share Links</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <select id="tourSelect" class="border rounded px-3 py-2 flex-1">
                        <option value="">Select a tour to generate share link</option>
                        <?php 
                        $tours = $pdo->query("SELECT id, name FROM tours WHERE status = 'active'")->fetchAll();
                        foreach ($tours as $tour): ?>
                            <option value="<?= $tour['id'] ?>"><?= $tour['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button onclick="generateShareLink()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Generate Link</button>
                </div>
                <div id="shareLink" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Share Link:</label>
                    <div class="flex">
                        <input type="text" id="linkInput" class="border rounded-l px-3 py-2 flex-1" readonly>
                        <button onclick="copyLink()" class="bg-gray-600 text-white px-4 py-2 rounded-r hover:bg-gray-700">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function generateShareLink() {
        const tourId = document.getElementById('tourSelect').value;
        if (!tourId) return;
        
        const link = `${window.location.origin}/tour-details.php?id=${tourId}&ref=ADV<?= $advisor_id ?>`;
        document.getElementById('linkInput').value = link;
        document.getElementById('shareLink').classList.remove('hidden');
    }
    
    function copyLink() {
        const input = document.getElementById('linkInput');
        input.select();
        document.execCommand('copy');
        alert('Link copied to clipboard!');
    }
    </script>
</body>
</html>