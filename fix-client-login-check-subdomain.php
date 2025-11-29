<?php
$file = 'c:\\xampp1\\htdocs\\foreveryoungtours\\countries\\visit-rw\\pages\\tour-detail.php';
$content = file_get_contents($file);

$old_check = "const isLoggedIn = <?php echo isset(\$_SESSION['user_id']) ? 'true' : 'false'; ?>;";
$new_check = "const isLoggedIn = <?php echo (isset(\$_SESSION['user_id']) && \$_SESSION['user_role'] === 'client') ? 'true' : 'false'; ?>;";

$content = str_replace($old_check, $new_check, $content);
file_put_contents($file, $content);
echo "Subdomain client login check updated!";
?>
