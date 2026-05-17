<?php
// Include our new OOP Class file
require_once 'AppointmentManager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Instantiating the object (triggers constructor / DB connection)
    $manager = new AppointmentManager();

    // Collect data from the index.php form inputs
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $time = $_POST['appointment_time'];

    // Call the class method to save the data
    if ($manager->createAppointment($name, $email, $department, $time)) {
        echo "<script>alert('Appointment booked successfully via OOP!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Failed to save appointment. Please check your system configuration.'); window.location.href='index.php';</script>";
    }
}
?>