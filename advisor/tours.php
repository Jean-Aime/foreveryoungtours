<?php
session_start();
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$advisor_id = $_SESSION['user_id'] ?? 1;

// Get all available tours
$stmt = $conn->prepare("
    SELECT t.*, c.name as country_name, r.name as region_name,
           (SELECT COUNT(*) FROM bookings b WHERE b.tour_id = t.id AND b.user_id = ?) as my_bookings,
           (SELECT SUM(b.total_price) FROM bookings b WHERE b.tour_id = t.id AND b.user_id = ? AND b.status IN ('confirmed', 'completed')) as my_revenue
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id 
    WHERE t.status = 'active'
    ORDER BY t.featured DESC, t.popularity_score DESC, t.created_at DESC
");
$stmt->execute([$advisor_id, $advisor_id]);
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle share link generation
if ($_POST && isset($_POST['action']) && $_POST['action'] == 'generate_share_link') {
    $tour_id = $_POST['tour_id'];
    $link_code = 'ADV' . $advisor_id . 'T' . $tour_id . '_' . uniqid();
    $full_url = "https://foreveryoungtours.com/pages/tour-detail-enhanced.php?id=" . $tour_id . "&ref=" . $link_code;
    
    $stmt = $conn->prepare("INSERT INTO shared_links (tour_id, user_id, link_code, full_url) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_url = VALUES(full_url), is_active = 1");
    $stmt->execute([$tour_id, $advisor_id, $link_code, $full_url]);
    
    echo json_encode(['success' => true, 'link' => $full_url, 'code' => $link_code]);
    exit;
}

// Get advisor's shared links performance
$stmt = $conn->prepare("
    SELECT sl.*, t.name as tour_name, t.price 
    FROM shared_links sl 
    JOIN tours t ON sl.tour_id = t.id 
    WHERE sl.user_id = ? 
    ORDER BY sl.created_at DESC
");
$stmt->execute([$advisor_id]);
$shared_links = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filter options
$category_filter = $_GET['category'] ?? '';
$country_filter = $_GET['country'] ?? '';
$price_filter = $_GET['price'] ?? '';

// Apply filters
$filtered_tours = $tours;
if ($category_filter) {
    $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['category'] == $category_filter);
}
if ($country_filter) {
    $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['country_id'] == $country_filter);
}
if ($price_filter) {
    switch ($price_filter) {
        case 'under_1000':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['price'] < 1000);
            break;
        case '1000_2000':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['price'] >= 1000 && $tour['price'] <= 2000);
            break;
        case 'over_2000':
            $filtered_tours = array_filter($filtered_tours, fn($tour) => $tour['price'] > 2000);
            break;
    }
}

// Get unique countries and categories for filters
$countries = [];
$categories = [];
foreach ($tours as $tour) {
    if (!in_array($tour['country_name'], array_column($countries, 'name'))) {
        $countries[] = ['id' => $tour['country_id'], 'name' => $tour['country_name']];
    }
    if (!in_array($tour['category'], $categories)) {
        $categories[] = $tour['category'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Tours - Advisor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Advisor Dashboard</h2>
                <p class="text-sm text-slate-600">Available Tours</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>My Bookings
                </a>
                <a href="clients.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-users mr-3"></i>Client Management
                </a>
                <a href="tours.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Available Tours
                </a>
                <a href="commissions.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-dollar-sign mr-3"></i>Commissions
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gradient">Available Tours</h1>
                <p class="text-slate-600">Browse and share amazing African tours with your clients</p>
            </div>

            <!-- Filters -->
            <div class="nextcloud-card p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Filter Tours</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                        <select onchange="applyFilters()" id="categoryFilter" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category; ?>" <?php echo $category_filter == $category ? 'selected' : ''; ?>>
                                <?php echo ucfirst($category); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                        <select onchange="applyFilters()" id="countryFilter" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">All Countries</option>
                            <?php foreach ($countries as $country): ?>
                            <option value="<?php echo $country['id']; ?>" <?php echo $country_filter == $country['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($country['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Price Range</label>
                        <select onchange="applyFilters()" id="priceFilter" class="w-full border border-slate-300 rounded-lg px-4 py-2">
                            <option value="">All Prices</option>
                            <option value="under_1000" <?php echo $price_filter == 'under_1000' ? 'selected' : ''; ?>>Under $1,000</option>
                            <option value="1000_2000" <?php echo $price_filter == '1000_2000' ? 'selected' : ''; ?>>$1,000 - $2,000</option>
                            <option value="over_2000" <?php echo $price_filter == 'over_2000' ? 'selected' : ''; ?>>Over $2,000</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="clearFilters()" class="w-full btn-secondary py-2 rounded-lg">
                            <i class="fas fa-times mr-2"></i>Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tours Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                <?php foreach ($filtered_tours as $tour): ?>
                <div class="nextcloud-card overflow-hidden">
                    <div class="h-48 bg-cover bg-center relative" style="background-image: url('<?php echo htmlspecialchars($tour['image_url'] ?: '../assets/images/default-tour.jpg'); ?>');">
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        <div class="absolute top-4 right-4 flex gap-2">
                            <?php if ($tour['featured']): ?>
                            <span class="bg-golden-500 text-black px-2 py-1 rounded text-xs font-bold">Featured</span>
                            <?php endif; ?>
                            <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-bold"><?php echo ucfirst($tour['category']); ?></span>
                        </div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="font-bold text-lg"><?php echo htmlspecialchars($tour['name']); ?></h3>
                            <p class="text-sm opacity-90"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-golden-600">$<?php echo number_format($tour['price']); ?></span>
                            <div class="text-right">
                                <span class="text-sm text-slate-600"><?php echo $tour['duration_days']; ?> days</span>
                                <?php if ($tour['difficulty_level']): ?>
                                <br><span class="text-xs text-slate-500"><?php echo ucfirst($tour['difficulty_level']); ?> level</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <p class="text-slate-600 text-sm mb-4"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...</p>
                        
                        <?php if ($tour['my_bookings'] > 0): ?>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-green-700">Your Sales:</span>
                                <span class="font-semibold text-green-800"><?php echo $tour['my_bookings']; ?> bookings</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-green-700">Revenue:</span>
                                <span class="font-semibold text-green-800">$<?php echo number_format($tour['my_revenue'] ?: 0); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <span class="text-slate-500">Group Size:</span>
                                <span class="font-semibold"><?php echo $tour['min_participants']; ?>-<?php echo $tour['max_participants']; ?></span>
                            </div>
                            <div>
                                <span class="text-slate-500">Commission:</span>
                                <span class="font-semibold text-green-600"><?php echo $tour['advisor_commission_rate'] ?: 10; ?>%</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button onclick="viewTour(<?php echo $tour['id']; ?>)" class="flex-1 btn-secondary py-2 rounded text-sm">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </button>
                            <button onclick="generateShareLink(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>')" class="flex-1 btn-primary py-2 rounded text-sm">
                                <i class="fas fa-share mr-1"></i>Share & Earn
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- My Shared Links Performance -->
            <?php if (!empty($shared_links)): ?>
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-4">My Shared Links Performance</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-3">Tour</th>
                                <th class="text-left p-3">Share Link</th>
                                <th class="text-left p-3">Clicks</th>
                                <th class="text-left p-3">Conversions</th>
                                <th class="text-left p-3">Commission Earned</th>
                                <th class="text-left p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shared_links as $link): ?>
                            <tr class="border-b">
                                <td class="p-3">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($link['tour_name']); ?></p>
                                        <p class="text-sm text-slate-600">$<?php echo number_format($link['price']); ?> per person</p>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center">
                                        <input type="text" value="<?php echo htmlspecialchars($link['full_url']); ?>" readonly class="text-xs bg-slate-50 px-2 py-1 rounded mr-2 flex-1">
                                        <button onclick="copyLink('<?php echo htmlspecialchars($link['full_url']); ?>')" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <span class="font-semibold"><?php echo $link['clicks']; ?></span>
                                </td>
                                <td class="p-3">
                                    <span class="font-semibold text-green-600"><?php echo $link['conversions']; ?></span>
                                </td>
                                <td class="p-3">
                                    <span class="font-semibold text-golden-600">$<?php echo number_format($link['conversions'] * $link['price'] * 0.1); ?></span>
                                </td>
                                <td class="p-3">
                                    <div class="flex gap-2">
                                        <button onclick="shareViaWhatsApp('<?php echo htmlspecialchars($link['full_url']); ?>', '<?php echo addslashes($link['tour_name']); ?>')" class="text-green-600 hover:text-green-800">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>
                                        <button onclick="shareViaEmail('<?php echo htmlspecialchars($link['full_url']); ?>', '<?php echo addslashes($link['tour_name']); ?>')" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        <button onclick="shareViaSMS('<?php echo htmlspecialchars($link['full_url']); ?>', '<?php echo addslashes($link['tour_name']); ?>')" class="text-purple-600 hover:text-purple-800">
                                            <i class="fas fa-sms"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Share Link Modal -->
    <div id="shareLinkModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gradient">Share Tour & Earn Commission</h3>
                    <button onclick="closeShareModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-sm text-slate-600 mt-2">Tour: <span id="shareTourName"></span></p>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Personalized Share Link</label>
                    <div class="flex">
                        <input type="text" id="shareLink" readonly class="flex-1 border border-slate-300 rounded-l-lg px-4 py-2 bg-slate-50 text-sm">
                        <button onclick="copyShareLink()" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <div class="bg-golden-50 border border-golden-200 rounded-lg p-4 mb-4">
                    <h4 class="font-semibold text-golden-800 mb-2">💰 Earn Commission</h4>
                    <p class="text-sm text-golden-700">You'll earn 10% commission on every booking made through your link!</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-slate-600 mb-3">Share via:</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="shareViaWhatsAppModal()" class="bg-green-500 text-white py-2 rounded hover:bg-green-600">
                            <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                        </button>
                        <button onclick="shareViaEmailModal()" class="bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-envelope mr-1"></i>Email
                        </button>
                        <button onclick="shareViaSMSModal()" class="bg-purple-600 text-white py-2 rounded hover:bg-purple-700">
                            <i class="fas fa-sms mr-1"></i>SMS
                        </button>
                    </div>
                </div>
                
                <div class="text-center">
                    <p class="text-xs text-slate-500">Track your link performance in the table below</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentShareLink = '';
        let currentTourName = '';

        function applyFilters() {
            const category = document.getElementById('categoryFilter').value;
            const country = document.getElementById('countryFilter').value;
            const price = document.getElementById('priceFilter').value;
            
            const params = new URLSearchParams();
            if (category) params.append('category', category);
            if (country) params.append('country', country);
            if (price) params.append('price', price);
            
            window.location.href = 'tours.php?' + params.toString();
        }

        function clearFilters() {
            window.location.href = 'tours.php';
        }

        function viewTour(tourId) {
            window.open('../pages/tour-detail-enhanced.php?id=' + tourId, '_blank');
        }

        function generateShareLink(tourId, tourName) {
            const formData = new FormData();
            formData.append('action', 'generate_share_link');
            formData.append('tour_id', tourId);

            fetch('tours.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentShareLink = data.link;
                    currentTourName = tourName;
                    document.getElementById('shareTourName').textContent = tourName;
                    document.getElementById('shareLink').value = data.link;
                    document.getElementById('shareLinkModal').classList.remove('hidden');
                } else {
                    alert('Error generating share link');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error generating share link');
            });
        }

        function closeShareModal() {
            document.getElementById('shareLinkModal').classList.add('hidden');
        }

        function copyShareLink() {
            const linkInput = document.getElementById('shareLink');
            linkInput.select();
            document.execCommand('copy');
            alert('Share link copied to clipboard!');
        }

        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            });
        }

        function shareViaWhatsAppModal() {
            const message = encodeURIComponent(`🌍 Discover Amazing Africa! 🌍\n\nCheck out this incredible tour: ${currentTourName}\n\n${currentShareLink}\n\nBook now for an unforgettable African adventure!`);
            window.open(`https://wa.me/?text=${message}`, '_blank');
        }

        function shareViaEmailModal() {
            const subject = encodeURIComponent(`🌍 Amazing African Adventure: ${currentTourName}`);
            const body = encodeURIComponent(`Hi there!\n\nI wanted to share this incredible African tour with you:\n\n${currentTourName}\n\n${currentShareLink}\n\nThis looks like an amazing adventure that I thought you might be interested in. The tour includes everything you need for an unforgettable African experience!\n\nLet me know if you have any questions.\n\nBest regards`);
            window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
        }

        function shareViaSMSModal() {
            const message = encodeURIComponent(`🌍 Amazing African tour: ${currentTourName} ${currentShareLink}`);
            window.open(`sms:?body=${message}`, '_blank');
        }

        function shareViaWhatsApp(url, tourName) {
            const message = encodeURIComponent(`🌍 Discover Amazing Africa! 🌍\n\nCheck out this incredible tour: ${tourName}\n\n${url}\n\nBook now for an unforgettable African adventure!`);
            window.open(`https://wa.me/?text=${message}`, '_blank');
        }

        function shareViaEmail(url, tourName) {
            const subject = encodeURIComponent(`🌍 Amazing African Adventure: ${tourName}`);
            const body = encodeURIComponent(`Hi there!\n\nI wanted to share this incredible African tour with you:\n\n${tourName}\n\n${url}\n\nThis looks like an amazing adventure that I thought you might be interested in!\n\nBest regards`);
            window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
        }

        function shareViaSMS(url, tourName) {
            const message = encodeURIComponent(`🌍 Amazing African tour: ${tourName} ${url}`);
            window.open(`sms:?body=${message}`, '_blank');
        }
    </script>
</body>
</html>