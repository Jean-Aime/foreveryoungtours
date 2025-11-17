<?php

require_once 'config.php';
session_start();
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$mca_id = $_SESSION['user_id'] ?? 1;

// Get MCA's assigned countries with statistics
$stmt = $conn->prepare("
    SELECT c.*, r.name as region_name,
           COUNT(DISTINCT t.id) as tour_count,
           COUNT(DISTINCT b.id) as booking_count,
           COALESCE(SUM(b.total_price), 0) as total_revenue
    FROM countries c 
    JOIN regions r ON c.region_id = r.id 
    JOIN mca_assignments ma ON c.id = ma.country_id 
    LEFT JOIN tours t ON c.id = t.country_id AND t.status = 'active'
    LEFT JOIN bookings b ON t.id = b.tour_id AND b.status IN ('confirmed', 'completed')
    WHERE ma.mca_id = ? AND ma.status = 'active'
    GROUP BY c.id
    ORDER BY r.name, c.name
");
$stmt->execute([$mca_id]);
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Countries - MCA Dashboard</title>
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
                <p class="text-sm text-slate-600">Country Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="countries.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-flag mr-3"></i>My Countries
                </a>
                <a href="tours.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Tours Management
                </a>
                <a href="advisors.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-user-friends mr-3"></i>Advisor Network
                </a>
                <a href="bookings.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>Bookings
                </a>
                <a href="commissions.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-dollar-sign mr-3"></i>Commissions
                </a>
                <a href="reports.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-chart-line mr-3"></i>Performance Reports
                </a>
                <a href="profile.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-user mr-3"></i>Profile Settings
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gradient">My Assigned Countries</h1>
                <p class="text-slate-600">Manage tours and performance for your assigned countries</p>
            </div>

            <!-- Countries Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($countries as $country): ?>
                <div class="nextcloud-card overflow-hidden">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('<?php echo htmlspecialchars($country['image_url']); ?>');">
                        <div class="h-full bg-black bg-opacity-40 flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold"><?php echo htmlspecialchars($country['name']); ?></h3>
                                <p class="text-sm opacity-90"><?php echo htmlspecialchars($country['region_name']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600"><?php echo $country['tour_count']; ?></p>
                                <p class="text-xs text-slate-600">Tours</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600"><?php echo $country['booking_count']; ?></p>
                                <p class="text-xs text-slate-600">Bookings</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-golden-600">$<?php echo number_format($country['total_revenue']); ?></p>
                                <p class="text-xs text-slate-600">Revenue</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="tours.php?country=<?php echo $country['id']; ?>" class="btn-primary flex-1 text-center py-2 rounded-lg text-sm">
                                <i class="fas fa-route mr-2"></i>Manage Tours
                            </a>
                            <a href="bookings.php?country=<?php echo $country['id']; ?>" class="btn-secondary flex-1 text-center py-2 rounded-lg text-sm">
                                <i class="fas fa-calendar-check mr-2"></i>View Bookings
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>