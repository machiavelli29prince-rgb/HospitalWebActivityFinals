<?php
//central bootstrap for the application - loads configuration, database, models, and shared helpers.

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

function redirectToAuth(string $message = ''): void
{
    $location = 'index.php#auth-section';
    if (!empty($message)) {
        $location .= '?message=' . urlencode($message);
    }
    header('Location: ' . $location);
    exit();
}

function ensureLoggedIn(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirectToAuth();
    }
}
