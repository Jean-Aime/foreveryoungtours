<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
        <h2 class="text-3xl font-bold text-center mb-6">Create Account</h2>
        
        <form id="registerForm">
            <!-- Step 1: Basic Info -->
            <div id="step1" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-900">Full Name</label>
                    <input type="text" id="name" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-yellow-500 text-black placeholder-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-900">Email</label>
                    <input type="email" id="email" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-yellow-500 text-black placeholder-gray-500">
                    <button type="button" onclick="sendVerification('email')" class="mt-2 text-sm text-blue-600 hover:underline">Send Verification Code</button>
                </div>
                <div id="emailVerifySection" class="hidden">
                    <label class="block text-sm font-medium mb-2 text-slate-900">Email Verification Code</label>
                    <input type="text" id="emailCode" maxlength="6" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-yellow-500 text-black placeholder-gray-500">
                    <button type="button" onclick="verifyCode('email')" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Verify Email</button>
                    <span id="emailVerified" class="hidden text-green-600 ml-2">✓ Verified</span>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-900">Phone Number</label>
                    <input type="tel" id="phone" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-yellow-500 text-black placeholder-gray-500">
                    <button type="button" onclick="sendVerification('phone')" class="mt-2 text-sm text-blue-600 hover:underline">Send Verification Code</button>
                </div>
                <div id="phoneVerifySection" class="hidden">
                    <label class="block text-sm font-medium mb-2 text-slate-900">Phone Verification Code</label>
                    <input type="text" id="phoneCode" maxlength="6" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-yellow-500 text-black placeholder-gray-500">
                    <button type="button" onclick="verifyCode('phone')" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Verify Phone</button>
                    <span id="phoneVerified" class="hidden text-green-600 ml-2">✓ Verified</span>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-900">Password</label>
                    <input type="password" id="password" required minlength="6" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-yellow-500 text-black placeholder-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2 text-slate-900">Confirm Password</label>
                    <input type="password" id="confirmPassword" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-yellow-500 text-black placeholder-gray-500">
                </div>
                <button type="submit" id="registerBtn" disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                    Register (Verify Email & Phone First)
                </button>
            </div>
        </form>
        
        <p class="text-center mt-4 text-sm">
            Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login</a>
        </p>
    </div>

    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
    let emailVerified = false;
    let phoneVerified = false;

    function showToast(message, type = 'info') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };
        const toast = document.createElement('div');
        toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
        toast.innerHTML = `<div class="flex items-center gap-3"><span>${message}</span></div>`;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    function sendVerification(type) {
        const value = document.getElementById(type).value;
        if (!value) {
            showToast('Please enter your ' + type, 'error');
            return;
        }

        fetch('send-verification.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `type=${type}&value=${encodeURIComponent(value)}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message + (data.demo_code ? ' (Code: ' + data.demo_code + ')' : ''), 'success');
                document.getElementById(type + 'VerifySection').classList.remove('hidden');
            } else {
                showToast(data.message, 'error');
            }
        });
    }

    function verifyCode(type) {
        const code = document.getElementById(type + 'Code').value;
        if (!code) {
            showToast('Please enter verification code', 'error');
            return;
        }

        fetch('verify-code.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `type=${type}&code=${code}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                document.getElementById(type + 'Verified').classList.remove('hidden');
                if (type === 'email') emailVerified = true;
                if (type === 'phone') phoneVerified = true;
                
                if (emailVerified && phoneVerified) {
                    document.getElementById('registerBtn').disabled = false;
                    document.getElementById('registerBtn').classList.remove('bg-gray-400', 'cursor-not-allowed');
                    document.getElementById('registerBtn').classList.add('bg-yellow-500', 'hover:bg-yellow-600');
                    document.getElementById('registerBtn').textContent = 'Create Account';
                }
            } else {
                showToast(data.message, 'error');
            }
        });
    }

    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!emailVerified || !phoneVerified) {
            showToast('Please verify both email and phone number', 'error');
            return;
        }

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (password !== confirmPassword) {
            showToast('Passwords do not match', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('password', password);

        fetch('register-with-verification.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.href = data.redirect, 1500);
            } else {
                showToast(data.message, 'error');
            }
        });
    });
    </script>
</body>
</html>
