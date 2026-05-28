<?php
require_once __DIR__ . '/../core/bootstrap.php';

// Only logged-in users may delete appointments.
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . appUrl('html/views/auth.php'));
    exit();
}

// Load the requested appointment by ID before deletion.
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment = new Appointment();
    $appointment->id = (int) $_GET['id'];

    $currentRecord = $appointment->fetchById($appointment->id);
    if (!$currentRecord) {
        header('Location: ' . appUrl('html/views/users.php'));
        exit();
    }

    // Prevent patients from deleting other users' appointments.
    if ($_SESSION['user_role'] === 'patient' && $currentRecord->user_id != $_SESSION['user_id']) {
        header('Location: ' . appUrl('html/views/users.php?error=unauthorized'));
        exit();
    }

    if ($appointment->delete()) {
        if ($_SESSION['user_role'] === 'doctor') {
            header('Location: ' . appUrl('html/views/doctor.php?deleted=1'));
        } else {
            header('Location: ' . appUrl('html/views/users.php?deleted=1'));
        }
        exit();
    }

    echo 'Error: High level structural update rejection.';
} else {
    header('Location: ' . appUrl('html/views/auth.php'));
    exit();
}
?>
