<?php
$base_dir = dirname(__DIR__);
$countries_dir = $base_dir . '/countries';

$files = [];
foreach (glob($countries_dir . '/*/pages/packages.php') as $f) $files[] = $f;
foreach (glob($countries_dir . '/*/pages/tour-detail.php') as $f) $files[] = $f;

echo "Found " . count($files) . " files\n\n";

foreach ($files as $file) {
    echo "Processing: $file\n";
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Fix tour detail links to use main domain BASE_URL
    $content = preg_replace(
        '/<a href="\.\.\/\.\.\/\.\.\/tour\/<\?php echo \$tour\[\'slug\'\]; \?>"/s',
        '<a href="<?php echo BASE_URL; ?>/tour/<?php echo $tour[\'slug\']; ?>"',
        $content
    );
    
    $content = preg_replace(
        '/<a href="\.\.\/\.\.\/\.\.\/tour\/<\?php echo \$related\[\'slug\'\]; \?>"/s',
        '<a href="<?php echo BASE_URL; ?>/tour/<?php echo $related[\'slug\']; ?>"',
        $content
    );
    
    // Fix packages URL in tour-detail sidebar
    $content = preg_replace(
        '/\$packages_url = "http:\/\/visit-\{\$country_subdomain\}\.foreveryoungtours\.local\/pages\/packages\.php";/s',
        '$packages_url = BASE_URL . "/pages/packages.php";',
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "  ✓ Fixed\n";
    } else {
        echo "  - Already correct\n";
    }
}

echo "\n✓ Done!\n";
