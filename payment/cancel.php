<?php
session_start();
$_SESSION['error'] = 'Payment was cancelled. Please try again.';
header('Location: ../dashboard.php');
exit();
?>
