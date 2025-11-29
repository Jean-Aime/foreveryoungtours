<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Book Button</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Book Button Test</h1>
        
        <div class="bg-blue-50 p-4 rounded mb-4">
            <p><strong>Session Status:</strong></p>
            <p>user_id: <?php echo $_SESSION['user_id'] ?? 'NOT SET'; ?></p>
            <p>user_role: <?php echo $_SESSION['user_role'] ?? 'NOT SET'; ?></p>
        </div>

        <div class="bg-yellow-50 p-4 rounded mb-4">
            <p><strong>Test URL:</strong></p>
            <p><a href="http://localhost/ForeverYoungTours/tour/5-days-memorable-rwanda-safari" class="text-blue-600 underline">
                http://localhost/ForeverYoungTours/tour/5-days-memorable-rwanda-safari
            </a></p>
        </div>

        <div class="bg-green-50 p-4 rounded">
            <p><strong>Expected Behavior:</strong></p>
            <ul class="list-disc list-inside">
                <li>If NOT logged in → Click button → Redirect to login page</li>
                <li>If logged in as client → Click button → Open booking modal</li>
            </ul>
        </div>

        <div class="mt-8">
            <a href="http://localhost/ForeverYoungTours/tour/5-days-memorable-rwanda-safari" class="bg-blue-600 text-white px-6 py-3 rounded">
                Go to Tour Page
            </a>
        </div>
    </div>
</body>
</html>
