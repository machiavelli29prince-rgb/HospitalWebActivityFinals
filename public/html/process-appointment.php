<?php
session_start();
require_once 'appointLib.php';

// Verification check: Ensure only logged-in patients can trigger data insertion loops
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: auth.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment = new Appointment();

    // Map system tracking keys directly from explicit active user session data parameters
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