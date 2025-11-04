<?php
require_once '../config/database.php';

// For demo, assume MCA ID = 11 (you can add login system later)
$mca_id = 11;

// Get MCA info and assigned countries
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$mca_id]);
$mca_info = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT c.*, ma.assigned_at 
    FROM countries c 
    JOIN mca_assignments ma ON c.id = ma.country_id 
    WHERE ma.mca_id = ? AND ma.status = 'active'
");
$stmt->execute([$mca_id]);
$countries = $stmt->fetchAll();

// Get team advisors
$stmt = $pdo->prepare("
    SELECT u.*, COUNT(b.id) as booking_count, COALESCE(SUM(b.total_amount), 0) as total_sales
    FROM users u
    LEFT JOIN bookings b ON u.id = b.advisor_id
    WHERE u.mca_id = ? AND u.role = 'advisor'
    GROUP BY u.id
");
$stmt->execute([$mca_id]);
$advisors = $stmt->fetchAll();

// Get performance stats
$stmt = $pdo->prepare("
    SELECT 
        COUNT(DISTINCT u.id) as total_advisors,
        COUNT(b.id) as total_bookings,
        COALESCE(SUM(b.total_amount), 0) as total_revenue,
        COALESCE(SUM(c.commission_amount), 0) as total_commissions
    FROM users u
    LEFT JOIN bookings b ON u.id = b.advisor_id
    LEFT JOIN commissions c ON b.id = c.booking_id AND c.user_id = ?
    WHERE u.mca_id = ?
");
$stmt->execute([$mca_id, $mca_id]);
$stats = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCA Dashboard - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">MCA Dashboard - <?= $mca_info['full_name'] ?></h1>
        
        <!-- Performance Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Team Advisors</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $stats['total_advisors'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Bookings</h3>
                <p class="text-3xl font-bold text-green-600"><?= $stats['total_bookings'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Team Revenue</h3>
                <p class="text-3xl font-bold text-purple-600">$<?= number_format($stats['total_revenue'], 2) ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">My Commissions</h3>
                <p class="text-3xl font-bold text-indigo-600">$<?= number_format($stats['total_commissions'], 2) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Assigned Countries -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold">Assigned Countries</h2>
                </div>
                <div class="p-6">
                    <?php foreach ($countries as $country): ?>
                    <div class="flex items-center justify-between py-3 border-b">
                        <div>
                            <h3 class="font-medium"><?= $country['name'] ?></h3>
                            <p class="text-sm text-gray-500">Assigned: <?= date('M d, Y', strtotime($country['assigned_at'])) ?></p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Active</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Team Advisors -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold">Team Advisors</h2>
                </div>
                <div class="p-6">
                    <?php foreach ($advisors as $advisor): ?>
                    <div class="flex items-center justify-between py-3 border-b">
                        <div>
                            <h3 class="font-medium"><?= $advisor['first_name'] . ' ' . $advisor['last_name'] ?></h3>
                            <p class="text-sm text-gray-500"><?= $advisor['email'] ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium"><?= $advisor['booking_count'] ?> bookings</p>
                            <p class="text-sm text-gray-500">$<?= number_format($advisor['total_sales'], 2) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>