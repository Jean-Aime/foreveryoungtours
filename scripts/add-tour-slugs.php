<?php
require_once '../config/database.php';

// Add slug column if not exists
try {
    $pdo->exec("ALTER TABLE tours ADD COLUMN slug VARCHAR(255) UNIQUE AFTER name");
    echo "✓ Slug column added\n";
} catch (PDOException $e) {
    echo "Slug column already exists or error: " . $e->getMessage() . "\n";
}

// Generate slugs for existing tours
$stmt = $pdo->query("SELECT id, name FROM tours WHERE slug IS NULL OR slug = ''");
$tours = $stmt->fetchAll();

foreach ($tours as $tour) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tour['name']), '-'));
    
    $update = $pdo->prepare("UPDATE tours SET slug = ? WHERE id = ?");
    $update->execute([$slug, $tour['id']]);
    
    echo "✓ Generated slug for: {$tour['name']} → {$slug}\n";
}

echo "\n✓ All done! Tours now have slugs.\n";
?>
