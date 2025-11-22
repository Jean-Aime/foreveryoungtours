<?php
// Debug subdomain access
echo "<h2>🔍 Subdomain Debug Information</h2>";

echo "<h3>Server Information:</h3>";
echo "<p><strong>HTTP_HOST:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</p>";
echo "<p><strong>DOCUMENT_ROOT:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "</p>";

echo "<h3>File System Check:</h3>";
$africa_path = __DIR__ . '/continents/africa/index.php';
echo "<p><strong>Africa Index Path:</strong> $africa_path</p>";
echo "<p><strong>Africa Index Exists:</strong> " . (file_exists($africa_path) ? "✅ YES" : "❌ NO") . "</p>";

if (file_exists($africa_path)) {
    echo "<p><strong>Africa Index Readable:</strong> " . (is_readable($africa_path) ? "✅ YES" : "❌ NO") . "</p>";
    echo "<p><strong>File Size:</strong> " . filesize($africa_path) . " bytes</p>";
}

echo "<h3>Directory Listing:</h3>";
$continents_dir = __DIR__ . '/continents';
if (is_dir($continents_dir)) {
    $dirs = scandir($continents_dir);
    foreach ($dirs as $dir) {
        if ($dir !== '.' && $dir !== '..') {
            $index_file = "$continents_dir/$dir/index.php";
            $exists = file_exists($index_file) ? "✅" : "❌";
            echo "<p>$exists <strong>$dir</strong> → $index_file</p>";
        }
    }
}

echo "<h3>Test Links:</h3>";
echo "<p><a href='continents/africa/' target='_blank'>Test Africa (Local)</a></p>";
echo "<p><a href='https://africa.iforeveryoungtours.com/' target='_blank'>Test Africa (Production)</a></p>";
?>
