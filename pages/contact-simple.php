<?php
session_start();
require_once '../includes/csrf.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact - Forever Young Tours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8">Contact Us</h1>
        
        <form method="POST" action="contact-handler.php" class="bg-white p-8 rounded-lg shadow">
            <?php echo getCsrfField(); ?>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 font-semibold">First Name *</label>
                    <input type="text" name="first_name" required class="w-full border rounded px-4 py-2">
                </div>
                <div>
                    <label class="block mb-2 font-semibold">Last Name *</label>
                    <input type="text" name="last_name" required class="w-full border rounded px-4 py-2">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2 font-semibold">Email *</label>
                    <input type="email" name="email" required class="w-full border rounded px-4 py-2">
                </div>
                <div>
                    <label class="block mb-2 font-semibold">Phone</label>
                    <input type="tel" name="phone" class="w-full border rounded px-4 py-2">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block mb-2 font-semibold">Subject *</label>
                <select name="subject" required class="w-full border rounded px-4 py-2">
                    <option value="">Select subject</option>
                    <option value="general">General Inquiry</option>
                    <option value="booking">Booking Question</option>
                    <option value="package">Package Information</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block mb-2 font-semibold">Message *</label>
                <textarea name="message" required rows="5" class="w-full border rounded px-4 py-2"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-3 rounded-lg font-semibold hover:shadow-lg">
                Send Message
            </button>
        </form>
    </div>
</body>
</html>
