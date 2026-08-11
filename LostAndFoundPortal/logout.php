<?php
/**
 * Lost and Found Portal - Logout Script
 */
require_once __DIR__ . '/config/config.php';

// Unset all session variables
$_SESSION = array();

// Destroy session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Start a fresh session for flash message
session_start();
$_SESSION['success_msg'] = "You have been logged out successfully.";

redirect('login.php');
?>
