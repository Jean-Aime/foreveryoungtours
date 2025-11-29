<?php
$file = 'c:\\xampp1\\htdocs\\foreveryoungtours\\pages\\tour-detail.php';
$content = file_get_contents($file);

// Remove the complex setTimeout script
$pattern = '/<script>\s*setTimeout\(function\(\) \{[\s\S]*?<\/script>/';
$content = preg_replace($pattern, '', $content);

// Remove the showAuthModal function
$pattern2 = '/function showAuthModal\([\s\S]*?\n\}\s*<\/script>/';
$content = preg_replace($pattern2, '</script>', $content);

file_put_contents($file, $content);
echo "Complex JavaScript removed!";
?>
