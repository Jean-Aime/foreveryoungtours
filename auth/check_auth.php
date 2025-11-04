<?php
function checkAuth($required_role = null) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }
    
    // If specific role required, check it
    if ($required_role) {
        $user_role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? '';
        
        // Allow 'user' role to access 'client' panel
        if ($required_role === 'client' && $user_role === 'user') {
            return;
        }
        
        // Check exact role match
        if ($user_role !== $required_role) {
            header('Location: ../auth/login.php');
            exit;
        }
    }
}

function redirectToDashboard($role) {
    switch ($role) {
        case 'super_admin':
            return '../admin/index.php';
        case 'mca':
            return '../mca/index.php';
        case 'advisor':
            return '../advisor/index.php';
        case 'client':
            return '../client/index.php';
        default:
            return '../auth/login.php';
    }
}
?>