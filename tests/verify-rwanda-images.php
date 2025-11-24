<?php
/**
 * Verify Rwanda Images
 * 
 * Simple CLI script to verify Rwanda hero images exist and are accessible
 */

// Include the main config
require_once 'config.php';

echo "🇷🇼 Rwanda Hero Images Verification\n";
echo "=====================================\n\n";

echo "📊 Configuration:\n";
echo "BASE_URL: " . BASE_URL . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'CLI mode') . "\n\n";

// Check Rwanda assets directory
$rwandaAssetsDir = 'countries/rwanda/assets/images';
echo "📁 Checking directory: $rwandaAssetsDir\n";

if (!is_dir($rwandaAssetsDir)) {
    echo "❌ Directory does not exist!\n";
    exit(1);
}

echo "✅ Directory exists\n\n";

// List all images in the directory
echo "🖼️ Images found:\n";
$images = glob($rwandaAssetsDir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

if (empty($images)) {
    echo "❌ No images found!\n";
    exit(1);
}

foreach ($images as $imagePath) {
    $filename = basename($imagePath);
    $filesize = filesize($imagePath);
    $filesizeKB = round($filesize / 1024, 2);
    
    echo "  📷 $filename ($filesizeKB KB)\n";
    
    // Generate URL using getImageUrl function
    $imageUrl = getImageUrl($imagePath);
    echo "     URL: $imageUrl\n";
    
    // Check if file is readable
    if (is_readable($imagePath)) {
        echo "     ✅ File is readable\n";
    } else {
        echo "     ❌ File is not readable\n";
    }
    
    echo "\n";
}

// Test the specific hero images used in Rwanda index.php
echo "🎯 Testing specific hero images used in Rwanda page:\n\n";

$heroImages = [
    'countries/rwanda/assets/images/hero-rwanda.jpg' => 'Primary Hero Image',
    'countries/rwanda/assets/images/rwanda-gorilla-hero.png' => 'Secondary Hero Image (Gorilla)',
    'countries/rwanda/assets/images/volucano.png' => 'Volcano Image',
    'countries/rwanda/assets/images/logo.png' => 'Logo Image'
];

foreach ($heroImages as $imagePath => $description) {
    echo "🔍 $description:\n";
    echo "   Path: $imagePath\n";
    
    if (file_exists($imagePath)) {
        $filesize = filesize($imagePath);
        $filesizeKB = round($filesize / 1024, 2);
        echo "   ✅ File exists ($filesizeKB KB)\n";
        
        $imageUrl = getImageUrl($imagePath);
        echo "   🔗 URL: $imageUrl\n";
        
        if (is_readable($imagePath)) {
            echo "   ✅ File is readable\n";
        } else {
            echo "   ❌ File is not readable\n";
        }
    } else {
        echo "   ❌ File does not exist!\n";
    }
    echo "\n";
}

echo "🎉 Verification complete!\n";
echo "\n📝 Next steps:\n";
echo "1. Open http://localhost/foreveryoungtours/test-rwanda-hero-images.php in browser\n";
echo "2. Open http://localhost/foreveryoungtours/countries/rwanda/ to see the updated page\n";
echo "3. Test subdomain: http://visit-rw.foreveryoungtours.local/ (if configured)\n";
?>
