<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];

// Handle booking operations
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_booking':
                $stmt = $pdo->prepare("INSERT INTO bookings (tour_id, advisor_id, customer_name, customer_email, customer_phone, travel_date, participants, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
                $total_amount = $_POST['participants'] * $_POST['tour_price'];
                $stmt->execute([
                    $_POST['tour_id'], $advisor_id, $_POST['customer_name'], 
                    $_POST['customer_email'], $_POST['customer_phone'], 
                    $_POST['travel_date'], $_POST['participants'], 
                    $total_amount
                ]);
                header('Location: bookings.php?success=1');
                exit;
        }
    }
}

// Get advisor's bookings
$stmt = $pdo->prepare("
    SELECT b.*, t.name as tour_name, t.price as tour_price, c.name as country_name 
    FROM bookings b 
    JOIN tours t ON b.tour_id = t.id 
    LEFT JOIN countries c ON t.country_id = c.id 
    WHERE b.advisor_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$advisor_id]);
$bookings = $stmt->fetchAll();

// Get available tours
$stmt = $pdo->query("SELECT t.*, c.name as country_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id WHERE t.status = 'active' ORDER BY t.name");
$tours = $stmt->fetchAll();

// Statistics
$total_bookings = count($bookings);
$confirmed_bookings = count(array_filter($bookings, fn($b) => $b['status'] == 'confirmed'));
$pending_bookings = count(array_filter($bookings, fn($b) => $b['status'] == 'pending'));
$total_revenue = array_sum(array_column(array_filter($bookings, fn($b) => in_array($b['status'], ['confirmed', 'completed'])), 'total_amount'));

$page_title = 'My Bookings';
$page_subtitle = 'Manage your client bookings and create new ones';

include 'includes/advisor-header.php';
?>

<?php if (isset($_GET['success'])): ?>
<div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>Booking created successfully!
</div>
<?php endif; ?>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Bookings</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo $total_bookings; ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-calendar-check text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Confirmed</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo $confirmed_bookings; ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-check-circle text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Pending</p>
                <p class="text-3xl font-bold text-slate-900"><?php echo $pending_bookings; ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-clock text-2xl text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Revenue</p>
                <p class="text-3xl font-bold text-slate-900">$<?php echo number_format($total_revenue); ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-dollar-sign text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Action Button -->
<div class="mb-6">
    <button onclick="openBookingModal()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all">
        <i class="fas fa-plus mr-2"></i>Create New Booking
    </button>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold text-slate-900">All Bookings</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tour</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Travel Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Participants</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                        No bookings yet. Create your first booking!
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-slate-900"><?php echo htmlspecialchars($booking['customer_name']); ?></p>
                            <p class="text-sm text-slate-600"><?php echo htmlspecialchars($booking['customer_email']); ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-slate-900"><?php echo htmlspecialchars($booking['tour_name']); ?></p>
                            <p class="text-sm text-slate-600"><?php echo htmlspecialchars($booking['country_name']); ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo date('M j, Y', strtotime($booking['travel_date'])); ?></td>
                    <td class="px-6 py-4 text-sm text-slate-700"><?php echo $booking['participants']; ?> people</td>
                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">$<?php echo number_format($booking['total_amount']); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            <?php echo match($booking['status']) {
                                'confirmed' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                default => 'bg-slate-100 text-slate-700'
                            }; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600"><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- New Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold text-slate-900">Create New Booking</h3>
                <button onclick="closeBookingModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="create_booking">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Select Tour *</label>
                    <select name="tour_id" id="tour_select" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="updatePrice()">
                        <option value="">Choose a tour...</option>
                        <?php foreach ($tours as $tour): ?>
                        <option value="<?php echo $tour['id']; ?>" data-price="<?php echo $tour['price']; ?>">
                            <?php echo htmlspecialchars($tour['name'] . ' - ' . $tour['country_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Customer Name *</label>
                    <input type="text" name="customer_name" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="John Doe">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Customer Email *</label>
                    <input type="email" name="customer_email" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="john@example.com">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Customer Phone *</label>
                    <input type="tel" name="customer_phone" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1 234 567 8900">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Travel Date *</label>
                    <input type="date" name="travel_date" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Number of Participants *</label>
                    <input type="number" name="participants" id="participants" min="1" value="1" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="updatePrice()">
                </div>
            </div>
            
            <div class="mt-6 p-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-slate-700">Total Amount:</span>
                    <span id="total_price" class="text-3xl font-bold text-slate-900">$0</span>
                </div>
            </div>
            
            <input type="hidden" name="tour_price" id="tour_price" value="0">
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeBookingModal()" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-check mr-2"></i>Create Booking
                </button>
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
</script>

<?php include 'includes/advisor-footer.php'; ?>
