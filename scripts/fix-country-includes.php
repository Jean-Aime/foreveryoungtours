<?php
$countries_dir = __DIR__ . '/countries';
$folders = array_diff(scandir($countries_dir), ['.', '..', 'index.php', 'template-country.php']);

$fixed = 0;
foreach ($folders as $country) {
    $file = "$countries_dir/$country/index.php";
    if (is_dir("$countries_dir/$country") && file_exists($file)) {
        $content = file_get_contents($file);
        $original = $content;
        
        // Remove problematic includes
        $content = preg_replace('/<\?php\s+include\s+[\'"]enhanced-booking-modal\.php[\'"]\s*;\s*\?>/i', '', $content);
        $content = preg_replace('/<\?php\s+include\s+[\'"]\.\.\/\.\.\/pages\/enhanced-booking-modal\.php[\'"]\s*;\s*\?>/i', '', $content);
        $content = preg_replace('/<\?php\s+include\s+[\'"]includes\/footer\.php[\'"]\s*;\s*\?>/i', '', $content);
        
        // Ensure footer exists
        if (strpos($content, '<footer') === false && strpos($content, '</body>') !== false) {
            $footer = "\n<!-- Footer -->\n<footer class=\"bg-gray-900 text-white py-12\">\n    <div class=\"max-w-7xl mx-auto px-4 text-center\">\n        <p>&copy; 2025 iForYoungTours. All rights reserved.</p>\n    </div>\n</footer>\n\n";
            $content = str_replace('</body>', $footer . '</body>', $content);
        }
        
        if ($content !== $original) {
            file_put_contents($file, $content);
            echo "✓ Fixed: countries/$country/index.php\n";
            $fixed++;
        }
    }
}

echo "\n✅ Fixed $fixed country pages!\n";
?>
