<?php
$page_title = 'Register Client';
$page_subtitle = 'Add New Client to Your Network';
session_start();
require_once '../config/database.php';
require_once '../includes/csrf.php';
require_once '../auth/check_auth.php';
checkAuth('advisor');

$advisor_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRF();
    
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $country = trim($_POST['country']);
    $password = $_POST['password'];
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered';
        } else {
            // Create client account
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, country, password, role, status, sponsor_id, created_at) VALUES (?, ?, ?, ?, ?, ?, 'client', 'active', ?, NOW())");
                $stmt->execute([$first_name, $last_name, $email, $phone, $country, $hashed_password, $advisor_id]);
                
                $client_id = $pdo->lastInsertId();
                
                // Send welcome email
                require_once '../config/email-config.php';
                $email_body = getEmailTemplate("
                    <h2>Welcome to iForYoungTours!</h2>
                    <p>Dear $first_name,</p>
                    <p>Your account has been created by your travel advisor. You can now book amazing tours across Africa!</p>
                    <p><strong>Your Login Details:</strong></p>
                    <p>Email: $email<br>Password: [Use the password you received]</p>
                    <a href='https://yourdomain.com/auth/login.php' class='button'>Login to Your Account</a>
                    <p>Start exploring our tours and book your next adventure!</p>
                ", "Welcome to iForYoungTours");
                
                sendEmail($email, 'Welcome to iForYoungTours', $email_body);
                
                $success = "Client registered successfully! Login details sent to $email";
            } catch (PDOException $e) {
                $error = 'Registration failed: ' . $e->getMessage();
            }
        }
    }
}

require_once 'includes/advisor-header.php';
?>

<main class="flex-1 overflow-auto ml-64 w-[calc(100%-16rem)] min-h-screen bg-cream">
    <div class="p-6 md:p-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Register New Client</h1>
            <p class="text-slate-600">Add a new client to your network and earn commissions on their bookings</p>
        </div>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST">
                    <?= csrfField() ?>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" required>
                        <small class="text-muted">Client will use this to login</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country *</label>
                            <input type="text" name="country" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Temporary Password *</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <small class="text-muted">Minimum 6 characters. Client can change this later.</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> The client will receive a welcome email with their login credentials. You'll earn commissions on all their bookings!
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Register Client
                        </button>
                        <a href="team.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Benefits of Registering Clients</h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Earn commissions on all their bookings</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Build your client base</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Track their booking history</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Provide personalized service</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Grow your network</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Increase your earnings</li>
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
