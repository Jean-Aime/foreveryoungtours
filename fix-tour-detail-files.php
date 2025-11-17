<?php
/**
 * Fix tour-detail.php files by copying from Rwanda master
 */

echo "=" . str_repeat("=", 100) . "\n";
echo "FIXING TOUR-DETAIL.PHP FILES\n";
echo "=" . str_repeat("=", 100) . "\n\n";

$source_file = 'countries/rwanda/pages/tour-detail.php';

if (!file_exists($source_file)) {
    die("ERROR: Source file not found: $source_file\n");
}

$countries = [
    'kenya', 'tanzania', 'uganda', 'south-africa',
    'egypt', 'morocco', 'botswana', 'namibia', 'zimbabwe',
    'ghana', 'nigeria', 'ethiopia', 'senegal', 'tunisia',
    'cameroon', 'dr-congo', 'democratic-republic-of-congo'
];

$fixed = 0;
$failed = 0;

foreach ($countries as $country) {
    $target_file = "countries/$country/pages/tour-detail.php";
    
    if (copy($source_file, $target_file)) {
        echo "✅ Fixed $target_file\n";
        $fixed++;
    } else {
        echo "❌ Failed to fix $target_file\n";
        $failed++;
    }
}

echo "\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "SUMMARY\n";
echo "=" . str_repeat("=", 100) . "\n";
echo "Fixed: $fixed files\n";
echo "Failed: $failed files\n";
echo "\n";

if ($fixed > 0) {
    echo "✅ FIXED $fixed TOUR-DETAIL.PHP FILES!\n";
}

echo "\n";
?>

