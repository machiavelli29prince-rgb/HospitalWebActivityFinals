<?php
// Include Karl's library file
require_once 'appointLib.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Instantiate Karl's class
    $appointment = new Appointment();

    // Pass form input values into the class properties using setters
    $appointment->setName($_POST['name']);
    $appointment->setEmail($_POST['email']);
    $appointment->setDepartment($_POST['department']);
    $appointment->setTime($_POST['appointment_time']);

    // Execute the insertion block
    if ($appointment->addAppointment()) {
        echo "<script>alert('Appointment booked successfully using groupmate library!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Failed to save appointment. Check db.php configurations.'); window.location.href='index.php';</script>";
    }
}
?>