<?php
echo "<h1>Rwanda Subdomain Test Page</h1>";
echo "<h2>✅ This page loaded successfully!</h2>";
echo "<pre>";

echo "=== SERVER INFO ===\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

echo "=== GET PARAMETERS ===\n";
print_r($_GET);

echo "\n=== DATABASE TEST ===\n";
try {
    require_once '../../../config/database.php';
    echo "✅ Database connected\n";
    
    if (isset($_GET['id'])) {
        $tour_id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT name, cover_image FROM tours WHERE id = ?");
        $stmt->execute([$tour_id]);
        $tour = $stmt->fetch();
        
        if ($tour) {
            echo "✅ Tour found: " . $tour['name'] . "\n";
            echo "Cover image: " . ($tour['cover_image'] ?: 'None') . "\n";
        } else {
            echo "❌ Tour not found\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== FILE PATHS ===\n";
echo "Current directory: " . getcwd() . "\n";
echo "Script directory: " . __DIR__ . "\n";

echo "</pre>";

echo "<p><strong>If you can see this page, the subdomain routing is working!</strong></p>";
echo "<p>Next step: Test the actual tour-detail.php page</p>";
?>
