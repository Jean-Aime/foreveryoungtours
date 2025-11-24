<?php
$page_title = 'Advisor Dashboard';
$page_subtitle = 'Advisor Performance Overview';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Get Advisor ID from query
$advisor_id = $_GET['id'] ?? null;

if (!$advisor_id) {
    header('Location: advisor-management.php');
    exit();
}

// Get advisor info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'advisor'");
$stmt->execute([$advisor_id]);
$advisor_info = $stmt->fetch();

if (!$advisor_info) {
    header('Location: advisor-management.php');
    exit();
}

// Get advisor's bookings
$stmt = $pdo->prepare("
    SELECT b.*, t.name as tour_name, t.destination
    FROM bookings b
    JOIN tours t ON b.tour_id = t.id
    WHERE b.advisor_id = ?
    ORDER BY b.booking_date DESC
    LIMIT 10
");
$stmt->execute([$advisor_id]);
$bookings = $stmt->fetchAll();

// Get advisor stats
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_bookings,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
        COALESCE(SUM(CASE WHEN status = 'confirmed' THEN total_amount ELSE 0 END), 0) as total_sales,
        COALESCE(SUM(CASE WHEN status = 'confirmed' THEN commission_amount ELSE 0 END), 0) as total_commissions
    FROM bookings 
    WHERE advisor_id = ?
");
$stmt->execute([$advisor_id]);
$stats = $stmt->fetch();

// Get commission details
$stmt = $pdo->prepare("
    SELECT c.*, b.booking_reference, b.customer_name
    FROM commissions c
    JOIN bookings b ON c.booking_id = b.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
    LIMIT 10
");
$stmt->execute([$advisor_id]);
$commissions = $stmt->fetchAll();

// Get monthly sales data (last 6 months)
$stmt = $pdo->prepare("
    SELECT 
        DATE_FORMAT(booking_date, '%Y-%m') as month,
        COUNT(*) as bookings,
        SUM(total_amount) as revenue
    FROM bookings
    WHERE advisor_id = ? AND status = 'confirmed'
    AND booking_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
    ORDER BY month
");
$stmt->execute([$advisor_id]);
$monthly_data = $stmt->fetchAll();

// Get booking status breakdown
$stmt = $pdo->prepare("
    SELECT status, COUNT(*) as count
    FROM bookings
    WHERE advisor_id = ?
    GROUP BY status
");
$stmt->execute([$advisor_id]);
$status_data = $stmt->fetchAll();

require_once 'includes/admin-header.php';
require_once 'includes/admin-sidebar.php';
?>

<!-- Main Content -->
<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Advisor Dashboard</h1>
                    <p class="text-slate-600"><?= htmlspecialchars(trim($advisor_info['first_name'] . ' ' . $advisor_info['last_name'])) ?></p>
                </div>
                <a href="advisor-management.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Advisors
                </a>
            </div>
        </div>

        <!-- Performance Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Bookings</p>
                                <h3 class="mb-0 fw-bold"><?= number_format($stats['total_bookings']) ?></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-calendar text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Confirmed</p>
                                <h3 class="mb-0 fw-bold text-success"><?= number_format($stats['confirmed_bookings']) ?></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Sales</p>
                                <h3 class="mb-0 fw-bold text-info">$<?= number_format($stats['total_sales'], 2) ?></h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded p-3">
                                <i class="fas fa-dollar-sign text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Commissions</p>
                                <h3 class="mb-0 fw-bold text-warning">$<?= number_format($stats['total_commissions'], 2) ?></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <i class="fas fa-coins text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Charts -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Sales Trend (Last 6 Months)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="80"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Booking Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Bookings -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Recent Bookings</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($bookings)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No bookings yet</p>
                        </div>
                        <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($bookings as $booking): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= htmlspecialchars($booking['booking_reference']) ?></h6>
                                    <small class="text-muted d-block"><?= htmlspecialchars($booking['customer_name']) ?></small>
                                    <small class="text-muted"><?= htmlspecialchars($booking['tour_name']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">$<?= number_format($booking['total_amount'], 2) ?></div>
                                    <span class="badge bg-<?php 
                                        echo match($booking['status']) {
                                            'confirmed' => 'success',
                                            'pending' => 'warning',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>"><?= ucfirst($booking['status']) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Commission History -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Commission History</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($commissions)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-coins text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No commissions yet</p>
                        </div>
                        <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($commissions as $commission): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= htmlspecialchars($commission['booking_reference']) ?></h6>
                                    <small class="text-muted d-block"><?= htmlspecialchars($commission['customer_name']) ?></small>
                                    <small class="text-muted"><?= ucfirst($commission['commission_type']) ?> (<?= $commission['commission_rate'] ?>%)</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">$<?= number_format($commission['commission_amount'], 2) ?></div>
                                    <span class="badge bg-<?php 
                                        echo match($commission['status']) {
                                            'paid' => 'primary',
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            default => 'secondary'
                                        };
                                    ?>"><?= ucfirst($commission['status']) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Trend Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_map(fn($d) => date('M Y', strtotime($d['month'].'-01')), $monthly_data)) ?>,
        datasets: [{
            label: 'Revenue ($)',
            data: <?= json_encode(array_map(fn($d) => $d['revenue'], $monthly_data)) ?>,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Bookings',
            data: <?= json_encode(array_map(fn($d) => $d['bookings'], $monthly_data)) ?>,
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: { display: true, text: 'Revenue ($)' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: { display: true, text: 'Bookings' },
                grid: { drawOnChartArea: false }
            }
        },
        plugins: {
            legend: { display: true, position: 'top' }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($d) => ucfirst($d['status']), $status_data)) ?>,
        datasets: [{
            data: <?= json_encode(array_map(fn($d) => $d['count'], $status_data)) ?>,
            backgroundColor: [
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(59, 130, 246)',
                'rgb(239, 68, 68)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: true, position: 'bottom' }
        }
    }
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>
