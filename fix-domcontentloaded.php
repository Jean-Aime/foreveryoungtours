<?php
$file = 'c:\\xampp1\\htdocs\\foreveryoungtours\\pages\\tour-detail.php';
$content = file_get_contents($file);

$old = "document.addEventListener('DOMContentLoaded', function() {\n    document.querySelectorAll('.book-tour-btn').forEach(btn => {";
$new = "document.querySelectorAll('.book-tour-btn').forEach(btn => {";

$content = str_replace($old, $new, $content);

$old2 = "    });\n});";
$new2 = "});";

$content = str_replace($old2, $new2, $content);

file_put_contents($file, $content);
echo "DOMContentLoaded removed!";
?>
