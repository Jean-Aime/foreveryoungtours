<?php
session_start();
require_once '../config/database.php';

require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Handle booking operations
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_booking':
                $stmt = $pdo->prepare("INSERT INTO bookings (tour_id, user_id, customer_name, customer_email, customer_phone, travel_date, participants, total_price, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $total_price = $_POST['participants'] * $_POST['tour_price'];
                $stmt->execute([
                    $_POST['tour_id'], $advisor_id, $_POST['customer_name'], 
                    $_POST['customer_email'], $_POST['customer_phone'], 
                    $_POST['travel_date'], $_POST['participants'], 
                    $total_price, $_POST['notes']
                ]);
                break;
            case 'update_status':
                $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$_POST['status'], $_POST['booking_id'], $advisor_id]);
                break;
        }
    }
}

// Get advisor's bookings
$stmt = $pdo->prepare("
    SELECT b.*, t.name as tour_name, t.price as tour_price, c.name as country_name 
    FROM bookings b 
    JOIN tours t ON b.tour_id = t.id 
    LEFT JOIN countries c ON t.country_id = c.id 
    WHERE b.user_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$advisor_id]);
$bookings = $stmt->fetchAll();

// Get available tours for new bookings
$stmt = $pdo->prepare("SELECT t.*, c.name as country_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id WHERE t.status = 'active' ORDER BY t.name");
$stmt->execute();
$tours = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Advisor Dashboard</title>
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
                <p class="text-sm text-slate-600">Sales & Client Management</p>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="bookings.php" class="nav-item active block px-6 py-3">
                    <i class="fas fa-calendar-check mr-3"></i>My Bookings
                </a>
                <a href="clients.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-users mr-3"></i>Client Management
                </a>
                <a href="tours.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-route mr-3"></i>Available Tours
                </a>
                <a href="commissions.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-dollar-sign mr-3"></i>Commissions
                </a>
                <a href="marketing.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-bullhorn mr-3"></i>Marketing Tools
                </a>
                <a href="reports.php" class="nav-item block px-6 py-3">
                    <i class="fas fa-chart-bar mr-3"></i>Sales Reports
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
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gradient">My Bookings</h1>
                    <p class="text-slate-600">Manage your client bookings and create new ones</p>
                </div>
                <button onclick="openBookingModal()" class="btn-primary px-6 py-3 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>New Booking
                </button>
            </div>

            <!-- Booking Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <?php
                $total_bookings = count($bookings);
                $confirmed_bookings = count(array_filter($bookings, fn($b) => $b['status'] == 'confirmed'));
                $pending_bookings = count(array_filter($bookings, fn($b) => $b['status'] == 'pending'));
                $total_revenue = array_sum(array_column(array_filter($bookings, fn($b) => in_array($b['status'], ['confirmed', 'completed'])), 'total_price'));
                ?>
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Bookings</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $total_bookings; ?></p>
                        </div>
                        <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                    </div>
                </div>
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Confirmed</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $confirmed_bookings; ?></p>
                        </div>
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Pending</p>
                            <p class="text-2xl font-bold text-gradient"><?php echo $pending_bookings; ?></p>
                        </div>
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="nextcloud-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600">Total Revenue</p>
                            <p class="text-2xl font-bold text-gradient">$<?php echo number_format($total_revenue); ?></p>
                        </div>
                        <i class="fas fa-dollar-sign text-2xl text-golden-600"></i>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="nextcloud-card overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold">All Bookings</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left p-4">Customer</th>
                                <th class="text-left p-4">Tour</th>
                                <th class="text-left p-4">Travel Date</th>
                                <th class="text-left p-4">Participants</th>
                                <th class="text-left p-4">Amount</th>
                                <th class="text-left p-4">Status</th>
                                <th class="text-left p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                            <tr class="border-b">
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($booking['customer_name']); ?></p>
                                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($booking['customer_email']); ?></p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-semibold"><?php echo htmlspecialchars($booking['tour_name']); ?></p>
                                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($booking['country_name']); ?></p>
                                    </div>
                                </td>
                                <td class="p-4"><?php echo date('M j, Y', strtotime($booking['travel_date'])); ?></td>
                                <td class="p-4"><?php echo $booking['participants']; ?></td>
                                <td class="p-4">$<?php echo number_format($booking['total_price']); ?></td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs <?php 
                                        echo match($booking['status']) {
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <button onclick="viewBooking(<?php echo $booking['id']; ?>)" class="btn-secondary px-3 py-1 rounded text-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($booking['status'] == 'pending'): ?>
                                        <button onclick="updateStatus(<?php echo $booking['id']; ?>, 'confirmed')" class="btn-primary px-3 py-1 rounded text-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Booking Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gradient">Create New Booking</h3>
            </div>
            <form method="POST" class="p-6">
                <input type="hidden" name="action" value="create_booking">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tour</label>
                        <select name="tour_id" id="tour_select" required class="w-full border border-slate-300 rounded-lg px-4 py-2" onchange="updatePrice()">
                            <option value="">Select Tour</option>
                            <?php foreach ($tours as $tour): ?>
                            <option value="<?php echo $tour['id']; ?>" data-price="<?php echo $tour['price']; ?>">
                                <?php echo htmlspecialchars($tour['name'] . ' - ' . $tour['country_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Customer Name</label>
                        <input type="text" name="customer_name" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Customer Email</label>
                        <input type="email" name="customer_email" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Customer Phone</label>
                        <input type="tel" name="customer_phone" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Travel Date</label>
                        <input type="date" name="travel_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Participants</label>
                        <input type="number" name="participants" id="participants" min="1" value="1" required class="w-full border border-slate-300 rounded-lg px-4 py-2" onchange="updatePrice()">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2"></textarea>
                </div>
                <div class="mt-4 p-4 bg-slate-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Total Price:</span>
                        <span id="total_price" class="text-xl font-bold text-golden-600">$0</span>
                    </div>
                </div>
                <input type="hidden" name="tour_price" id="tour_price" value="0">
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeBookingModal()" class="btn-secondary px-6 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Create Booking</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBookingModal() {
            document.getElementById('bookingModal').classList.remove('hidden');
        }
        
        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }
        
        function updatePrice() {
            const tourSelect = document.getElementById('tour_select');
            const participants = document.getElementById('participants').value || 1;
            const selectedOption = tourSelect.options[tourSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            const total = price * participants;
            
            document.getElementById('tour_price').value = price;
            document.getElementById('total_price').textContent = '$' + new Intl.NumberFormat().format(total);
        }
        
        function updateStatus(bookingId, status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="booking_id" value="${bookingId}">
                <input type="hidden" name="status" value="${status}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        
        function viewBooking(bookingId) {
            // Implement booking details view
            alert('Booking details for ID: ' + bookingId);
        }
    </script>
</body>
</html>