<?php
/**
 * Apply Universal Country Template to All Countries
 */

$base_dir = __DIR__;
$template_file = "$base_dir/countries/template-country.php";

if (!file_exists($template_file)) {
    die("Template file not found!\n");
}

$template_content = file_get_contents($template_file);

$countries_dir = "$base_dir/countries";
$country_folders = array_diff(scandir($countries_dir), ['.', '..', 'index.php', 'template-country.php']);

echo "Applying country template to all countries...\n\n";

foreach ($country_folders as $country) {
    $country_path = "$countries_dir/$country";
    
    if (is_dir($country_path)) {
        $index_file = "$country_path/index.php";
        
        // Backup existing file if it exists
        if (file_exists($index_file)) {
            $backup_file = "$country_path/index.php.backup." . date('YmdHis');
            copy($index_file, $backup_file);
            echo "  Backed up: $country/index.php\n";
        }
        
        // Write new template
        file_put_contents($index_file, $template_content);
        echo "✓ Updated: countries/$country/index.php\n";
    }
}

echo "\n✅ ALL COUNTRY PAGES UPDATED!\n";
echo "\nAll countries now have:\n";
echo "- Proper config.php includes\n";
echo "- BASE_URL image handling\n";
echo "- Consistent navigation\n";
echo "- Responsive design\n";
echo "- Working tour listings\n";
?>
