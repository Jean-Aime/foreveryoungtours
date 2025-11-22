<?php
require_once 'config/database.php';

// Get Rwanda country ID
$stmt = $pdo->query("SELECT id FROM countries WHERE slug='rwanda'");
$rwanda = $stmt->fetch();

if (!$rwanda) {
    die("❌ Rwanda not found in database. Run insert-rwanda.php first.");
}

$tours = [
    [
        'name' => '6 Days Rwanda Premium Primate Safari',
        'slug' => '6-days-rwanda-primate-safari',
        'description' => 'Experience gorillas, chimps, and golden monkeys in their natural habitat',
        'price' => 4600,
        'duration' => '6 Days / 5 Nights',
        'duration_days' => 6,
        'category' => 'wildlife',
        'featured' => 1,
        'status' => 'active'
    ],
    [
        'name' => '8 Days Rwanda Complete Wildlife Experience',
        'slug' => '8-days-rwanda-complete',
        'description' => 'Gorillas, Big Five safari, and cultural immersion',
        'price' => 5800,
        'duration' => '8 Days / 7 Nights',
        'duration_days' => 8,
        'category' => 'wildlife',
        'featured' => 1,
        'status' => 'active'
    ],
    [
        'name' => '10 Days Rwanda Grand Tour',
        'slug' => '10-days-rwanda-grand-tour',
        'description' => 'Ultimate Rwanda experience with all highlights',
        'price' => 7200,
        'duration' => '10 Days / 9 Nights',
        'duration_days' => 10,
        'category' => 'adventure',
        'featured' => 1,
        'status' => 'active'
    ]
];

// Get first user ID or create default
$stmt = $pdo->query("SELECT id FROM users LIMIT 1");
$user = $stmt->fetch();
$user_id = $user ? $user['id'] : 1;

foreach ($tours as $tour) {
    $sql = "INSERT INTO tours (country_id, name, slug, description, price, duration, duration_days, category, featured, status, created_by) 
            VALUES (:country_id, :name, :slug, :description, :price, :duration, :duration_days, :category, :featured, :status, :created_by)
            ON DUPLICATE KEY UPDATE status='active'";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'country_id' => $rwanda['id'],
        'name' => $tour['name'],
        'slug' => $tour['slug'],
        'description' => $tour['description'],
        'price' => $tour['price'],
        'duration' => $tour['duration'],
        'duration_days' => $tour['duration_days'],
        'category' => $tour['category'],
        'featured' => $tour['featured'],
        'status' => $tour['status'],
        'created_by' => $user_id
    ]);
}

echo "✅ Added 3 Rwanda tours successfully!";
?>
