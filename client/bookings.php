<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/csrf.php';
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('client');

$client_email = $_SESSION['user_email'] ?? '';

// Handle booking cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    requireCsrf();
    $booking_id = $_POST['booking_id'];
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled', cancelled_date = NOW() WHERE id = ? AND customer_email = ?");
    $stmt->execute([$booking_id, $client_email]);
    header('Location: bookings.php');
    exit;
}

// Get bookings
$stmt = $pdo->prepare("SELECT b.*, t.name as tour_name FROM bookings b LEFT JOIN tours t ON b.tour_id = t.id WHERE b.customer_email = ? ORDER BY b.booking_date DESC");
$stmt->execute([$client_email]);
$bookings = $stmt->fetchAll();

// Get statistics
$stats_stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_bookings,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_bookings,
        COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed_bookings,
        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_bookings
    FROM bookings 
    WHERE customer_email = ?
");
$stats_stmt->execute([$client_email]);
$stats = $stats_stmt->fetch();

$page_title = 'My Bookings';
$page_subtitle = 'Manage Your Tour Bookings';

include 'includes/client-header.php';
?>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #FDF6E3;">
                <i class="fas fa-calendar-check" style="color: #DAA520;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Total Bookings</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo $stats['total_bookings']; ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #fef3c7;">
                <i class="fas fa-clock" style="color: #92400e;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Pending</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo $stats['pending_bookings']; ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #d1fae5;">
                <i class="fas fa-check-circle" style="color: #228B22;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Confirmed</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo $stats['confirmed_bookings']; ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-sm">
        <div class="flex items-center">
            <div class="p-3 rounded-full" style="background: #fee2e2;">
                <i class="fas fa-times-circle" style="color: #991b1b;"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium" style="color: #666;">Cancelled</p>
                <p class="text-2xl font-bold" style="color: #333;"><?php echo $stats['cancelled_bookings']; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <?php if (empty($bookings)): ?>
    <div class="p-12 text-center">
        <i class="fas fa-calendar-times text-6xl mb-4" style="color: #ccc;"></i>
        <h3 class="text-xl font-medium mb-2" style="color: #333;">No bookings yet</h3>
        <p class="mb-6" style="color: #666;">Start your African adventure by booking your first tour</p>
        <a href="tours.php" class="inline-block px-6 py-3 rounded-lg font-semibold transition-colors" style="background: #DAA520; color: #fff;">
            <i class="fas fa-search mr-2"></i>Explore Tours
        </a>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Ref</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tour</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Participants</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($booking['booking_reference']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($booking['tour_name'] ?: 'Tour Booking'); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($booking['customer_name']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('M d, Y', strtotime($booking['travel_date'])); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $booking['participants']; ?> people</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$<?php echo number_format($booking['total_amount'], 2); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php 
                            switch($booking['status']) {
                                case 'confirmed': echo 'bg-green-100 text-green-800'; break;
                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                case 'paid': echo 'bg-blue-100 text-blue-800'; break;
                                case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                default: echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewBooking(<?php echo $booking['id']; ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                        <?php if ($booking['status'] === 'pending'): ?>
                        <form method="POST" class="inline" onsubmit="return confirm('Cancel this booking?')">
                            <?php echo getCsrfField(); ?>
                            <input type="hidden" name="action" value="cancel">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                            <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                        </form>
                        <?php endif; ?>
                    </td>
                    <script>
                    window.bookingData_<?php echo $booking['id']; ?> = <?php echo json_encode($booking); ?>;
                    </script>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Booking Details Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-2xl font-bold text-slate-900">Booking Details</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-6"></div>
    </div>
</div>

<script>
function viewBooking(id) {
    const booking = window['bookingData_' + id];
    if (!booking) return;
    
    const content = `
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Booking Reference</p>
                    <p class="text-lg font-semibold">${booking.booking_reference}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-lg font-semibold capitalize">${booking.status}</p>
                </div>
            </div>
            
            <div class="border-t pt-4">
                <h4 class="font-bold text-lg mb-3">Tour Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tour Name</p>
                        <p class="font-medium">${booking.tour_name || 'Tour Booking'}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Travel Date</p>
                        <p class="font-medium">${new Date(booking.travel_date).toLocaleDateString()}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Participants</p>
                        <p class="font-medium">${booking.participants} people</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-2xl font-bold text-blue-600">$${parseFloat(booking.total_amount).toFixed(2)}</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t pt-4">
                <h4 class="font-bold text-lg mb-3">Booking Preferences</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Accommodation</p>
                        <p class="font-medium capitalize">${booking.accommodation_type || 'Standard'}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Transport</p>
                        <p class="font-medium capitalize">${booking.transport_type || 'Shared'}</p>
                    </div>
                </div>
                ${booking.special_requests ? `
                <div class="mt-3">
                    <p class="text-sm text-gray-500">Special Requests</p>
                    <p class="font-medium">${booking.special_requests}</p>
                </div>
                ` : ''}
            </div>
            
            <div class="border-t pt-4">
                <h4 class="font-bold text-lg mb-3">Booking Timeline</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Booking Date:</span>
                        <span class="font-medium">${new Date(booking.booking_date).toLocaleString()}</span>
                    </div>
                    ${booking.confirmed_date ? `
                    <div class="flex justify-between">
                        <span class="text-gray-500">Confirmed Date:</span>
                        <span class="font-medium">${new Date(booking.confirmed_date).toLocaleString()}</span>
                    </div>
                    ` : ''}
                    ${booking.cancelled_date ? `
                    <div class="flex justify-between">
                        <span class="text-gray-500">Cancelled Date:</span>
                        <span class="font-medium">${new Date(booking.cancelled_date).toLocaleString()}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('bookingModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}
</script>

<?php include 'includes/client-footer.php'; ?>
