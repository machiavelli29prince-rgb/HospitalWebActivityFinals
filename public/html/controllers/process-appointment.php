<?php
require_once __DIR__ . '/../core/bootstrap.php';

// Verification check: Ensure only logged-in patients can trigger data insertion loops
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'patient') {
    header('Location: ../views/auth.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment = new Appointment();
    $appointment->user_id = (int) $_SESSION['user_id'];
    $appointment->name = trim($_POST['name']);
    $appointment->email = trim($_POST['email']);
    $appointment->department = trim($_POST['department']);
    $appointment->time = trim($_POST['time']);

    if ($appointment->create()) {
        $mailer = new MailerHelper();
        $mailer->sendAppointmentNotification(
            $appointment->name,
            $appointment->email,
            'Appointment Confirmed',
            "Your appointment in {$appointment->department} at {$appointment->time} has been confirmed."
        );

        header('Location: ../views/users.php?added=1');
    } else {
        header('Location: ../views/users.php?error=1');
    }
    exit();
}
?>