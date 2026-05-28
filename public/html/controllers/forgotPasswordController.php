<?php
require_once __DIR__ . '/../core/bootstrap.php';

$error = '';
$success = '';
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address to continue.';
    } else {
        $auth->requestPasswordReset($email);
        $success = 'If an account exists for that email, a password reset link has been sent. Please check your inbox.';
    }
}
