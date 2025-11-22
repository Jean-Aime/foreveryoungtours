<?php
/**
 * Convert all image paths in the project to use BASE_URL
 * This ensures images work on main domain, subdomains, and live server
 */

echo "<h1>🔄 Converting All Image Paths to BASE_URL</h1>";
echo "<pre>";

$fixed_count = 0;
$error_count = 0;

// Function to add config include to a file
function addConfigInclude($content) {
    // Check if config.php is already included
    if (strpos($content, "require_once 'config.php'") !== false || 
        strpos($content, 'require_once "config.php"') !== false ||
        strpos($content, "include 'config.php'") !== false ||
        strpos($content, 'include "config.php"') !== false) {
        return $content;
    }
    
    // Find the first <?php tag and add the include after it
    if (preg_match('/^(<\?php\s*)/', $content, $matches)) {
        $replacement = $matches[1] . "\nrequire_once 'config.php';\n";
        return preg_replace('/^<\?php\s*/', $replacement, $content, 1);
    }
    
    return $content;
}

// Function to replace image path functions
function replaceImageFunctions($content) {
    // Replace various image path functions with getImageUrl calls
    $patterns = [
        // Replace fixImagePath calls
        '/fixImagePath\s*\(\s*([^)]+)\s*\)/' => 'getImageUrl($1)',
        
        // Replace fixImageSrc calls
        '/fixImageSrc\s*\(\s*([^)]+)\s*\)/' => 'getImageUrl($1)',
        
        // Replace getImagePath calls
        '/getImagePath\s*\(\s*([^)]+)\s*\)/' => 'getImageUrl($1)',
        
        // Replace direct relative paths in src attributes
        '/src=["\']\.\.\/\.\.\/\.\.\/uploads\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'uploads/$1\') ?>"',
        '/src=["\']\.\.\/\.\.\/uploads\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'uploads/$1\') ?>"',
        '/src=["\']\.\.\/uploads\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'uploads/$1\') ?>"',
        '/src=["\']uploads\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'uploads/$1\') ?>"',
        
        // Replace direct relative paths in src attributes for assets
        '/src=["\']\.\.\/\.\.\/\.\.\/assets\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'assets/$1\') ?>"',
        '/src=["\']\.\.\/\.\.\/assets\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'assets/$1\') ?>"',
        '/src=["\']\.\.\/assets\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'assets/$1\') ?>"',
        '/src=["\']assets\/([^"\']+)["\']/' => 'src="<?= getImageUrl(\'assets/$1\') ?>"',
        
        // Replace background-image URLs
        '/background-image:\s*url\(["\']\.\.\/\.\.\/\.\.\/uploads\/([^"\']+)["\']\)/' => 'background-image: url("<?= getImageUrl(\'uploads/$1\') ?>")',
        '/background-image:\s*url\(["\']uploads\/([^"\']+)["\']\)/' => 'background-image: url("<?= getImageUrl(\'uploads/$1\') ?>")',
        
        // Replace style background-image
        '/style=["\'][^"\']*background-image:\s*url\(["\']\.\.\/\.\.\/\.\.\/uploads\/([^"\']+)["\']\)/' => 'style="background-image: url(\'<?= getImageUrl(\'uploads/$1\') ?>\');"',
    ];
    
    foreach ($patterns as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    return $content;
}

// Function to remove old image path functions
function removeOldFunctions($content) {
    // Remove old function definitions
    $patterns = [
        '/\/\*\*[^*]*\*+(?:[^*\/][^*]*\*+)*\/\s*function\s+fixImagePath\s*\([^{]*\{[^}]*\}/',
        '/function\s+fixImagePath\s*\([^{]*\{[^}]*\}/',
        '/function\s+fixImageSrc\s*\([^{]*\{[^}]*\}/',
        '/function\s+getImagePath\s*\([^{]*\{[^}]*\}/',
        '/\/\/ Function to fix image paths[^\n]*\n\s*function\s+fixImagePath\s*\([^{]*\{[^}]*\}/',
        '/\/\/ Smart image path function[^\n]*\n\s*function\s+getImagePath\s*\([^{]*\{[^}]*\}/',
    ];
    
    foreach ($patterns as $pattern) {
        $content = preg_replace($pattern, '', $content);
    }
    
    return $content;
}

// Get all PHP files to process
$files_to_process = [];

// Add main pages
$main_dirs = ['pages', 'admin', 'client', 'mca', 'advisor'];
foreach ($main_dirs as $dir) {
    if (is_dir($dir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files_to_process[] = $file->getPathname();
            }
        }
    }
}

// Add country pages
if (is_dir('countries')) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('countries'));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files_to_process[] = $file->getPathname();
        }
    }
}

// Add root level files
$root_files = ['index.php', 'subdomain-handler.php'];
foreach ($root_files as $file) {
    if (file_exists($file)) {
        $files_to_process[] = $file;
    }
}

echo "Found " . count($files_to_process) . " PHP files to process\n\n";

foreach ($files_to_process as $file_path) {
    echo "Processing: " . $file_path . "... ";
    
    try {
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // Skip if file doesn't contain image references
        if (!preg_match('/(src=|background-image|uploads\/|assets\/images|fixImagePath|fixImageSrc|getImagePath)/', $content)) {
            echo "⏭️ No image references\n";
            continue;
        }
        
        // Add config include
        $content = addConfigInclude($content);
        
        // Replace image functions and paths
        $content = replaceImageFunctions($content);
        
        // Remove old function definitions
        $content = removeOldFunctions($content);
        
        // Clean up extra whitespace
        $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
        
        if ($content !== $original_content) {
            if (file_put_contents($file_path, $content)) {
                echo "✅ Updated\n";
                $fixed_count++;
            } else {
                echo "❌ Failed to write\n";
                $error_count++;
            }
        } else {
            echo "⏭️ No changes needed\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        $error_count++;
    }
}

echo "\n";
echo "=== SUMMARY ===\n";
echo "Files updated: $fixed_count\n";
echo "Errors: $error_count\n";
echo "Total processed: " . count($files_to_process) . "\n";
echo "\n";
echo "✅ BASE_URL conversion complete!\n";
echo "All image paths now use absolute URLs that work on:\n";
echo "- Main domain (localhost/foreveryoungtours)\n";
echo "- Subdomains (visit-rw.foreveryoungtours.local)\n";
echo "- Live server (when you update BASE_URL in config.php)\n";
echo "\n";
echo "Next steps:\n";
echo "1. Test on main domain: http://localhost/foreveryoungtours/\n";
echo "2. Test on subdomain: http://visit-rw.foreveryoungtours.local/\n";
echo "3. Update BASE_URL in config.php for live deployment\n";

echo "</pre>";
?>
