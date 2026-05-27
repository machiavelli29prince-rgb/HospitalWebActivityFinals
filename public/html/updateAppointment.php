<?php
require_once 'bootstrap.php';

// Auth Guard: Universal authentication checkpoint rule
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit();
}

$appointment = new Appointment();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $appointment->id = (int) $_POST['id'];
    $currentRecord = $appointment->fetchById($appointment->id);

    if (!$currentRecord) {
        header('Location: users.php');
        exit();
    }

    if ($_SESSION['user_role'] === 'patient' && $currentRecord->user_id != $_SESSION['user_id']) {
        header('Location: users.php?error=unauthorized');
        exit();
    }

    $appointment->name = trim($_POST['name']);
    $appointment->email = trim($_POST['email']);
    $appointment->department = trim($_POST['department']);
    $appointment->time = trim($_POST['time']);

    if ($appointment->update()) {
        $mailer = new MailerHelper();
        $mailer->sendAppointmentNotification(
            $appointment->name,
            $appointment->email,
            'Appointment Updated',
            "Your appointment has been updated to {$appointment->department} at {$appointment->time}."
        );

        if (isset($_POST['update']) && $_POST['update'] === '1') {
            header('Location: users.php?updated=1');
        } else {
            header('Location: doctor.php?updated=1');
        }
        exit();
    }

    echo 'Error: Mutation engine validation failure.';
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment->id = (int) $_GET['id'];
    $currentPatient = $appointment->fetchById($appointment->id);
    
    if (!$currentPatient) {
        header('Location: users.php');
        exit();
    }

    if ($_SESSION['user_role'] === 'patient' && $currentPatient->user_id != $_SESSION['user_id']) {
        header('Location: users.php?error=unauthorized');
        exit();
    }
}
?>