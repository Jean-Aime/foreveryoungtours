<?php
session_start();
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$mca_id = $_SESSION['user_id'] ?? 1;

// Get MCA's assigned countries
$stmt = $conn->prepare("SELECT country_id FROM mca_assignments WHERE mca_id = ? AND status = 'active'");
$stmt->execute([$mca_id]);
$assigned_country_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($assigned_country_ids)) {
    $country_filter = "WHERE 1=0"; // No tours if no countries assigned
} else {
    $country_filter = "WHERE t.country_id IN (" . implode(',', $assigned_country_ids) . ")";
}

// Get tours in assigned countries
$stmt = $conn->prepare("
    SELECT t.*, c.name as country_name, r.name as region_name,
           (SELECT COUNT(*) FROM bookings b WHERE b.tour_id = t.id) as total_bookings,
           (SELECT SUM(b.total_price) FROM bookings b WHERE b.tour_id = t.id AND b.status IN ('confirmed', 'completed')) as total_revenue
    FROM tours t 
    LEFT JOIN countries c ON t.country_id = c.id 
    LEFT JOIN regions r ON c.region_id = r.id 
    $country_filter AND t.status = 'active'
    ORDER BY t.popularity_score DESC, t.created_at DESC
");
$stmt->execute();
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle share link generation
if ($_POST && isset($_POST['action']) && $_POST['action'] == 'generate_share_link') {
    $tour_id = $_POST['tour_id'];
    $link_code = 'MCA' . $mca_id . 'T' . $tour_id . '_' . uniqid();
    $full_url = "https://foreveryoungtours.com/pages/tour-detail-enhanced.php?id=" . $tour_id . "&ref=" . $link_code;
    
    $stmt = $conn->prepare("INSERT INTO shared_links (tour_id, user_id, link_code, full_url) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_url = VALUES(full_url), is_active = 1");
    $stmt->execute([$tour_id, $mca_id, $link_code, $full_url]);
    
    echo json_encode(['success' => true, 'link' => $full_url, 'code' => $link_code]);
    exit;
}

// Get MCA's shared links performance
$stmt = $conn->prepare("
    SELECT sl.*, t.name as tour_name, t.price 
    FROM shared_links sl 
    JOIN tours t ON sl.tour_id = t.id 
    WHERE sl.user_id = ? 
    ORDER BY sl.created_at DESC
");
$stmt->execute([$mca_id]);
$shared_links = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tours Management - MCA Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">MCA Dashboard</h2>
                <p class="text-sm text-slate-600">Tours Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="countries.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-flag mr-3"></i>My Countries
                </a>
                <a href="tours.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Tours Management
                </a>
                <a href="advisors.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-user-friends mr-3"></i>Advisor Network
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>Bookings
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gradient">Tours Management</h1>
                <p class="text-slate-600">Manage and share tours in your assigned countries</p>
            </div>

            <!-- Tours Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                <?php foreach ($tours as $tour): ?>
                <div class="nextcloud-card overflow-hidden">
                    <div class="h-48 bg-cover bg-center relative" style="background-image: url('<?php echo htmlspecialchars($tour['image_url'] ?: '../assets/images/default-tour.jpg'); ?>');">
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        <div class="absolute top-4 right-4">
                            <?php if ($tour['featured']): ?>
                            <span class="bg-golden-500 text-black px-2 py-1 rounded text-xs font-bold">Featured</span>
                            <?php endif; ?>
                        </div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="font-bold text-lg"><?php echo htmlspecialchars($tour['name']); ?></h3>
                            <p class="text-sm opacity-90"><?php echo htmlspecialchars($tour['country_name']); ?></p>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-golden-600">$<?php echo number_format($tour['price']); ?></span>
                            <span class="text-sm text-slate-600"><?php echo $tour['duration_days']; ?> days</span>
                        </div>
                        
                        <p class="text-slate-600 text-sm mb-4"><?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <span class="text-slate-500">Bookings:</span>
                                <span class="font-semibold"><?php echo $tour['total_bookings']; ?></span>
                            </div>
                            <div>
                                <span class="text-slate-500">Revenue:</span>
                                <span class="font-semibold">$<?php echo number_format($tour['total_revenue'] ?: 0); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-500">Category:</span>
                                <span class="font-semibold"><?php echo ucfirst($tour['category']); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-500">Difficulty:</span>
                                <span class="font-semibold"><?php echo ucfirst($tour['difficulty_level'] ?: 'Moderate'); ?></span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button onclick="viewTour(<?php echo $tour['id']; ?>)" class="flex-1 btn-secondary py-2 rounded text-sm">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </button>
                            <button onclick="generateShareLink(<?php echo $tour['id']; ?>, '<?php echo addslashes($tour['name']); ?>')" class="flex-1 btn-primary py-2 rounded text-sm">
                                <i class="fas fa-share mr-1"></i>Share Tour
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Shared Links Performance -->
            <?php if (!empty($shared_links)): ?>
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-4">Shared Links Performance</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-3">Tour</th>
                                <th class="text-left p-3">Share Link</th>
                                <th class="text-left p-3">Clicks</th>
                                <th class="text-left p-3">Conversions</th>
                                <th class="text-left p-3">Revenue</th>
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
                                    <span class="font-semibold text-golden-600">$<?php echo number_format($link['conversions'] * $link['price']); ?></span>
                                </td>
                                <td class="p-3">
                                    <div class="flex gap-2">
                                        <button onclick="shareViaWhatsApp('<?php echo htmlspecialchars($link['full_url']); ?>', '<?php echo addslashes($link['tour_name']); ?>')" class="text-green-600 hover:text-green-800">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>
                                        <button onclick="shareViaEmail('<?php echo htmlspecialchars($link['full_url']); ?>', '<?php echo addslashes($link['tour_name']); ?>')" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-envelope"></i>
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
                    <h3 class="text-xl font-bold text-gradient">Share Tour</h3>
                    <button onclick="closeShareModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-sm text-slate-600 mt-2">Tour: <span id="shareTourName"></span></p>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Share Link</label>
                    <div class="flex">
                        <input type="text" id="shareLink" readonly class="flex-1 border border-slate-300 rounded-l-lg px-4 py-2 bg-slate-50">
                        <button onclick="copyShareLink()" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
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
                    <p class="text-xs text-slate-500">You'll earn commission on bookings made through your link</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentShareLink = '';
        let currentTourName = '';

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
            const message = encodeURIComponent(`Check out this amazing African tour: ${currentTourName}\n\n${currentShareLink}`);
            window.open(`https://wa.me/?text=${message}`, '_blank');
        }

        function shareViaEmailModal() {
            const subject = encodeURIComponent(`Amazing African Tour: ${currentTourName}`);
            const body = encodeURIComponent(`Hi,\n\nI wanted to share this incredible African tour with you:\n\n${currentTourName}\n\n${currentShareLink}\n\nBest regards`);
            window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
        }

        function shareViaSMSModal() {
            const message = encodeURIComponent(`Check out this amazing African tour: ${currentTourName} ${currentShareLink}`);
            window.open(`sms:?body=${message}`, '_blank');
        }

        function shareViaWhatsApp(url, tourName) {
            const message = encodeURIComponent(`Check out this amazing African tour: ${tourName}\n\n${url}`);
            window.open(`https://wa.me/?text=${message}`, '_blank');
        }

        function shareViaEmail(url, tourName) {
            const subject = encodeURIComponent(`Amazing African Tour: ${tourName}`);
            const body = encodeURIComponent(`Hi,\n\nI wanted to share this incredible African tour with you:\n\n${tourName}\n\n${url}\n\nBest regards`);
            window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
        }
    </script>
</body>
</html>