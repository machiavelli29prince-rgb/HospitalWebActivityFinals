<?php
// Central bootstrap for the application. Loads configuration, database, models, and shared helpers.

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers/Mailer.php';
require_once __DIR__ . '/appointLib.php';

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
