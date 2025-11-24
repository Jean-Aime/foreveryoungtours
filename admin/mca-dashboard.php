<?php
$page_title = 'MCA Dashboard';
$page_subtitle = 'Master Country Advisor Overview';
session_start();
require_once '../config/database.php';
require_once '../auth/check_auth.php';
checkAuth('super_admin');

// Get MCA ID from query or default
$mca_id = $_GET['id'] ?? null;

if (!$mca_id) {
    header('Location: mca-management.php');
    exit();
}

// Get MCA info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'mca'");
$stmt->execute([$mca_id]);
$mca_info = $stmt->fetch();

if (!$mca_info) {
    header('Location: mca-management.php');
    exit();
}

// Get assigned countries
$stmt = $pdo->prepare("
    SELECT c.*, ma.assigned_at 
    FROM countries c 
    JOIN mca_assignments ma ON c.id = ma.country_id 
    WHERE ma.mca_id = ? AND ma.status = 'active'
");
$stmt->execute([$mca_id]);
$countries = $stmt->fetchAll();

// Get team advisors
$stmt = $pdo->prepare("
    SELECT u.*, 
           COUNT(DISTINCT b.id) as booking_count, 
           COALESCE(SUM(b.total_amount), 0) as total_sales
    FROM users u
    LEFT JOIN bookings b ON u.id = b.advisor_id AND b.status = 'confirmed'
    WHERE u.sponsor_id = ? AND u.role = 'advisor'
    GROUP BY u.id
    ORDER BY total_sales DESC
");
$stmt->execute([$mca_id]);
$advisors = $stmt->fetchAll();

// Get performance stats
$stmt = $pdo->prepare("
    SELECT 
        COUNT(DISTINCT u.id) as total_advisors,
        COUNT(DISTINCT b.id) as total_bookings,
        COALESCE(SUM(b.total_amount), 0) as total_revenue,
        COALESCE(SUM(c.commission_amount), 0) as total_commissions
    FROM users u
    LEFT JOIN bookings b ON u.id = b.advisor_id AND b.status = 'confirmed'
    LEFT JOIN commissions c ON b.id = c.booking_id AND c.user_id = ?
    WHERE u.sponsor_id = ?
");
$stmt->execute([$mca_id, $mca_id]);
$stats = $stmt->fetch();

// Get monthly team performance (last 6 months)
$stmt = $pdo->prepare("
    SELECT 
        DATE_FORMAT(b.booking_date, '%Y-%m') as month,
        COUNT(DISTINCT b.id) as bookings,
        SUM(b.total_amount) as revenue
    FROM bookings b
    JOIN users u ON b.advisor_id = u.id
    WHERE u.sponsor_id = ? AND b.status = 'confirmed'
    AND b.booking_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(b.booking_date, '%Y-%m')
    ORDER BY month
");
$stmt->execute([$mca_id]);
$monthly_data = $stmt->fetchAll();

// Get top performing advisors
$stmt = $pdo->prepare("
    SELECT 
        CONCAT(u.first_name, ' ', u.last_name) as name,
        COUNT(b.id) as bookings,
        SUM(b.total_amount) as revenue
    FROM users u
    LEFT JOIN bookings b ON u.id = b.advisor_id AND b.status = 'confirmed'
    WHERE u.sponsor_id = ?
    GROUP BY u.id
    ORDER BY revenue DESC
    LIMIT 5
");
$stmt->execute([$mca_id]);
$top_advisors = $stmt->fetchAll();

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
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">MCA Dashboard</h1>
                    <p class="text-slate-600"><?= htmlspecialchars(trim($mca_info['first_name'] . ' ' . $mca_info['last_name'])) ?></p>
                </div>
                <a href="mca-management.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to MCAs
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
                                <p class="text-muted mb-1 small">Team Advisors</p>
                                <h3 class="mb-0 fw-bold"><?= number_format($stats['total_advisors']) ?></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <i class="fas fa-users text-primary fs-4"></i>
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
                                <p class="text-muted mb-1 small">Total Bookings</p>
                                <h3 class="mb-0 fw-bold text-success"><?= number_format($stats['total_bookings']) ?></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <i class="fas fa-calendar-check text-success fs-4"></i>
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
                                <p class="text-muted mb-1 small">Team Revenue</p>
                                <h3 class="mb-0 fw-bold text-info">$<?= number_format($stats['total_revenue'], 2) ?></h3>
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
                                <p class="text-muted mb-1 small">MCA Commissions</p>
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
                        <h5 class="mb-0">Team Performance (Last 6 Months)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceChart" height="80"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Top Advisors</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="advisorsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Assigned Countries -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Assigned Countries</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($countries)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-globe text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No countries assigned</p>
                        </div>
                        <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($countries as $country): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($country['name']) ?></h6>
                                    <small class="text-muted">Assigned: <?= date('M d, Y', strtotime($country['assigned_at'])) ?></small>
                                </div>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Team Advisors -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Team Advisors</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($advisors)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-user-tie text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No team advisors yet</p>
                        </div>
                        <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($advisors as $advisor): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?= htmlspecialchars(trim($advisor['first_name'] . ' ' . $advisor['last_name'])) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($advisor['email']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold"><?= $advisor['booking_count'] ?> bookings</div>
                                    <small class="text-muted">$<?= number_format($advisor['total_sales'], 2) ?></small>
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
// Team Performance Chart
const perfCtx = document.getElementById('performanceChart').getContext('2d');
const perfChart = new Chart(perfCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_map(fn($d) => date('M Y', strtotime($d['month'].'-01')), $monthly_data)) ?>,
        datasets: [{
            label: 'Revenue ($)',
            data: <?= json_encode(array_map(fn($d) => $d['revenue'], $monthly_data)) ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1,
            yAxisID: 'y'
        }, {
            label: 'Bookings',
            data: <?= json_encode(array_map(fn($d) => $d['bookings'], $monthly_data)) ?>,
            type: 'line',
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
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

// Top Advisors Chart
const advisorsCtx = document.getElementById('advisorsChart').getContext('2d');
const advisorsChart = new Chart(advisorsCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($d) => $d['name'], $top_advisors)) ?>,
        datasets: [{
            data: <?= json_encode(array_map(fn($d) => $d['revenue'], $top_advisors)) ?>,
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(139, 92, 246)',
                'rgb(236, 72, 153)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { 
                display: true, 
                position: 'bottom',
                labels: {
                    font: { size: 10 },
                    boxWidth: 12
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': $' + context.parsed.toFixed(2);
                    }
                }
            }
        }
    }
});
</script>

<?php require_once 'includes/admin-footer.php'; ?>
