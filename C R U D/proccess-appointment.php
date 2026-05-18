<?php


require_once 'appointLib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Basic input sanitisation
    $name       = trim($_POST['name']       ?? '');
    $email      = trim($_POST['email']      ?? '');
    $department = trim($_POST['department'] ?? '');
    $time       = trim($_POST['time']       ?? '');

    // Reject yan pag walang nakalagay sa forms
    if ($name === '' || $email === '' || $department === '' || $time === '') {
        echo "<script>
                alert('All fields are required. Please fill in the form completely.');
                window.history.back();
              </script>";
        exit();
    }

    // Para sure yung email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Please enter a valid email address.');
                window.history.back();
              </script>";
        exit();
    }

    // Instantiate the Appointment class and populate its properties
    $appointment = new Appointment();
    $appointment->setName($name);
    $appointment->setEmail($email);
    $appointment->setDepartment($department);
    $appointment->setTime($time);

    // Execute INSERT and babalik with status flag na
    if ($appointment->addAppointment()) {
        header("Location: index.php?added=1");
    } else {
        echo "<script>
                alert('Failed to save appointment. Please check your database configuration.');
                window.history.back();
              </script>";
    }
    exit();

} else {
    // Direct access without POST — balik kasa main page
    header("Location: index.php");
    exit();
}
?>