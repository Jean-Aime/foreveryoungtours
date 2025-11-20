<?php
require_once 'config/database.php';
require_once 'subdomain-handler.php';

$tour_id = $_GET['tour_id'] ?? null;
if (!$tour_id) {
    header('Location: /');
    exit;
}

// Get tour details
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, c.flag_url 
    FROM tours t 
    JOIN countries c ON t.country_id = c.id 
    WHERE t.id = ? AND t.status = 'active'
");
$stmt->execute([$tour_id]);
$tour = $stmt->fetch();

if (!$tour) {
    header('Location: /');
    exit;
}

// Handle booking submission
if ($_POST) {
    $stmt = $pdo->prepare("
        INSERT INTO bookings (tour_id, customer_name, customer_email, customer_phone, 
                            travel_date, number_of_travelers, total_amount, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $total_amount = $tour['price'] * $_POST['travelers'];
    
    $stmt->execute([
        $tour_id,
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['travel_date'],
        $_POST['travelers'],
        $total_amount
    ]);
    
    $booking_id = $pdo->lastInsertId();
    header("Location: /booking-confirmation.php?id=$booking_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?= htmlspecialchars($tour['title']) ?> - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Tour Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center mb-4">
                    <img src="<?= htmlspecialchars($tour['flag_url']) ?>" alt="<?= htmlspecialchars($tour['country_name']) ?>" class="w-8 h-6 mr-3">
                    <span class="text-gray-600"><?= htmlspecialchars($tour['country_name']) ?></span>
                </div>
                <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($tour['title']) ?></h1>
                <div class="grid md:grid-cols-2 gap-6">
                    <img src="<?= htmlspecialchars($tour['main_image']) ?>" alt="<?= htmlspecialchars($tour['title']) ?>" class="w-full h-64 object-cover rounded-lg">
                    <div>
                        <p class="text-gray-700 mb-4"><?= htmlspecialchars($tour['description']) ?></p>
                        <div class="space-y-2">
                            <p><strong>Duration:</strong> <?= htmlspecialchars($tour['duration']) ?></p>
                            <p><strong>Price per person:</strong> <span class="text-2xl font-bold text-green-600">$<?= number_format($tour['price']) ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Book This Tour</h2>
                <form method="POST" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Travel Date *</label>
                            <input type="date" name="travel_date" required min="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Travelers *</label>
                        <select name="travelers" required onchange="updateTotal()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Select number of travelers</option>
                            <?php for($i = 1; $i <= 20; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> <?= $i == 1 ? 'person' : 'people' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <!-- Total Amount Display -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center text-lg">
                            <span>Total Amount:</span>
                            <span id="total-amount" class="font-bold text-green-600">$0</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-yellow-600 text-white py-3 px-6 rounded-md hover:bg-yellow-700 font-semibold text-lg">
                        Confirm Booking
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function updateTotal() {
        const travelers = document.querySelector('select[name="travelers"]').value;
        const pricePerPerson = <?= $tour['price'] ?>;
        const total = travelers * pricePerPerson;
        document.getElementById('total-amount').textContent = '$' + total.toLocaleString();
    }
    </script>
</body>
</html>