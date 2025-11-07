<?php
$page_title = "My Bookings - iForYoungTours";
$page_description = "View and manage your African tour bookings";
$css_path = "../assets/css/modern-styles.css";

require_once '../config/database.php';
$conn = $pdo;

// Simple email-based lookup (in production, use proper authentication)
$customer_email = $_GET['email'] ?? '';
$bookings = [];

if ($customer_email) {
    $stmt = $conn->prepare("
        SELECT b.*, t.name as tour_name, t.destination, t.image_url, t.cover_image, c.name as country_name
        FROM bookings b
        LEFT JOIN tours t ON b.tour_id = t.id
        LEFT JOIN countries c ON t.country_id = c.id
        WHERE b.customer_email = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$customer_email]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include './header.php';
?>

<div class="min-h-screen bg-cream pt-20">
    <section class="py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 mb-4">My Bookings</h1>
                <p class="text-xl text-slate-600">Track and manage your African adventure bookings</p>
            </div>
            
            <?php if (!$customer_email): ?>
            <!-- Email Input Form -->
            <div class="max-w-md mx-auto">
                <div class="nextcloud-card p-8">
                    <h3 class="text-xl font-bold mb-4">Enter Your Email</h3>
                    <form method="GET" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-golden-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="w-full bg-golden-500 hover:bg-golden-600 text-black py-3 rounded-lg font-semibold transition-colors">
                            View My Bookings
                        </button>
                    </form>
                </div>
            </div>
            
            <?php elseif (empty($bookings)): ?>
            <!-- No Bookings Found -->
            <div class="text-center">
                <div class="nextcloud-card p-12">
                    <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No Bookings Found</h3>
                    <p class="text-slate-600 mb-6">We couldn't find any bookings for <?php echo htmlspecialchars($customer_email); ?></p>
                    <a href="../pages/packages.php" class="bg-golden-500 hover:bg-golden-600 text-black px-6 py-3 rounded-lg font-semibold transition-colors inline-block">
                        Browse Tours
                    </a>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Bookings List -->
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-slate-900">Bookings for <?php echo htmlspecialchars($customer_email); ?></h2>
                    <span class="text-slate-600"><?php echo count($bookings); ?> booking(s) found</span>
                </div>
                
                <?php foreach ($bookings as $booking): ?>
                <div class="nextcloud-card overflow-hidden">
                    <div class="md:flex">
                        <div class="md:w-1/3">
                            <?php 
                            $image = $booking['cover_image'] ?: $booking['image_url'] ?: '../assets/images/default-tour.jpg';
                            if (strpos($image, 'uploads/') === 0) {
                                $image = '../' . $image;
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($image); ?>" 
                                 alt="<?php echo htmlspecialchars($booking['tour_name']); ?>" 
                                 class="w-full h-48 md:h-full object-cover"
                                 onerror="this.src='../assets/images/default-tour.jpg'; this.onerror=null;">
                        </div>
                        <div class="md:w-2/3 p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($booking['tour_name']); ?></h3>
                                    <p class="text-slate-600"><?php echo htmlspecialchars($booking['destination'] . ', ' . $booking['country_name']); ?></p>
                                </div>
                                <span class="status-badge status-<?php echo $booking['status']; ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                                <div>
                                    <span class="text-slate-500">Booking Ref:</span>
                                    <div class="font-semibold"><?php echo htmlspecialchars($booking['booking_reference']); ?></div>
                                </div>
                                <div>
                                    <span class="text-slate-500">Travel Date:</span>
                                    <div class="font-semibold"><?php echo date('M j, Y', strtotime($booking['travel_date'])); ?></div>
                                </div>
                                <div>
                                    <span class="text-slate-500">Participants:</span>
                                    <div class="font-semibold"><?php echo $booking['participants']; ?> people</div>
                                </div>
                                <div>
                                    <span class="text-slate-500">Total Amount:</span>
                                    <div class="font-semibold text-golden-600">$<?php echo number_format($booking['total_amount'], 2); ?></div>
                                </div>
                            </div>
                            
                            <?php if ($booking['special_requests']): ?>
                            <div class="mb-4">
                                <span class="text-slate-500 text-sm">Special Requests:</span>
                                <p class="text-sm text-slate-600 mt-1"><?php echo htmlspecialchars($booking['special_requests']); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-slate-500">
                                    Booked on <?php echo date('M j, Y', strtotime($booking['created_at'])); ?>
                                </div>
                                <div class="space-x-3">
                                    <a href="tour-detail.php?id=<?php echo $booking['tour_id']; ?>" class="text-golden-600 hover:text-golden-700 font-medium text-sm">
                                        View Tour
                                    </a>
                                    <?php if ($booking['status'] === 'pending'): ?>
                                    <button class="text-red-600 hover:text-red-700 font-medium text-sm">
                                        Cancel Booking
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<style>
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}
.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #d1fae5; color: #065f46; }
.status-cancelled { background: #fee2e2; color: #991b1b; }
.status-completed { background: #dbeafe; color: #1e40af; }
</style>

<?php include '../includes/footer.php'; ?>