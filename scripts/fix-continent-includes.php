<?php
$continents = ['africa', 'asia', 'europe', 'north-america', 'south-america', 'oceania', 'caribbean', 
               'central-africa', 'east-africa', 'north-africa', 'southern-africa', 'west-africa'];

foreach ($continents as $continent) {
    $file = __DIR__ . '/continents/' . $continent . '/index.php';
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Remove problematic includes
        $content = preg_replace('/<\?php\s+include\s+[\'"]enhanced-booking-modal\.php[\'"]\s*;\s*\?>/i', '', $content);
        $content = preg_replace('/<\?php\s+include\s+[\'"]inquiry-modal\.php[\'"]\s*;\s*\?>/i', '', $content);
        $content = preg_replace('/<\?php\s+include\s+[\'"]\.\.\/\.\.\/includes\/footer\.php[\'"]\s*;\s*\?>/i', '', $content);
        
        // Ensure footer exists before </body>
        if (strpos($content, '<footer') === false && strpos($content, '</body>') !== false) {
            $footer = "\n<!-- Footer -->\n<footer class=\"bg-gray-900 text-white py-12\">\n    <div class=\"max-w-7xl mx-auto px-4 text-center\">\n        <p>&copy; 2025 iForYoungTours. All rights reserved.</p>\n    </div>\n</footer>\n\n";
            $content = str_replace('</body>', $footer . '</body>', $content);
        }
        
        file_put_contents($file, $content);
        echo "✓ Fixed: continents/$continent/index.php\n";
    } else {
        echo "✗ Not found: continents/$continent/index.php\n";
    }
}

echo "\n✅ All continent pages fixed!\n";
?>
