<?php
// Terminate the current user session and clear all related session metadata.
session_start();

// Destroy stored session values.
$_SESSION = array();

// Remove the session cookie from the browser if one exists.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// End the PHP session and redirect to the public homepage.
session_destroy();
header("Location: index.php");
exit();
?>
