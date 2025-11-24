<?php
session_start();
require_once '../config.php';
require_once '../config/database.php';
require_once '../config/stripe.php';

$tour_id = $_GET['id'] ?? null;
if (!$tour_id) {
    header('Location: packages.php');
    exit;
}

$stmt = $pdo->prepare("SELECT t.*, c.name as country_name FROM tours t LEFT JOIN countries c ON t.country_id = c.id WHERE t.id = ?");
$stmt->execute([$tour_id]);
$tour = $stmt->fetch();

if (!$tour) {
    header('Location: packages.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book <?= htmlspecialchars($tour['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2">
                    <img src="<?= getImageUrl($tour['cover_image'] ?: $tour['image_url']) ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-full h-full object-cover">
                </div>
                <div class="md:w-1/2 p-8">
                    <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($tour['name']) ?></h1>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($tour['country_name']) ?></p>
                    
                    <div class="mb-6">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-semibold"><?= $tour['duration_days'] ?> days</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Price per person:</span>
                            <span class="font-semibold text-2xl text-yellow-600">$<?= number_format($tour['price']) ?></span>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block mb-2 font-semibold">Number of Travelers</label>
                        <input type="number" id="travelers" value="1" min="1" max="<?= $tour['max_participants'] ?>" class="w-full border rounded px-4 py-2">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block mb-2 font-semibold">Tour Date</label>
                        <input type="date" id="tour_date" min="<?= date('Y-m-d') ?>" class="w-full border rounded px-4 py-2">
                    </div>
                    
                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span id="total_price" class="text-yellow-600">$<?= number_format($tour['price']) ?></span>
                        </div>
                    </div>
                    
                    <button onclick="bookNow()" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-4 rounded-lg font-bold text-lg hover:shadow-xl transition">
                        Book Now with Stripe
                    </button>
                    
                    <p class="text-sm text-gray-500 mt-4 text-center">Secure payment powered by Stripe</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    const stripe = Stripe('<?= STRIPE_PUBLISHABLE_KEY ?>');
    const tourPrice = <?= $tour['price'] ?>;
    
    document.getElementById('travelers').addEventListener('input', updateTotal);
    
    function updateTotal() {
        const travelers = parseInt(document.getElementById('travelers').value) || 1;
        const total = tourPrice * travelers;
        document.getElementById('total_price').textContent = '$' + total.toLocaleString();
    }
    
    async function bookNow() {
        const travelers = parseInt(document.getElementById('travelers').value) || 1;
        const tourDate = document.getElementById('tour_date').value;
        
        if (!tourDate) {
            alert('Please select a tour date');
            return;
        }
        
        try {
            const response = await fetch('<?= BASE_URL ?>/api/create-checkout-session.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    tour_id: <?= $tour['id'] ?>,
                    travelers: travelers,
                    tour_date: tourDate
                })
            });
            
            const session = await response.json();
            
            if (session.error) {
                alert('Error: ' + session.error);
                return;
            }
            
            const result = await stripe.redirectToCheckout({
                sessionId: session.id
            });
            
            if (result.error) {
                alert(result.error.message);
            }
        } catch (error) {
            alert('Booking error: ' + error.message);
        }
    }
    </script>
</body>
</html>
