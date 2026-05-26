<?php
session_start();
require_once 'appointLib.php';

// Auth Guard: Enforces strict session verification for patients adding records
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: auth.php");
    exit();
}

// Creation Controller: Binds session-validated user parameters directly to new requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment = new Appointment();

    $appointment->setUserId($_SESSION['user_id']);
    $appointment->setName($_POST['name']);
    $appointment->setEmail($_POST['email']);
    $appointment->setDepartment($_POST['department']);
    $appointment->setTime($_POST['time']);

    if ($appointment->addAppointment()) {
        header("Location: users.php?added=1");
    } else {
        header("Location: users.php?error=1");
    }
    exit();
}
?>