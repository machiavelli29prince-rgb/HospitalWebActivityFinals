<?php
session_start();
require_once("appointLib.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$appointment = new Appointment();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $appointment->setId($_POST['id']);
    $currentRecord = $appointment->getAppointment();

    if (!$currentRecord) {
        header("Location: users.php");
        exit();
    }

    // Ownership check: Prevent patients from updating appointments belonging to others
    if ($_SESSION['user_role'] === 'patient' && $currentRecord->user_id != $_SESSION['user_id']) {
        header("Location: users.php?error=unauthorized");
        exit();
    }

    $appointment->setName($_POST['name']);
    $appointment->setEmail($_POST['email']);
    $appointment->setDepartment($_POST['department']);
    $appointment->setTime($_POST['time']);

    if ($appointment->updateAppointment()) {
        if (isset($_POST['update']) && $_POST['update'] == "1") {
            header("Location: users.php?updated=1");
        } else {
            header("Location: doctor.php?updated=1");
        }
        exit();
    } else {
        echo "Error: Mutation engine validation failure.";
    }
}

// Standalone GET loader verification check
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment->setId($_GET['id']);
    $currentPatient = $appointment->getAppointment();
    
    if (!$currentPatient) {
        header("Location: users.php");
        exit();
    }

    if ($_SESSION['user_role'] === 'patient' && $currentPatient->user_id != $_SESSION['user_id']) {
        header("Location: users.php?error=unauthorized");
        exit();
    }
}
?>