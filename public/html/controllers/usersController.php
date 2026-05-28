<?php
require_once __DIR__ . '/../core/bootstrap.php';

// Auth Guard: Redirects guest users away from private system space
if (!isset($_SESSION['user_id'])) {
    header('Location: ../utils/index.php#auth-section');
    exit();
}

// Access Guard: Restricts medical staff from accessing patient specific view states
if ($_SESSION['user_role'] === 'doctor') {
    header('Location: doctor.php');
    exit();
}

$post = new Appointment();
$posts = $post->fetchByUser((int) $_SESSION['user_id']);
$currentAppointment = null;

// Routing Pre-Processor: Checks entity parameters before populating modification interfaces
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $post->id = (int) $_GET['id'];
    $currentAppointment = $post->fetchById($post->id);

    if ($currentAppointment && $currentAppointment->user_id != $_SESSION['user_id']) {
        header('Location: users.php?error=unauthorized');
        exit();
    }
}
