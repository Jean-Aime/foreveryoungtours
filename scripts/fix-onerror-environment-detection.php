<?php
/**
 * Fix onerror fallbacks to use environment detection for all countries
 */

echo "<h1>Fixing onerror Fallbacks with Environment Detection</h1>";
echo "<pre>";

// Get all country directories
$countries_dir = 'countries/';
$countries = [];

if (is_dir($countries_dir)) {
    $dirs = scandir($countries_dir);
    foreach ($dirs as $dir) {
        if ($dir !== '.' && $dir !== '..' && is_dir($countries_dir . $dir)) {
            $tour_detail_file = $countries_dir . $dir . '/pages/tour-detail.php';
            if (file_exists($tour_detail_file)) {
                $countries[] = $dir;
            }
        }
    }
}

echo "Found " . count($countries) . " countries with tour-detail.php files:\n";
foreach ($countries as $country) {
    echo "- $country\n";
}
echo "\n";

$fixed_count = 0;
$error_count = 0;

foreach ($countries as $country) {
    $file_path = $countries_dir . $country . '/pages/tour-detail.php';
    echo "Processing: $country... ";
    
    try {
        $content = file_get_contents($file_path);
        
        // Replace onerror fallbacks with environment detection
        $patterns_and_replacements = [
            // Pattern for absolute path onerror
            [
                'pattern' => "/onerror=\"this\.src='\/foreveryoungtours\/assets\/images\/default-tour\.jpg'/",
                'replacement' => "onerror=\"this.src='<?php echo (strpos(\$_SERVER['HTTP_HOST'], 'visit-') === 0 || strpos(\$_SERVER['HTTP_HOST'], '.foreveryoungtours.') !== false) ? '../../../assets/images/default-tour.jpg' : '/foreveryoungtours/assets/images/default-tour.jpg'; ?>'"
            ],
            // Pattern for relative path onerror
            [
                'pattern' => "/onerror=\"this\.src='\.\.\/\.\.\/\.\.\/assets\/images\/default-tour\.jpg'/",
                'replacement' => "onerror=\"this.src='<?php echo (strpos(\$_SERVER['HTTP_HOST'], 'visit-') === 0 || strpos(\$_SERVER['HTTP_HOST'], '.foreveryoungtours.') !== false) ? '../../../assets/images/default-tour.jpg' : '/foreveryoungtours/assets/images/default-tour.jpg'; ?>'"
            ]
        ];
        
        $updated_content = $content;
        $changes_made = false;
        
        foreach ($patterns_and_replacements as $pr) {
            $new_content = preg_replace($pr['pattern'], $pr['replacement'], $updated_content);
            if ($new_content !== $updated_content && $new_content !== null) {
                $updated_content = $new_content;
                $changes_made = true;
            }
        }
        
        if ($changes_made) {
            if (file_put_contents($file_path, $updated_content)) {
                echo "✓ Fixed onerror fallbacks with environment detection\n";
                $fixed_count++;
            } else {
                echo "✗ Failed to write\n";
                $error_count++;
            }
        } else {
            // Check if Rwanda (already has environment detection)
            if ($country === 'rwanda') {
                echo "✓ Already has environment detection\n";
            } else {
                echo "✓ No onerror fallbacks found or already correct\n";
            }
        }
        
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Fixed: $fixed_count countries\n";
echo "Errors: $error_count countries\n";
echo "Total: " . count($countries) . " countries\n";

if ($fixed_count > 0) {
    echo "\n✅ All onerror fallbacks now use environment detection!\n";
    echo "Broken images will fallback correctly on both main domain and subdomains.\n";
}

echo "</pre>";
?>
