<?php
session_start();
require_once '../config/database.php';

class MLMAuth {
    private $conn;
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    
    public function login($email, $password) {
        $stmt = $this->conn->prepare("
            SELECT id, email, password, role, first_name, last_name, status 
            FROM users 
            WHERE email = ? AND status = 'active'
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            
            // Update last login
            $this->conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
            
            return [
                'success' => true,
                'role' => $user['role'],
                'redirect' => $this->getRedirectUrl($user['role'])
            ];
        }
        
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    private function getRedirectUrl($role) {
        switch ($role) {
            case 'super_admin':
                return '/foreveryoungtours/admin/';
            case 'mca':
                return '/foreveryoungtours/mca/';
            case 'advisor':
                return '/foreveryoungtours/advisor/';
            case 'client':
                return '/foreveryoungtours/pages/dashboard.php';
            default:
                return '/foreveryoungtours/';
        }
    }
    
    public function logout() {
        session_destroy();
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
    
    public function requireRole($role) {
        if (!$this->hasRole($role)) {
            header('Location: /foreveryoungtours/auth/login.php');
            exit;
        }
    }
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new MLMAuth();
    $result = $auth->login($_POST['email'], $_POST['password']);
    
    if ($result['success']) {
        header('Location: ' . $result['redirect']);
        exit;
    } else {
        $error = $result['message'];
    }
}
?>