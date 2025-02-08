<?php
// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session data
session_destroy();

// Optional: Destroy the session cookie (if session is cookie-based)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Redirect to login page or home page after logout
header("Location: login.php");
exit();
?>
