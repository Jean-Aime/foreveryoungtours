<?php
session_start();
echo "<h2>Session Debug</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['user_role'])) {
    echo "<p><strong>Your role:</strong> " . $_SESSION['user_role'] . "</p>";
    
    switch ($_SESSION['user_role']) {
        case 'admin':
        case 'super_admin':
            echo "<p>You should go to: <a href='/admin/index.php'>/admin/index.php</a></p>";
            break;
        case 'mca':
            echo "<p>You should go to: <a href='/mca/index.php'>/mca/index.php</a></p>";
            break;
        case 'advisor':
            echo "<p>You should go to: <a href='/advisor/index.php'>/advisor/index.php</a></p>";
            break;
        case 'user':
            echo "<p>You should go to: <a href='/client/index.php'>/client/index.php</a></p>";
            break;
    }
} else {
    echo "<p>Not logged in. <a href='/auth/login.php'>Login here</a></p>";
}
?>
