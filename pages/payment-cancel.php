<?php
$tour_id = $_GET['tour_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Cancelled</h1>
            <p class="text-gray-600 mb-8">Your payment was cancelled. No charges were made to your account.</p>
            <div class="space-y-3">
                <?php if ($tour_id): ?>
                <a href="tour-detail.php?id=<?= $tour_id ?>" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition">
                    Try Again
                </a>
                <?php endif; ?>
                <a href="../index.php" class="block w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-50 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
