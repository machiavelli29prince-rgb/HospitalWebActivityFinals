<?php
require_once 'bootstrap.php';

// Auth Guard: Kicks non-authenticated traffic out to login index
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment = new Appointment();
    $appointment->id = (int) $_GET['id'];

    $currentRecord = $appointment->fetchById($appointment->id);
    
    if (!$currentRecord) {
        header('Location: users.php');
        exit();
    }

    if ($_SESSION['user_role'] === 'patient' && $currentRecord->user_id != $_SESSION['user_id']) {
        header('Location: users.php?error=unauthorized');
        exit();
    }

    if ($appointment->delete()) {
        if ($_SESSION['user_role'] === 'doctor') {
            header('Location: doctor.php?deleted=1');
        } else {
            header('Location: users.php?deleted=1');
        }
        exit();
    }

    echo 'Error: High level structural update rejection.';
} else {
    header('Location: auth.php');
    exit();
}
?>