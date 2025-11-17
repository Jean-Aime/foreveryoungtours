<?php
/**
 * Fix Syntax Errors in All Files
 */

echo "=" . str_repeat("=", 100) . "\n";
echo "FIXING SYNTAX ERRORS\n";
echo "=" . str_repeat("=", 100) . "\n\n";

$fixed = 0;
$failed = 0;

// 1. Fix continent-theme.php files
echo "1. FIXING CONTINENT-THEME.PHP FILES\n";
echo str_repeat("-", 100) . "\n";

$countries = [
    'rwanda', 'kenya', 'tanzania', 'uganda', 'south-africa',
    'egypt', 'morocco', 'botswana', 'namibia', 'zimbabwe',
    'ghana', 'nigeria', 'ethiopia', 'senegal', 'tunisia',
    'cameroon', 'dr-congo', 'democratic-republic-of-congo'
];

foreach ($countries as $country) {
    $file = "countries/$country/continent-theme.php";
    
    if (!file_exists($file)) {
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Fix the syntax error on line 43
    $old_line = "    echo '<script src=\"<?= getImageUrl('assets/js/africa-theme.js') ?>\"></script>';";
    $new_line = "    echo '<script src=\"' . getImageUrl('assets/js/africa-theme.js') . '\"></script>';";
    
    $content = str_replace($old_line, $new_line, $content);
    
    if (file_put_contents($file, $content)) {
        echo "   ✅ Fixed $file\n";
        $fixed++;
    } else {
        echo "   ❌ Failed to fix $file\n";
        $failed++;
    }
}

// 2. Fix tour-detail.php files
echo "\n2. FIXING TOUR-DETAIL.PHP FILES\n";
echo str_repeat("-", 100) . "\n";

foreach ($countries as $country) {
    $file = "countries/$country/pages/tour-detail.php";
    
    if (!file_exists($file)) {
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Check if file starts with <?php
    if (strpos($content, '<?php') !== 0) {
        // File doesn't start with <?php, add it
        $content = "<?php\n" . $content;
        
        if (file_put_contents($file, $content)) {
            echo "   ✅ Fixed $file (added <?php tag)\n";
            $fixed++;
        } else {
            echo "   ❌ Failed to fix $file\n";
            $failed++;
        }
    } else {
        // Check for other syntax issues
        // Look for standalone 'else' without matching 'if'
        $lines = explode("\n", $content);
        $has_error = false;
        
        foreach ($lines as $i => $line) {
            if (trim($line) === 'else {' || trim($line) === '} else {') {
                // Check if there's a matching if before
                $found_if = false;
                for ($j = $i - 1; $j >= 0; $j--) {
                    if (strpos($lines[$j], 'if') !== false) {
                        $found_if = true;
                        break;
                    }
                }
                
                if (!$found_if) {
                    $has_error = true;
                    break;
                }
            }
        }
        
        if ($has_error) {
            echo "   ⚠️  $file has syntax error but auto-fix not available\n";
        }
    }
}

// 3. Fix blog.php
echo "\n3. FIXING BLOG.PHP\n";
echo str_repeat("-", 100) . "\n";

$blog_file = "pages/blog.php";
if (file_exists($blog_file)) {
    $content = file_get_contents($blog_file);
    
    // Check for unmatched endif
    $if_count = substr_count($content, '<?php if');
    $endif_count = substr_count($content, 'endif');
    
    if ($endif_count > $if_count) {
        echo "   ⚠️  blog.php has unmatched endif (manual fix needed)\n";
        echo "      Found $if_count 'if' statements but $endif_count 'endif' statements\n";
    } else {
        echo "   ✅ blog.php structure looks OK\n";
    }
}

// Summary
echo "\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "SUMMARY\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "Fixed: $fixed files\n";
echo "Failed: $failed files\n";
echo "\n";

if ($fixed > 0) {
    echo "✅ FIXED $fixed FILES!\n";
    echo "   Run check-all-php-syntax.php again to verify\n";
}

echo "\n";
?>

