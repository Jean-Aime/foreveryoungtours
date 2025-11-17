<?php

require_once 'config.php';
session_start();
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$advisor_id = $_SESSION['user_id'] ?? 1;

// Get advisor's team (L2 and L3)
$stmt = $conn->prepare("
    SELECT u.*, 
           (SELECT COUNT(*) FROM users WHERE upline_id = u.id) as team_count,
           (SELECT COUNT(*) FROM bookings WHERE referred_by = u.id) as total_bookings,
           (SELECT SUM(total_price) FROM bookings WHERE referred_by = u.id AND status IN ('confirmed', 'completed')) as total_sales
    FROM users u 
    WHERE u.upline_id = ? OR u.upline_id IN (SELECT id FROM users WHERE upline_id = ?)
    ORDER BY u.level, u.created_at DESC
");
$stmt->execute([$advisor_id, $advisor_id]);
$team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get team statistics
$l2_count = count(array_filter($team_members, fn($m) => $m['level'] == 2));
$l3_count = count(array_filter($team_members, fn($m) => $m['level'] == 3));
$total_team_sales = array_sum(array_column($team_members, 'total_sales'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Team - Advisor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
</head>
<body class="bg-cream">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-bold text-gradient">Advisor Dashboard</h2>
                <p class="text-sm text-slate-600">Team Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-home mr-3"></i>Overview
                </a>
                <a href="team.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-users mr-3"></i>My Team
                </a>
                <a href="tours.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Available Tours
                </a>
                <a href="../auth/logout.php" class="nav-item block px-6 py-3 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold text-gradient mb-8">My Team</h1>

            <!-- Team Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-2">Level 2 Team</h3>
                    <p class="text-3xl font-bold text-blue-600"><?php echo $l2_count; ?></p>
                </div>
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-2">Level 3 Team</h3>
                    <p class="text-3xl font-bold text-green-600"><?php echo $l3_count; ?></p>
                </div>
                <div class="nextcloud-card p-6">
                    <h3 class="text-lg font-bold mb-2">Team Sales</h3>
                    <p class="text-3xl font-bold text-golden-600">$<?php echo number_format($total_team_sales); ?></p>
                </div>
            </div>

            <!-- Team Members -->
            <div class="nextcloud-card p-6">
                <h2 class="text-xl font-bold mb-4">Team Members</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-3">Name</th>
                                <th class="text-left p-3">Level</th>
                                <th class="text-left p-3">Team Size</th>
                                <th class="text-left p-3">Bookings</th>
                                <th class="text-left p-3">Sales</th>
                                <th class="text-left p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($team_members as $member): ?>
                            <tr class="border-b">
                                <td class="p-3">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($member['full_name']); ?></p>
                                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($member['email']); ?></p>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium <?php 
                                        echo $member['level'] == 2 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; 
                                    ?>">
                                        Level <?php echo $member['level']; ?>
                                    </span>
                                </td>
                                <td class="p-3"><?php echo $member['team_count']; ?></td>
                                <td class="p-3"><?php echo $member['total_bookings']; ?></td>
                                <td class="p-3">$<?php echo number_format($member['total_sales'] ?: 0); ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs <?php 
                                        echo $member['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; 
                                    ?>">
                                        <?php echo ucfirst($member['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>