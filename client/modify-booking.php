<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
require_once '../includes/audit-logger.php';
checkAuth('client');

$booking_id = $_GET['id'] ?? null;
$success = '';
$error = '';

if (!$booking_id) {
    header('Location: my-bookings.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    header('Location: my-bookings.php');
    exit();
}

if ($_POST) {
    try {
        $modification_type = $_POST['modification_type'];
        $old_data = json_encode($booking);
        $new_data = json_encode($_POST);
        
        $stmt = $pdo->prepare("INSERT INTO booking_modifications 
            (booking_id, modification_type, old_data, new_data, reason, requested_by, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $booking_id,
            $modification_type,
            $old_data,
            $new_data,
            $_POST['reason'],
            $_SESSION['user_id']
        ]);
        
        logAudit($_SESSION['user_id'], 'booking_modification_request', 'booking', $booking_id, $booking, $_POST);
        $success = 'Modification request submitted successfully!';
    } catch (PDOException $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

require_once 'includes/client-header.php';
?>

<main class="flex-1 min-h-screen bg-cream">
    <div class="p-6 md:p-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-slate-900 mb-6">Modify Booking</h1>
            
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Current Booking Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>Booking ID:</strong> #<?= $booking['id'] ?></div>
                    <div><strong>Status:</strong> <?= ucfirst($booking['status']) ?></div>
                    <div><strong>Destination:</strong> <?= htmlspecialchars($booking['destination']) ?></div>
                    <div><strong>Travel Date:</strong> <?= date('M j, Y', strtotime($booking['travel_date'])) ?></div>
                    <div><strong>Guests:</strong> <?= $booking['number_of_guests'] ?></div>
                    <div><strong>Total:</strong> $<?= number_format($booking['total_amount'], 2) ?></div>
                </div>
            </div>
            
            <form method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Modification Type *</label>
                        <select name="modification_type" required onchange="showFields(this.value)"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                            <option value="">Select Type</option>
                            <option value="date_change">Change Travel Date</option>
                            <option value="guest_change">Change Number of Guests</option>
                            <option value="package_change">Change Package</option>
                            <option value="cancellation">Cancel Booking</option>
                        </select>
                    </div>
                    
                    <div id="dateFields" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">New Travel Date *</label>
                        <input type="date" name="new_travel_date" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    
                    <div id="guestFields" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">New Number of Guests *</label>
                        <input type="number" name="new_guests" min="1" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    
                    <div id="packageFields" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">New Package *</label>
                        <input type="text" name="new_package" 
                               class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Reason for Modification *</label>
                        <textarea name="reason" rows="4" required
                                  class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-gold"></textarea>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Modification fees may apply. Your request will be reviewed by our team.
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <a href="my-bookings.php" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary-gold text-white rounded-lg hover:bg-yellow-600">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function showFields(type) {
    document.getElementById('dateFields').classList.add('hidden');
    document.getElementById('guestFields').classList.add('hidden');
    document.getElementById('packageFields').classList.add('hidden');
    
    if (type === 'date_change') {
        document.getElementById('dateFields').classList.remove('hidden');
    } else if (type === 'guest_change') {
        document.getElementById('guestFields').classList.remove('hidden');
    } else if (type === 'package_change') {
        document.getElementById('packageFields').classList.remove('hidden');
    }
}
</script>

<?php require_once 'includes/client-footer.php'; ?>
