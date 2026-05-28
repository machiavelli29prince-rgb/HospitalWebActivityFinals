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
    static $baseUrl = null;
    if ($baseUrl !== null) {
        return $baseUrl;
    }

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptFilename = realpath($_SERVER['SCRIPT_FILENAME'] ?? '') ?: '';
    $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';

    $base = '';
    $publicSegment = '/public';

    if (($position = strpos($scriptName, $publicSegment)) !== false) {
        $base = substr($scriptName, 0, $position + strlen($publicSegment));
    } elseif ($scriptFilename && $documentRoot && strpos($scriptFilename, $documentRoot) === 0) {
        $relativePath = str_replace('\\', '/', substr($scriptFilename, strlen($documentRoot)));
        if (($fsPosition = strpos($relativePath, $publicSegment)) !== false) {
            $base = substr($relativePath, 0, $fsPosition + strlen($publicSegment));
        }
    }

    if (empty($base)) {
        $base = dirname($scriptName);
    }

    $baseUrl = rtrim($base, '/');
    return $baseUrl;
}

// Resolve a web-relative path from the application root.
function appUrl(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $base = rtrim(getAppBaseUrl(), '/');
    }

    return $base . '/' . ltrim($path, '/');
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
