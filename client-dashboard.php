<?php
session_start();
require_once 'config/database.php';
require_once 'subdomain-handler.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user bookings with subdomain filtering
$where_clause = "";
$params = [$user_id];

if (defined('CURRENT_COUNTRY_ID')) {
    $where_clause = "AND t.country_id = ?";
    $params[] = CURRENT_COUNTRY_ID;
} elseif (isset($_SESSION['continent_filter'])) {
    $where_clause = "AND c.continent = ?";
    $params[] = $_SESSION['continent_filter'];
}

$stmt = $pdo->prepare("
    SELECT b.*, t.title, t.main_image, c.name as country_name, c.flag_url
    FROM bookings b 
    JOIN tours t ON b.tour_id = t.id 
    JOIN countries c ON t.country_id = c.id 
    WHERE b.user_id = ? $where_clause
    ORDER BY b.created_at DESC
");
$stmt->execute($params);
$bookings = $stmt->fetchAll();

// Get user profile
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <?php if (defined('CURRENT_COUNTRY_NAME')): ?>
                    <img src="<?= htmlspecialchars($country['flag_url'] ?? '') ?>" alt="<?= CURRENT_COUNTRY_NAME ?>" class="w-8 h-6 mr-3">
                    <h1 class="text-2xl font-bold"><?= CURRENT_COUNTRY_NAME ?> Dashboard</h1>
                    <?php else: ?>
                    <h1 class="text-2xl font-bold">My Dashboard</h1>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Welcome, <?= htmlspecialchars($user['name']) ?></span>
                    <a href="/logout.php" class="text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-user text-2xl text-gray-600"></i>
                        </div>
                        <h3 class="font-bold text-lg"><?= htmlspecialchars($user['name']) ?></h3>
                        <p class="text-gray-600 text-sm"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                    <nav class="space-y-2">
                        <a href="#bookings" class="block px-4 py-2 bg-blue-50 text-blue-600 rounded">My Bookings</a>
                        <a href="#profile" class="block px-4 py-2 hover:bg-gray-50 rounded">Profile</a>
                        <a href="#preferences" class="block px-4 py-2 hover:bg-gray-50 rounded">Preferences</a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-3">
                <!-- Stats Cards -->
                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-600">Total Bookings</h3>
                        <p class="text-3xl font-bold text-blue-600"><?= count($bookings) ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-600">Confirmed</h3>
                        <p class="text-3xl font-bold text-green-600"><?= count(array_filter($bookings, fn($b) => $b['status'] == 'confirmed')) ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-600">Pending</h3>
                        <p class="text-3xl font-bold text-yellow-600"><?= count(array_filter($bookings, fn($b) => $b['status'] == 'pending')) ?></p>
                    </div>
                </div>

                <!-- Bookings List -->
                <div class="bg-white rounded-lg shadow" id="bookings">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-bold">My Bookings</h2>
                    </div>
                    <div class="divide-y">
                        <?php foreach($bookings as $booking): ?>
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex">
                                    <img src="<?= htmlspecialchars($booking['main_image']) ?>" alt="<?= htmlspecialchars($booking['title']) ?>" class="w-20 h-20 object-cover rounded-lg mr-4">
                                    <div>
                                        <div class="flex items-center mb-2">
                                            <img src="<?= htmlspecialchars($booking['flag_url']) ?>" alt="<?= htmlspecialchars($booking['country_name']) ?>" class="w-6 h-4 mr-2">
                                            <span class="text-sm text-gray-600"><?= htmlspecialchars($booking['country_name']) ?></span>
                                        </div>
                                        <h3 class="font-bold text-lg mb-1"><?= htmlspecialchars($booking['title']) ?></h3>
                                        <p class="text-gray-600 text-sm mb-2">Travel Date: <?= date('F j, Y', strtotime($booking['travel_date'])) ?></p>
                                        <p class="text-gray-600 text-sm">Travelers: <?= $booking['number_of_travelers'] ?> people</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-green-600 mb-2">$<?= number_format($booking['total_amount']) ?></p>
                                    <span class="px-3 py-1 bg-<?= $booking['status'] == 'confirmed' ? 'green' : ($booking['status'] == 'pending' ? 'yellow' : 'red') ?>-100 text-<?= $booking['status'] == 'confirmed' ? 'green' : ($booking['status'] == 'pending' ? 'yellow' : 'red') ?>-800 rounded-full text-sm font-medium">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($bookings)): ?>
                        <div class="p-12 text-center">
                            <i class="fas fa-suitcase text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-600 mb-2">No bookings yet</h3>
                            <p class="text-gray-500 mb-4">Start exploring amazing destinations!</p>
                            <a href="/" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Browse Tours</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>