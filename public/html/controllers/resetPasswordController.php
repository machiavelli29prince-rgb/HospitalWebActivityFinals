<?php
require_once __DIR__ . '/../core/bootstrap.php';

$error = '';
$success = '';
$auth = new AuthController();
$token = trim($_GET['token'] ?? $_POST['token'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($token)) {
        $error = 'Password reset token is missing. Please use the link from your email.';
    } elseif (empty($password) || empty($confirmPassword)) {
        $error = 'Please enter and confirm your new password.';
    } elseif ($password !== $confirmPassword) {
        $error = 'The passwords do not match. Please try again.';
    } elseif ($auth->resetPassword($token, $password)) {
        $success = 'Your password has been reset successfully. You may now sign in with your new password.';
    } else {
        $error = $auth->getLastError() ?: 'Unable to reset your password. Please try again or request a new link.';
    }
}
