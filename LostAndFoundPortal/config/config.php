<?php
/**
 * Lost and Found Portal
 * Configuration & Database Connection File
 * Technology: PHP 8 (Core PHP) & PDO MySQL
 */

// Start Session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Credentials (Default XAMPP settings)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lost_and_found_db');

// Application URL Setup
define('BASE_URL', 'http://localhost/LostAndFoundPortal/');
define('SITE_NAME', 'Lost & Found Portal');

// Database Connection with PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO_ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO_FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // Graceful error display if MySQL service in XAMPP is not running
    die("<div style='font-family: Arial, sans-serif; padding: 20px; background: #fee2e2; border: 1px solid #f87171; color: #991b1b; border-radius: 8px; margin: 50px auto; max-width: 600px;'>
        <h3 style='margin-top:0;'>⚠️ Database Connection Failed</h3>
        <p>Could not connect to MySQL server. Please ensure:</p>
        <ul>
            <li>XAMPP Control Panel is running.</li>
            <li>MySQL service is STARTED in XAMPP.</li>
            <li>Database <b>lost_and_found_db</b> is created and <b>schema.sql</b> is imported in phpMyAdmin.</li>
        </ul>
        <small>Error Details: " . htmlspecialchars($e->getMessage()) . "</small>
    </div>");
}

/**
 * HELPER & SECURITY FUNCTIONS
 */

// Sanitize user input to prevent XSS attacks
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Redirect user to another page
function redirect($path) {
    header("Location: " . BASE_URL . ltrim($path, '/'));
    exit;
}

// Check if User is Logged In
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if Admin is Logged In
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

// Require User Auth Guard
function requireUserLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error_msg'] = "Please log in to access this page.";
        redirect('login.php');
    }
}

// Require Admin Auth Guard
function requireAdminLogin() {
    if (!isAdmin()) {
        $_SESSION['error_msg'] = "Admin access required.";
        redirect('admin/index.php');
    }
}

// Get Logged In User Info
function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;
    $stmt = $pdo->prepare("SELECT id, name, email, phone, address, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Upload Image Helper Function with security checks
function uploadImage($file, $targetDir = "assets/uploads/") {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No image uploaded or upload error occurred.'];
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);
    
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file format. Only JPG, PNG, WEBP allowed.'];
    }

    if ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
        return ['success' => false, 'message' => 'File size exceeds max 5MB limit.'];
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = 'item_' . time() . '_' . uniqid() . '.' . strtolower($ext);
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFilePath = $targetDir . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return ['success' => true, 'fileName' => $newFileName];
    } else {
        return ['success' => false, 'message' => 'Failed to save uploaded file.'];
    }
}
?>
