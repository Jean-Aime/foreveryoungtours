<?php
require_once 'config/database.php';
require_once 'includes/client-portal-functions.php';
session_start();

// Check if client is logged in
if (!isset($_SESSION['is_client']) || !isset($_SESSION['client_portal_code'])) {
    header('Location: portal-login.php');
    exit;
}

$portalCode = $_SESSION['client_portal_code'];

// Get portal details
$portal = getPortalDetails($pdo, $portalCode);
$tours = getPortalTours($pdo, $portalCode);

// Get client bookings
$stmt = $pdo->prepare("
    SELECT b.*, t.name as tour_name, t.destination
    FROM bookings b
    JOIN tours t ON b.tour_id = t.id
    WHERE b.customer_email = ?
    ORDER BY b.created_at DESC
");
$stmt->execute([$_SESSION['client_email']]);
$bookings = $stmt->fetchAll();

// Get messages
$stmt = $pdo->prepare("SELECT * FROM portal_messages WHERE portal_code = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$portalCode]);
$messages = $stmt->fetchAll();

// Statistics
$total_bookings = count($bookings);
$confirmed_bookings = count(array_filter($bookings, fn($b) => $b['status'] == 'confirmed'));
$total_spent = array_sum(array_column(array_filter($bookings, fn($b) => in_array($b['status'], ['confirmed', 'completed'])), 'total_amount'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Travel Dashboard - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">🌍 Forever Young Tours</h1>
                    <p class="text-blue-100 text-sm">Welcome, <?= htmlspecialchars($_SESSION['client_name']) ?>!</p>
                </div>
                <a href="portal-logout.php" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 font-semibold">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Bookings</p>
                        <p class="text-3xl font-bold text-slate-900"><?= $total_bookings ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-2xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Confirmed</p>
                        <p class="text-3xl font-bold text-slate-900"><?= $confirmed_bookings ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Spent</p>
                        <p class="text-3xl font-bold text-slate-900">$<?= number_format($total_spent) ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-2xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Portal Views</p>
                        <p class="text-3xl font-bold text-slate-900"><?= $portal['portal_views'] ?></p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-eye text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Your Advisor -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">👤 Your Travel Advisor</h2>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold"><?= strtoupper(substr($portal['owned_by_name'], 0, 1)) ?></span>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-slate-900"><?= htmlspecialchars($portal['owned_by_name']) ?></p>
                            <p class="text-sm text-slate-600"><i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($portal['owner_email']) ?></p>
                            <p class="text-sm text-slate-600"><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($portal['owner_phone']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- My Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900">📅 My Bookings</h2>
                    </div>
                    <div class="p-6">
                        <?php if (empty($bookings)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">No bookings yet</p>
                            <a href="#tours" class="text-blue-600 hover:text-blue-800 font-semibold">Browse tours below</a>
                        </div>
                        <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($bookings as $booking): ?>
                            <div class="border border-slate-200 rounded-lg p-4 hover:bg-slate-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-bold text-slate-900"><?= htmlspecialchars($booking['tour_name']) ?></h3>
                                        <p class="text-sm text-slate-600"><?= htmlspecialchars($booking['destination']) ?></p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        <?= match($booking['status']) {
                                            'confirmed' => 'bg-green-100 text-green-700',
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            'completed' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-slate-100 text-slate-700'
                                        } ?>">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-slate-600">Travel Date</p>
                                        <p class="font-semibold"><?= date('M j, Y', strtotime($booking['travel_date'])) ?></p>
                                    </div>
                                    <div>
                                        <p class="text-slate-600">Participants</p>
                                        <p class="font-semibold"><?= $booking['participants'] ?> people</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-600">Amount</p>
                                        <p class="font-semibold text-green-600">$<?= number_format($booking['total_amount']) ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Available Tours -->
                <div id="tours" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900">🗺️ Tours Selected For You</h2>
                    </div>
                    <div class="p-6">
                        <?php if (empty($tours)): ?>
                        <p class="text-slate-500 text-center py-8">Your advisor is preparing tour options for you</p>
                        <?php else: ?>
                        <div class="grid md:grid-cols-2 gap-6">
                            <?php foreach ($tours as $tour): ?>
                            <div class="border border-slate-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                                <?php if ($tour['image_url']): ?>
                                <img src="<?= htmlspecialchars($tour['image_url']) ?>" class="w-full h-40 object-cover" alt="<?= htmlspecialchars($tour['name']) ?>">
                                <?php endif; ?>
                                <div class="p-4">
                                    <h3 class="font-bold text-slate-900 mb-2"><?= htmlspecialchars($tour['name']) ?></h3>
                                    <p class="text-sm text-slate-600 mb-3">
                                        <i class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($tour['destination']) ?>
                                    </p>
                                    <div class="flex justify-between items-center">
                                        <p class="text-2xl font-bold text-blue-600">$<?= number_format($tour['price']) ?></p>
                                        <a href="pages/tour-booking.php?tour_id=<?= $tour['id'] ?>&portal=<?= $portalCode ?>" 
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-semibold">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="#tours" class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 font-semibold">
                            <i class="fas fa-search mr-2"></i>Browse Tours
                        </a>
                        <a href="#messages" class="block w-full bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 font-semibold">
                            <i class="fas fa-comments mr-2"></i>Message Advisor
                        </a>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div id="messages" class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4">💬 Recent Messages</h3>
                    <?php if (empty($messages)): ?>
                    <p class="text-slate-500 text-sm">No messages yet</p>
                    <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($messages as $msg): ?>
                        <div class="text-sm">
                            <p class="font-semibold text-slate-900"><?= htmlspecialchars($msg['sender_name']) ?></p>
                            <p class="text-slate-600"><?= htmlspecialchars(substr($msg['message'], 0, 50)) ?>...</p>
                            <p class="text-xs text-slate-400"><?= date('M j, H:i', strtotime($msg['created_at'])) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <a href="portal.php?code=<?= $portalCode ?>" class="block mt-4 text-center text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        View All Messages →
                    </a>
                </div>

                <!-- Portal Info -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                    <h3 class="font-bold text-slate-900 mb-3">📋 Portal Info</h3>
                    <div class="space-y-2 text-sm">
                        <div>
                            <p class="text-slate-600">Portal Code</p>
                            <p class="font-mono font-bold text-blue-700"><?= $portalCode ?></p>
                        </div>
                        <div>
                            <p class="text-slate-600">Created</p>
                            <p class="font-semibold"><?= date('M j, Y', strtotime($portal['created_at'])) ?></p>
                        </div>
                        <div>
                            <p class="text-slate-600">Status</p>
                            <p class="font-semibold text-green-600">Active</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>
</html>
