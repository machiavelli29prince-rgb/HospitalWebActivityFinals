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

// Build the root URL for the current application path. Uses the first /public segment as the web root.
function getAppBaseUrl(): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $position = strpos($scriptName, '/public');

    if ($position !== false) {
        $base = substr($scriptName, 0, $position + strlen('/public'));
    } else {
        $base = dirname($scriptName);
    }

    return rtrim($base, '/');
}

// Resolve a web-relative path from the application root.
function appUrl(string $path = ''): string
{
    return rtrim(getAppBaseUrl(), '/') . '/' . ltrim($path, '/');
}

// Redirect the user back to the public login/auth page.
function redirectToAuth(string $message = ''): void
{
    $location = appUrl('html/utils/index.php');
    if (!empty($message)) {
        $location .= '?message=' . urlencode($message);
    }
    $location .= '#auth-section';
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
