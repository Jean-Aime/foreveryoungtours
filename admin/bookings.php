<?php
$page_title = 'Booking Management';
$page_subtitle = 'Manage All Tour Bookings';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Get filters
$status = $_GET['status'] ?? '';
$tour_id = $_GET['tour_id'] ?? '';
$advisor_id = $_GET['advisor_id'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query with subdomain filtering
$where = [];
$params = [];

// Add subdomain filtering
if (defined('CURRENT_COUNTRY_ID')) {
    $where[] = "t.country_id = ?";
    $params[] = CURRENT_COUNTRY_ID;
} elseif (isset($_SESSION['continent_filter'])) {
    $where[] = "r.name = ?";
    $params[] = $_SESSION['continent_filter'];
}

if ($status) {
    $where[] = "b.status = ?";
    $params[] = $status;
}
if ($tour_id) {
    $where[] = "b.tour_id = ?";
    $params[] = $tour_id;
}
if ($advisor_id) {
    $where[] = "b.advisor_id = ?";
    $params[] = $advisor_id;
}
if ($date_from) {
    $where[] = "b.travel_date >= ?";
    $params[] = $date_from;
}
if ($date_to) {
    $where[] = "b.travel_date <= ?";
    $params[] = $date_to;
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Get bookings with country filtering
$sql = "SELECT b.*, t.name as tour_name, t.destination, c.name as country_name,
               CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
               CONCAT(m.first_name, ' ', m.last_name) as mca_name,
               'booking' as source
        FROM bookings b
        LEFT JOIN tours t ON b.tour_id = t.id
        LEFT JOIN countries c ON t.country_id = c.id
        LEFT JOIN regions r ON c.region_id = r.id
        LEFT JOIN users a ON b.advisor_id = a.id
        LEFT JOIN users m ON a.mca_id = m.id
        $whereClause
        ORDER BY b.booking_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

// Get statistics (bookings only)
$stats_sql = "SELECT
    COUNT(*) as total_bookings,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(total_amount) as total_revenue,
    SUM(commission_amount) as total_commissions
    FROM bookings";
$stats = $pdo->query($stats_sql)->fetch();

// Get tours for filter
$tours = $pdo->query("SELECT id, name FROM tours WHERE status = 'active'")->fetchAll();

// Get advisors for filter
$advisors = $pdo->query("SELECT id, CONCAT(first_name, ' ', last_name) as name FROM users WHERE role = 'advisor'")->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Booking Management</h1>
            <p class="text-slate-600">Manage and track all tour bookings</p>
        </div>
        
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Bookings</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $stats['total_bookings'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Confirmed</h3>
                <p class="text-3xl font-bold text-green-600"><?= $stats['confirmed'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Pending</h3>
                <p class="text-3xl font-bold text-yellow-600"><?= $stats['pending'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Revenue</h3>
                <p class="text-3xl font-bold text-purple-600">$<?= number_format($stats['total_revenue'], 2) ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Commissions</h3>
                <p class="text-3xl font-bold text-indigo-600">$<?= number_format($stats['total_commissions'], 2) ?></p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All Status</option>
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= $status == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="paid" <?= $status == 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                
                <select name="tour_id" class="border rounded px-3 py-2">
                    <option value="">All Tours</option>
                    <?php foreach ($tours as $tour): ?>
                        <option value="<?= $tour['id'] ?>" <?= $tour_id == $tour['id'] ? 'selected' : '' ?>><?= $tour['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select name="advisor_id" class="border rounded px-3 py-2">
                    <option value="">All Advisors</option>
                    <?php foreach ($advisors as $advisor): ?>
                        <option value="<?= $advisor['id'] ?>" <?= $advisor_id == $advisor['id'] ? 'selected' : '' ?>><?= $advisor['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                
                <input type="date" name="date_from" value="<?= $date_from ?>" class="border rounded px-3 py-2" placeholder="From Date">
                <input type="date" name="date_to" value="<?= $date_to ?>" class="border rounded px-3 py-2" placeholder="To Date">
                
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking Ref</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Advisor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $booking['booking_reference'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $booking['customer_name'] ?></div>
                            <div class="text-sm text-gray-500"><?= $booking['customer_email'] ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $booking['tour_name'] ?></div>
                            <div class="text-sm text-gray-500"><?= $booking['destination'] ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $booking['advisor_name'] ?: 'Direct' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M d, Y', strtotime($booking['travel_date'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$<?= number_format($booking['total_amount'], 2) ?></td>
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
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="viewBooking(<?= $booking['id'] ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                            <?php if ($booking['status'] == 'pending'): ?>
                                <button onclick="confirmBooking(<?= $booking['id'] ?>)" class="text-green-600 hover:text-green-900">Confirm</button>
                            <?php endif; ?>
                        </td>
                        <script>
                        window.bookingData_<?= $booking['id'] ?> = <?= json_encode($booking) ?>;
                        </script>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    </div>
</main>

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
                <h4 class="font-bold text-lg mb-3">Customer Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium">${booking.customer_name}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">${booking.customer_email}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium">${booking.customer_phone || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Emergency Contact</p>
                        <p class="font-medium">${booking.emergency_contact || 'N/A'}</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t pt-4">
                <h4 class="font-bold text-lg mb-3">Tour Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tour Name</p>
                        <p class="font-medium">${booking.tour_name}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Destination</p>
                        <p class="font-medium">${booking.destination || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Travel Date</p>
                        <p class="font-medium">${new Date(booking.travel_date).toLocaleDateString()}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Participants</p>
                        <p class="font-medium">${booking.participants} people</p>
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
                <h4 class="font-bold text-lg mb-3">Payment Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-2xl font-bold text-blue-600">$${parseFloat(booking.total_amount).toFixed(2)}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Status</p>
                        <p class="font-medium capitalize">${booking.payment_status}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="font-medium capitalize">${booking.payment_method || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Commission</p>
                        <p class="font-medium">$${parseFloat(booking.commission_amount || 0).toFixed(2)}</p>
                    </div>
                </div>
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
            
            ${booking.advisor_name ? `
            <div class="border-t pt-4">
                <h4 class="font-bold text-lg mb-3">Advisor Information</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Advisor</p>
                        <p class="font-medium">${booking.advisor_name}</p>
                    </div>
                    ${booking.mca_name ? `
                    <div>
                        <p class="text-sm text-gray-500">MCA</p>
                        <p class="font-medium">${booking.mca_name}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
            ` : ''}
            
            <div class="border-t pt-4 flex gap-3">
                ${booking.status === 'pending' ? `
                    <button onclick="updateBookingStatus(${id}, 'confirmed')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Confirm Booking
                    </button>
                ` : ''}
                ${booking.status === 'confirmed' ? `
                    <button onclick="updateBookingStatus(${id}, 'paid')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-dollar-sign mr-2"></i>Mark as Paid
                    </button>
                ` : ''}
                ${booking.status === 'paid' ? `
                    <button onclick="updateBookingStatus(${id}, 'completed')" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        <i class="fas fa-check-circle mr-2"></i>Mark as Completed
                    </button>
                ` : ''}
                ${booking.status !== 'cancelled' && booking.status !== 'completed' ? `
                    <button onclick="updateBookingStatus(${id}, 'cancelled')" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>Cancel Booking
                    </button>
                ` : ''}
                <button onclick="sendEmail(${id})" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    <i class="fas fa-envelope mr-2"></i>Send Email
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('bookingModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

function updateBookingStatus(id, status) {
    const messages = {
        'confirmed': 'Confirm this booking?',
        'paid': 'Mark this booking as paid?',
        'completed': 'Mark this booking as completed?',
        'cancelled': 'Cancel this booking? This action cannot be undone.'
    };
    
    if (confirm(messages[status])) {
        fetch('booking-actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'update_status', booking_id: id, status: status})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking status updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function sendEmail(id) {
    const booking = window['bookingData_' + id];
    if (!booking) return;
    
    const subject = prompt('Email Subject:', `Booking Confirmation - ${booking.booking_reference}`);
    if (!subject) return;
    
    const message = prompt('Email Message:', `Dear ${booking.customer_name},\n\nYour booking ${booking.booking_reference} has been confirmed.\n\nThank you for choosing iForYoungTours!`);
    if (!message) return;
    
    fetch('booking-actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            action: 'send_email',
            booking_id: id,
            email: booking.customer_email,
            subject: subject,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Email sent successfully!');
        } else {
            alert('Error sending email: ' + data.message);
        }
    });
}

function confirmBooking(id) {
    updateBookingStatus(id, 'confirmed');
}
</script>

<?php require_once 'includes/admin-footer.php'; ?>