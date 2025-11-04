<?php
session_start();
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $country = trim($_POST['country']);
    $city = trim($_POST['city']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($country) || empty($password)) {
        $error = "All required fields must be filled";
    } elseif (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true || $_SESSION['verification_email'] !== $email) {
        $error = "Please verify your email first";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Email already registered";
        } else {
            // Create user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, phone, country, city, role, email_verified, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'client', 1, 'active', NOW())");
            
            if ($stmt->execute([$first_name, $last_name, $email, $hashed_password, $phone, $country, $city])) {
                $user_id = $pdo->lastInsertId();
                unset($_SESSION['verification_code'], $_SESSION['code_expires'], $_SESSION['verification_email'], $_SESSION['email_verified']);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = 'client';
                
                header('Location: ../client/index.php');
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../assets/css/modern-styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { overflow: hidden; }
        .form-container { height: 100vh; overflow-y: hidden; display: flex; flex-direction: column; }
        .image-container { height: 100vh; }
        .form-scroll { flex: 1; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 form-container bg-white">
            <!-- Logo in Top Left -->
            <div class="p-6">
                <a href="../index.php" class="inline-block hover:opacity-80 transition-opacity">
                    <h1 class="text-2xl font-bold text-gradient">iForYoungTours</h1>
                </a>
            </div>
            
            <!-- Scrollable Form Content -->
            <div class="form-scroll flex items-center justify-center px-8">
                <div class="w-full max-w-md py-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">Create Account</h2>
                        <p class="text-slate-600">Join us for amazing African adventures</p>
                    </div>

                <!-- Error Message -->
                <?php if ($error): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <div class="nextcloud-card p-8">
                        <form method="POST" id="registrationForm" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" required 
                                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                       placeholder="John">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" required 
                                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                       placeholder="Doe">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                   placeholder="john@gmail.com">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" required
                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                   placeholder="+1 (555) 000-0000">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="country" required
                                       value="<?php echo htmlspecialchars($_POST['country'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                       placeholder="United States">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-900 mb-2">
                                    City
                                </label>
                                <input type="text" name="city"
                                       value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                       placeholder="New York">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                   placeholder="Minimum 6 characters">
                            <p class="text-xs text-slate-500 mt-1">Must be at least 6 characters</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="confirm_password" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 focus:border-transparent text-black"
                                   placeholder="Re-enter password">
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" required class="mt-1 mr-3 accent-golden-600">
                            <span class="text-sm text-slate-600">
                                I agree to the <a href="#" class="text-golden-600 hover:text-golden-700">Terms of Service</a> 
                                and <a href="#" class="text-golden-600 hover:text-golden-700">Privacy Policy</a>
                            </span>
                        </div>

                            <button type="button" onclick="handleCreateAccount()" id="submitBtn" class="w-full btn-primary text-black px-6 py-3 rounded-lg font-semibold text-lg">
                                Create Account
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <p class="text-slate-600">
                                Already have an account? 
                                <a href="login.php" class="text-golden-600 hover:text-golden-700 font-semibold">Sign In</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Tourism Image -->
        <div class="hidden lg:block lg:w-1/2 image-container relative">
            <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1200" 
                 alt="African Safari" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-12 text-white">
                <h2 class="text-4xl font-bold mb-4">Discover Africa's Wonders</h2>
                <p class="text-xl opacity-90">Join thousands of travelers exploring the beauty of African safaris, culture, and adventures.</p>
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div id="verificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold text-slate-900 mb-4">Verify Your Email</h3>
            <p class="text-slate-600 mb-4" id="modalMessage">We've sent a 6-digit verification code to <span id="emailDisplay" class="font-semibold"></span>. Please check your inbox.</p>
            
            <div id="testModeBox" class="bg-yellow-50 border-2 border-yellow-500 rounded-lg p-4 mb-4" style="display:none;">
                <p class="text-sm text-yellow-800 mb-2 font-semibold">⚠️ Test Mode - Email Not Configured</p>
                <p class="text-xs text-yellow-700 mb-2">Your verification code:</p>
                <p id="testCode" class="text-3xl font-bold text-center text-yellow-600 tracking-widest"></p>
                <p class="text-xs text-yellow-700 mt-2">Configure email in config/email-config.php for production</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-900 mb-2">Enter Verification Code</label>
                <input type="text" id="verificationCode" maxlength="6" 
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-golden-500 text-center text-2xl tracking-widest text-black"
                       placeholder="000000">
                <p id="verificationStatus" class="text-sm mt-2"></p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="verifyAndSubmit()" id="verifyBtn" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                    Verify & Register
                </button>
                <button onclick="closeModal()" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 font-semibold">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
    let formData = {};

    async function handleCreateAccount() {
        const form = document.getElementById('registrationForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Collect form data
        formData = {
            first_name: form.first_name.value,
            last_name: form.last_name.value,
            email: form.email.value,
            phone: form.phone.value,
            country: form.country.value,
            city: form.city.value,
            password: form.password.value,
            confirm_password: form.confirm_password.value
        };

        // Validate passwords match
        if (formData.password !== formData.confirm_password) {
            alert('Passwords do not match');
            return;
        }

        // Send verification code
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'Sending code...';

        try {
            const response = await fetch('send-verification-code.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'email=' + encodeURIComponent(formData.email)
            });
            const text = await response.text();
            console.log('Response:', text);
            const data = JSON.parse(text);
            
            if (data.success) {
                document.getElementById('emailDisplay').textContent = formData.email;
                document.getElementById('verificationModal').classList.remove('hidden');
                document.getElementById('verificationModal').classList.add('flex');
                
                // Show code if in test mode
                if (data.test_mode && data.code) {
                    document.getElementById('testModeBox').style.display = 'block';
                    document.getElementById('testCode').textContent = data.code;
                    document.getElementById('modalMessage').innerHTML = '<span class="text-yellow-600">⚠️ Email not configured - Using test mode</span>';
                }
            } else {
                let errorMsg = data.message;
                if (data.debug) {
                    errorMsg += '\n\nDebug: ' + data.debug;
                }
                alert(errorMsg);
                console.error('Error details:', data);
            }
        } catch (error) {
            alert('Error sending code: ' + error.message);
            console.error('Full error:', error);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Create Account';
        }
    }

    async function verifyAndSubmit() {
        const code = document.getElementById('verificationCode').value;
        if (!code || code.length !== 6) {
            alert('Please enter the 6-digit code');
            return;
        }

        const btn = document.getElementById('verifyBtn');
        btn.disabled = true;
        btn.textContent = 'Verifying...';

        try {
            const response = await fetch('verify-email-code.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'code=' + encodeURIComponent(code)
            });
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('verificationStatus').textContent = '✓ Verified! Creating account...';
                document.getElementById('verificationStatus').className = 'text-sm mt-2 text-green-600';
                
                // Submit the form
                setTimeout(() => {
                    document.getElementById('registrationForm').submit();
                }, 500);
            } else {
                document.getElementById('verificationStatus').textContent = data.message;
                document.getElementById('verificationStatus').className = 'text-sm mt-2 text-red-600';
                btn.disabled = false;
                btn.textContent = 'Verify & Register';
            }
        } catch (error) {
            alert('Error verifying code. Please try again.');
            btn.disabled = false;
            btn.textContent = 'Verify & Register';
        }
    }

    function closeModal() {
        document.getElementById('verificationModal').classList.add('hidden');
        document.getElementById('verificationModal').classList.remove('flex');
        document.getElementById('verificationCode').value = '';
        document.getElementById('verificationStatus').textContent = '';
    }
    </script>
</body>
</html>
