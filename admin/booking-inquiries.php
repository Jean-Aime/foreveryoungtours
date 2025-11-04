<?php
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

$stmt = $pdo->query("SELECT * FROM booking_inquiries ORDER BY created_at DESC");
$inquiries = $stmt->fetchAll();

include 'includes/admin-header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Booking Inquiries</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Booking Inquiries</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            All Booking Inquiries
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Travel Dates</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inquiry): ?>
                    <tr>
                        <td><?php echo $inquiry['id']; ?></td>
                        <td><?php echo htmlspecialchars($inquiry['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                        <td><?php echo htmlspecialchars($inquiry['phone']); ?></td>
                        <td><?php echo htmlspecialchars($inquiry['travel_dates']); ?></td>
                        <td><?php echo htmlspecialchars($inquiry['budget']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $inquiry['status'] == 'pending' ? 'warning' : 'success'; ?>">
                                <?php echo ucfirst($inquiry['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($inquiry['created_at'])); ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewInquiry(<?php echo $inquiry['id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function viewInquiry(id) {
    window.location.href = 'booking-inquiry-detail.php?id=' + id;
}
</script>

<?php include 'includes/admin-footer.php'; ?>
