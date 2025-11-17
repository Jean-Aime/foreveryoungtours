<?php
require_once 'config.php';

echo "<h1>🧪 BASE_URL Configuration Test</h1>";
echo "<hr>";

echo "<h2>📊 Environment Detection</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Property</th><th>Value</th></tr>";
echo "<tr><td>HTTP_HOST</td><td>" . ($_SERVER['HTTP_HOST'] ?? 'NOT SET') . "</td></tr>";
echo "<tr><td>REQUEST_URI</td><td>" . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</td></tr>";
echo "<tr><td>HTTPS</td><td>" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'YES' : 'NO') . "</td></tr>";
echo "<tr><td><strong>BASE_URL</strong></td><td><strong>" . BASE_URL . "</strong></td></tr>";
echo "</table>";

echo "<h2>🖼️ Image URL Tests</h2>";

$test_images = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    'uploads/tours/28_main_1763207330_6197.png',
    'uploads/tours/28_gallery_0_1763207330_1676.png',
    'assets/images/default-tour.jpg',
    'assets/images/africa.png',
    '../../../uploads/tours/old-relative-path.jpg',
    '../../assets/images/old-relative-path.jpg',
    'http://external.com/external-image.jpg',
    '',  // Empty path test
];

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Input Path</th><th>Generated URL</th><th>Status</th></tr>";

foreach ($test_images as $image) {
    $generated_url = getImageUrl($image);
    
    // Check if file exists (only for local paths)
    $status = '';
    if (strpos($generated_url, 'http') === 0) {
        if (strpos($generated_url, BASE_URL) === 0) {
            // Local absolute URL - check if file exists
            $relative_path = str_replace(BASE_URL . '/', '', $generated_url);
            $status = file_exists($relative_path) ? '✅ EXISTS' : '❌ NOT FOUND';
        } else {
            $status = '🌐 EXTERNAL';
        }
    } else {
        $status = '⚠️ NOT ABSOLUTE';
    }
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($image ?: '(empty)') . "</td>";
    echo "<td>" . htmlspecialchars($generated_url) . "</td>";
    echo "<td>" . $status . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>🎨 Visual Test</h2>";
echo "<p>Testing actual image display:</p>";

$visual_test_images = [
    'uploads/tours/28_cover_1763207330_5662.jpeg',
    'uploads/tours/28_main_1763207330_6197.png',
    'assets/images/default-tour.jpg'
];

foreach ($visual_test_images as $index => $image) {
    $url = getImageUrl($image);
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0; display: inline-block;'>";
    echo "<p><strong>Test " . ($index + 1) . ":</strong></p>";
    echo "<p><strong>Input:</strong> " . htmlspecialchars($image) . "</p>";
    echo "<p><strong>URL:</strong> " . htmlspecialchars($url) . "</p>";
    echo "<img src='" . htmlspecialchars($url) . "' style='max-width: 200px; height: auto; border: 1px solid #ddd;' ";
    echo "onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
    echo "<div style='display: none; background: #f0f0f0; padding: 20px; text-align: center; color: red;'>❌ Image not found</div>";
    echo "</div>";
}

echo "<h2>🔗 Test Links</h2>";
echo "<div style='background: #f9f9f9; padding: 15px; border-radius: 5px;'>";
echo "<h3>Main Domain Tests:</h3>";
echo "<p><a href='http://localhost/foreveryoungtours/test-base-url.php' target='_blank'>🔗 Main Domain Test</a></p>";
echo "<p><a href='http://localhost/foreveryoungtours/pages/tour-detail?id=28' target='_blank'>🔗 Main Tour Detail</a></p>";

echo "<h3>Subdomain Tests:</h3>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/test-base-url.php' target='_blank'>🔗 Subdomain Test</a></p>";
echo "<p><a href='http://visit-rw.foreveryoungtours.local/pages/tour-detail?id=28' target='_blank'>🔗 Subdomain Tour Detail</a></p>";

echo "<h3>Country Direct Tests:</h3>";
echo "<p><a href='http://localhost/foreveryoungtours/countries/rwanda/pages/tour-detail?id=28' target='_blank'>🔗 Direct Rwanda Tour Detail</a></p>";
echo "</div>";

echo "<h2>📝 Usage Examples</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px;'>";
echo "<h3>In PHP:</h3>";
echo "<pre>";
echo htmlspecialchars('<?php
require_once \'config.php\';

// Simple usage
echo \'<img src="\' . getImageUrl(\'uploads/tours/image.jpg\') . \'" alt="Tour">\';

// With fallback
echo \'<img src="\' . getImageUrl($tour[\'cover_image\'], \'assets/images/default.jpg\') . \'" alt="Tour">\';

// In background style
echo \'<div style="background-image: url(\\\'\' . getImageUrl($image) . \'\\\')"></div>\';
?>');
echo "</pre>";

echo "<h3>Direct in HTML:</h3>";
echo "<pre>";
echo htmlspecialchars('<img src="<?= getImageUrl(\'uploads/tours/image.jpg\') ?>" alt="Tour">
<div style="background-image: url(\'<?= getImageUrl($image) ?>\')"></div>');
echo "</pre>";
echo "</div>";

echo "<h2>⚙️ Configuration</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>Current BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>For live deployment:</strong> Update the BASE_URL in config.php to your live domain</p>";
echo "<p><strong>Example:</strong> <code>define('BASE_URL', 'https://foreveryoungtours.com');</code></p>";
echo "</div>";
?>
