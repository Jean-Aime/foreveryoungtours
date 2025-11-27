<?php
require_once '../../config/database.php';
require_once 'config.php';

// Get all tours for Rwanda
$stmt = $pdo->prepare("
    SELECT t.*, c.name as country_name 
    FROM tours t 
    JOIN countries c ON t.country_id = c.id 
    WHERE c.slug = 'visit-rw' AND t.status = 'active'
    ORDER BY t.featured DESC, t.created_at DESC
");
$stmt->execute();
$tours = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Rwanda Tours - iForYoungTours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-yellow-600 to-orange-500 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-2">All Rwanda Tours</h1>
            <p class="text-xl">Discover the complete collection of Rwanda experiences</p>
        </div>
    </header>

    <!-- Tours Grid -->
    <section class="container mx-auto px-4 py-12">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($tours as $tour): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <img src="<?= getImageUrl($tour['image_url'] ?: $tour['cover_image'], 'countries/rwanda/assets/images/hero-rwanda.jpg') ?>" alt="<?= htmlspecialchars($tour['name']) ?>" class="w-full h-48 object-cover">
                <div class="p-6">
                    <?php if($tour['featured']): ?>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold mb-2">Featured</span>
                    <?php endif; ?>
                    <h3 class="font-bold text-xl mb-2"><?= htmlspecialchars($tour['name']) ?></h3>
                    <p class="text-gray-600 text-sm mb-4"><?= substr(htmlspecialchars($tour['description']), 0, 120) ?>...</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-green-600">$<?= number_format($tour['price']) ?></span>
                        <span class="text-gray-500"><?= htmlspecialchars($tour['duration']) ?></span>
                    </div>
                    <div class="flex gap-2">
                        <a href="/booking.php?tour_id=<?= $tour['id'] ?>" class="flex-1 bg-yellow-600 text-white py-2 px-4 rounded text-center hover:bg-yellow-700">
                            Book Now
                        </a>
                        <a href="/tour-details.php?id=<?= $tour['id'] ?>" class="flex-1 border border-yellow-600 text-yellow-600 py-2 px-4 rounded text-center hover:bg-yellow-50">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(empty($tours)): ?>
        <div class="text-center py-12">
            <h3 class="text-2xl font-bold text-gray-600 mb-4">No tours available</h3>
            <p class="text-gray-500">Check back soon for new Rwanda experiences!</p>
        </div>
        <?php endif; ?>
    </section>
</body>
</html>