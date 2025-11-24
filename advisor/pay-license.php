<?php
$page_title = 'Pay License Fee';
$page_subtitle = 'Activate Your Advisor License';
session_start();
require_once '../config/database.php';
require_once '../config/stripe-config.php';
require_once '../includes/csrf.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT license_type, license_status, license_expiry_date FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

require_once 'includes/advisor-header.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Advisor License</h1>
            <p class="text-slate-600">Choose your license plan to start earning commissions</p>
        </div>

        <?php if ($user['license_status'] === 'active'): ?>
        <div class="alert alert-success mb-4">
            Your <?= ucfirst($user['license_type']) ?> license is active until <?= date('M d, Y', strtotime($user['license_expiry_date'])) ?>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Basic License</h3>
                            <div class="display-4 fw-bold text-primary my-3">$<?= number_format(LICENSE_FEE_BASIC, 2) ?></div>
                            <p class="text-muted">per year</p>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>30% Commission Rate</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Access to All Tours</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Marketing Materials</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Email Support</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Training Resources</li>
                        </ul>
                        <form method="POST" action="../payment/process-payment.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="payment_type" value="license_basic">
                            <input type="hidden" name="amount" value="<?= LICENSE_FEE_BASIC ?>">
                            <button type="submit" class="btn btn-primary w-100">
                                <?= $user['license_type'] === 'basic' ? 'Renew License' : 'Get Basic License' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-warning border-3 shadow-lg h-100">
                    <div class="card-body p-4">
                        <div class="badge bg-warning text-dark mb-3">MOST POPULAR</div>
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Premium License</h3>
                            <div class="display-4 fw-bold text-warning my-3">$<?= number_format(LICENSE_FEE_PREMIUM, 2) ?></div>
                            <p class="text-muted">per year</p>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>40% Commission Rate</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority Booking Access</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Premium Marketing Kit</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority Support</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Advanced Training</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Monthly Webinars</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Exclusive Bonuses</li>
                        </ul>
                        <form method="POST" action="../payment/process-payment.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="payment_type" value="license_premium">
                            <input type="hidden" name="amount" value="<?= LICENSE_FEE_PREMIUM ?>">
                            <button type="submit" class="btn btn-warning w-100">
                                <?= $user['license_type'] === 'premium' ? 'Renew License' : 'Get Premium License' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">License Benefits</h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Why Get Licensed?</h6>
                        <ul>
                            <li>Start earning commissions immediately</li>
                            <li>Build your own travel business</li>
                            <li>Access to exclusive tours and packages</li>
                            <li>Professional marketing support</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Payment Information</h6>
                        <ul>
                            <li>Secure payment via Stripe</li>
                            <li>Annual license (12 months)</li>
                            <li>Instant activation</li>
                            <li>Cancel anytime</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php require_once 'includes/advisor-footer.php'; ?>
