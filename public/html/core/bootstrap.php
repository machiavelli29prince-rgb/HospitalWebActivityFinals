<?php
// Bootstrap loader: defines paths, loads config, utilities, and models, then starts the session.

define('HTML_ROOT', realpath(__DIR__ . '/..'));
define('HTML_CONFIG_PATH', HTML_ROOT . '/core/config.php');
define('HTML_UTILS_PATH', HTML_ROOT . '/utils');
define('HTML_MODELS_PATH', HTML_ROOT . '/models');

require_once HTML_CONFIG_PATH;
require_once HTML_UTILS_PATH . '/db.php';
require_once HTML_UTILS_PATH . '/Mailer.php';
require_once HTML_MODELS_PATH . '/appointLib.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect the user back to the public login/auth page.
function redirectToAuth(string $message = ''): void
{
    $location = 'index.php#auth-section';
    if (!empty($message)) {
        $location .= '?message=' . urlencode($message);
    }
    header('Location: ' . $location);
    exit();
}

// Ensure the current request has an authenticated user session.
function ensureLoggedIn(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirectToAuth();
    }
}
