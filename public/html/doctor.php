<?php
// Include Karl's library file
require_once 'appointLib.php';
$appointmentLib = new Appointment();

$departments = ["General Medicine", "Cardiology", "Pediatrics", "Neurology"];
$current_dept = isset($_GET['dept']) ? $_GET['dept'] : "General Medicine";

if (!in_array($current_dept, $departments)) {
    $current_dept = "General Medicine";
}

// Invoke the department filter method we added into Karl's class
$patient_list = $appointmentLib->getAppointmentsByDepartment($current_dept);
?>