<?php 
session_start();
require_once("appointLib.php");

// Auth Guard: Kicks non-authenticated traffic out to login index
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment = new Appointment();
    $appointment->setId($_GET['id']);
    $currentRecord = $appointment->getAppointment();
    
    if (!$currentRecord) {
        header("Location: users.php");
        exit();
    }

    // Security Ownership Rule: Blocks patients from deleting entries that belong to other users
    if ($_SESSION['user_role'] === 'patient' && $currentRecord->user_id != $_SESSION['user_id']) {
        header("Location: users.php?error=unauthorized");
        exit();
    }

    // Routing Logic: Routes workflows to the proper dashboard origin upon successful deletion
    if ($appointment->deleteAppointment()) {
        if ($_SESSION['user_role'] === 'doctor') {
            header("Location: doctor.php?deleted=1");
        } else {
            header("Location: users.php?deleted=1");
        }
        exit();
    } else {
        echo "Error: High level structural update rejection.";
    }
} else {
    header("Location: auth.php");
    exit();
}
?>