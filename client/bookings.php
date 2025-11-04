<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('client');

$client_email = $_SESSION['user_email'] ?? '';

// Handle booking cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $booking_id = $_POST['booking_id'];
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled', cancelled_date = NOW() WHERE id = ? AND customer_email = ?");
    $stmt->execute([$booking_id, $client_email]);
    header('Location: bookings.php');
    exit;
}

// Get bookings (confirmed bookings only from bookings table)
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

include 'includes/client-header.php';
?>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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

<!-- Bookings List -->
<?php if (empty($bookings)): ?>
<div class="bg-white rounded-lg shadow-sm p-12 text-center">
    <i class="fas fa-calendar-times text-6xl mb-4" style="color: #ccc;"></i>
    <h3 class="text-xl font-medium mb-2" style="color: #333;">No bookings yet</h3>
    <p class="mb-6" style="color: #666;">Start your African adventure by booking your first tour</p>
    <a href="tours.php" class="inline-block px-6 py-3 rounded-lg font-semibold transition-colors" style="background: #DAA520; color: #000;">
        <i class="fas fa-search mr-2"></i>Explore Tours
    </a>
</div>

<?php else: ?>
<div class="space-y-6">
    <?php foreach ($bookings as $booking): ?>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-bold mb-1" style="color: #333;">
                    <?php echo htmlspecialchars($booking['tour_name'] ?: 'Tour Booking'); ?>
                </h3>
                <p style="color: #666;">
                    <i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($booking['customer_name']); ?>
                </p>
                <p class="text-sm" style="color: #999;">
                    Ref: <?php echo htmlspecialchars($booking['booking_reference']); ?>
                </p>
            </div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full status-badge-<?php echo $booking['status']; ?>">
                <?php echo ucfirst($booking['status']); ?>
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-sm mb-1" style="color: #666;"><i class="fas fa-calendar mr-2"></i>Travel Date</p>
                <p class="font-semibold" style="color: #333;"><?php echo date('M d, Y', strtotime($booking['travel_date'])); ?></p>
            </div>
            <div>
                <p class="text-sm mb-1" style="color: #666;"><i class="fas fa-users mr-2"></i>Participants</p>
                <p class="font-semibold" style="color: #333;"><?php echo $booking['participants']; ?> People</p>
            </div>
            <div>
                <p class="text-sm mb-1" style="color: #666;"><i class="fas fa-dollar-sign mr-2"></i>Total Amount</p>
                <p class="font-semibold" style="color: #DAA520;">$<?php echo number_format($booking['total_amount'], 2); ?></p>
            </div>
        </div>
        
        <?php if ($booking['accommodation_type'] || $booking['transport_type']): ?>
        <div class="mb-4">
            <p class="text-sm mb-1" style="color: #666;"><i class="fas fa-info-circle mr-2"></i>Booking Details</p>
            <p style="color: #333;">Accommodation: <?php echo ucfirst($booking['accommodation_type']); ?> | Transport: <?php echo ucfirst($booking['transport_type']); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if ($booking['special_requests']): ?>
        <div class="mb-4 p-3 rounded-lg" style="background: #f9f9f9;">
            <p class="text-sm mb-1" style="color: #666;">Special Requests:</p>
            <p class="text-sm" style="color: #333;"><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="flex justify-between items-center pt-4 border-t">
            <div class="text-sm" style="color: #999;">
                Booked on <?php echo date('M j, Y g:i A', strtotime($booking['booking_date'])); ?>
            </div>
            <div class="flex space-x-3">
                <?php if ($booking['status'] === 'pending'): ?>
                <form method="POST" class="inline" onsubmit="return confirm('Cancel this booking?')">
                    <input type="hidden" name="action" value="cancel">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                    <button type="submit" class="text-sm font-medium" style="color: #dc2626;">
                        <i class="fas fa-times mr-1"></i>Cancel
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<style>
.status-badge-pending { background: #fef3c7; color: #92400e; }
.status-badge-confirmed { background: #d1fae5; color: #065f46; }
.status-badge-cancelled { background: #fee2e2; color: #991b1b; }
.status-badge-completed { background: #dbeafe; color: #1e40af; }
</style>

<?php include 'includes/client-footer.php'; ?>