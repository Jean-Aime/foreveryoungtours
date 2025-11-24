<?php
require_once '../config/database.php';
require_once '../includes/client-portal-functions.php';

// Get portal code from URL
$portalCode = $_GET['code'] ?? basename(dirname($_SERVER['REQUEST_URI']));

if (empty($portalCode)) {
    die('Invalid portal link');
}

// Get portal details
$portal = getPortalDetails($pdo, $portalCode);

if (!$portal) {
    die('Portal not found or expired');
}

// Check if expired
if ($portal['ownership_status'] !== 'active') {
    die('This portal has expired. Please contact us.');
}

// Log portal view
logPortalActivity($pdo, $portalCode, 'view');

// Update view count
$stmt = $pdo->prepare("UPDATE client_registry SET portal_views = portal_views + 1 WHERE portal_code = ?");
$stmt->execute([$portalCode]);

// Get tours for this portal
$tours = getPortalTours($pdo, $portalCode);

// Get messages
$stmt = $pdo->prepare("SELECT * FROM portal_messages WHERE portal_code = ? ORDER BY created_at ASC");
$stmt->execute([$portalCode]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Travel Portal - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .tour-card { transition: transform 0.3s; }
        .tour-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-slate-50">

    <!-- Header -->
    <header class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">🌍 Forever Young Tours</h1>
            <p class="text-yellow-100">Your Personal Travel Portal</p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        
        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">
                Welcome, <?= htmlspecialchars($portal['client_name']) ?>! 👋
            </h2>
            <p class="text-slate-600 mb-4">
                Your portal code: <code class="bg-slate-100 px-2 py-1 rounded font-mono"><?= htmlspecialchars($portalCode) ?></code>
            </p>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="font-bold text-blue-900 mb-2">👤 Your Advisor</h3>
                    <p class="text-lg font-semibold"><?= htmlspecialchars($portal['owned_by_name']) ?></p>
                    <p class="text-sm text-slate-600">
                        <i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($portal['owner_email']) ?>
                    </p>
                    <p class="text-sm text-slate-600">
                        <i class="fas fa-phone mr-2"></i><?= htmlspecialchars($portal['owner_phone']) ?>
                    </p>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <h3 class="font-bold text-green-900 mb-2">📋 Your Interest</h3>
                    <p class="text-slate-700"><?= htmlspecialchars($portal['client_interest'] ?: 'African Tours') ?></p>
                    <p class="text-sm text-slate-600 mt-2">
                        <i class="fas fa-calendar mr-2"></i>Created: <?= date('M d, Y', strtotime($portal['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Tours Selected For You -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
                📦 Tours Selected For You
            </h2>
            
            <?php if (empty($tours)): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <p class="text-yellow-800">Your advisor is preparing tour options for you. Check back soon!</p>
            </div>
            <?php else: ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($tours as $tour): ?>
                <div class="tour-card bg-white rounded-lg shadow-lg overflow-hidden">
                    <?php if ($tour['image_url']): ?>
                    <img src="<?= htmlspecialchars($tour['image_url']) ?>" 
                         alt="<?= htmlspecialchars($tour['name']) ?>"
                         class="w-full h-48 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">
                            <?= htmlspecialchars($tour['name']) ?>
                        </h3>
                        <p class="text-slate-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($tour['destination']) ?>
                        </p>
                        <p class="text-slate-600 mb-4">
                            <i class="fas fa-clock mr-2"></i><?= htmlspecialchars($tour['duration']) ?>
                        </p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-slate-600">Price per person</p>
                                <p class="text-2xl font-bold text-yellow-600">
                                    $<?= number_format($tour['custom_price'] ?? $tour['price'], 2) ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="../pages/tour-detail.php?id=<?= $tour['id'] ?>" target="_blank"
                               class="flex-1 bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700">
                                View Details
                            </a>
                            <button onclick="bookTour(<?= $tour['id'] ?>)"
                                    class="flex-1 bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Chat Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
                💬 Chat with Your Advisor
            </h2>
            
            <div class="border rounded-lg p-4 h-64 overflow-y-auto mb-4 bg-slate-50">
                <?php if (empty($messages)): ?>
                <p class="text-slate-500 text-center py-8">No messages yet. Start the conversation!</p>
                <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                <div class="mb-4 <?= $msg['sender_type'] === 'client' ? 'text-right' : '' ?>">
                    <div class="inline-block max-w-[70%] <?= $msg['sender_type'] === 'client' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-900' ?> rounded-lg px-4 py-2">
                        <p class="text-sm font-semibold mb-1"><?= htmlspecialchars($msg['sender_name']) ?></p>
                        <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                        <p class="text-xs opacity-75 mt-1"><?= date('M d, H:i', strtotime($msg['created_at'])) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <form id="chatForm" class="flex gap-2">
                <input type="text" id="messageInput" 
                       placeholder="Type your message..."
                       class="flex-1 border rounded-lg px-4 py-2">
                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700">
                    Send
                </button>
            </form>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-slate-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 Forever Young Tours. All rights reserved.</p>
            <p class="text-sm text-slate-400 mt-2">Portal Code: <?= htmlspecialchars($portalCode) ?></p>
        </div>
    </footer>

    <script>
    const portalCode = '<?= $portalCode ?>';
    
    document.getElementById('chatForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        try {
            const response = await fetch('send-message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    portal_code: portalCode,
                    message: message
                })
            });
            
            if (response.ok) {
                input.value = '';
                location.reload();
            }
        } catch (error) {
            alert('Failed to send message');
        }
    });
    
    function bookTour(tourId) {
        window.location.href = `../pages/tour-booking.php?tour_id=${tourId}&portal=${portalCode}`;
    }
    </script>

</body>
</html>
