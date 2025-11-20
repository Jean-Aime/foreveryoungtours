<?php
require_once '../../config/database.php';

// Get all tours from African countries
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name, c.image_url as flag_url, c.country_code as code
    FROM tours t 
    JOIN countries c ON t.country_id = c.id 
    JOIN regions r ON c.region_id = r.id
    WHERE r.name = 'Africa' AND t.status = 'active'
    ORDER BY c.name, t.featured DESC, t.created_at DESC
");
$stmt->execute();
$tours = $stmt->fetchAll();

// Group tours by country
$tours_by_country = [];
foreach($tours as $tour) {
    $tours_by_country[$tour['country_name']][] = $tour;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Africa Tours - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-gradient-to-r from-yellow-600 to-orange-500 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-2">All Africa Tours</h1>
            <p class="text-xl">Complete collection of African experiences</p>
        </div>
    </header>

    <section class="container mx-auto px-4 py-12">
        <?php foreach($tours_by_country as $country => $country_tours): ?>
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <img src="<?= htmlspecialchars($country_tours[0]['flag_url']) ?>" alt="<?= htmlspecialchars($country) ?>" class="w-8 h-6 mr-3">
                <h2 class="text-3xl font-bold"><?= htmlspecialchars($country) ?></h2>
                <span class="ml-4 bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm"><?= count($country_tours) ?> tours</span>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($country_tours as $tour): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <img src="<?= htmlspecialchars($tour['main_image']) ?>" alt="<?= htmlspecialchars($tour['title']) ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><?= htmlspecialchars($tour['title']) ?></h3>
                        <p class="text-gray-600 text-sm mb-3"><?= substr(htmlspecialchars($tour['description']), 0, 100) ?>...</p>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xl font-bold text-green-600">$<?= number_format($tour['price']) ?></span>
                            <span class="text-gray-500 text-sm"><?= htmlspecialchars($tour['duration']) ?></span>
                        </div>
                        <button onclick="bookTour(<?= $tour['id'] ?>)" class="w-full bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700">
                            Book Now
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>

    <script>
    function bookTour(tourId) {
        window.location.href = '/booking.php?tour_id=' + tourId;
    }
    </script>
</body>
</html>