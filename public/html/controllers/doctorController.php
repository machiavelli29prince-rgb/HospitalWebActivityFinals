<?php
require_once __DIR__ . '/../core/bootstrap.php';

// Access Guard: Enforces strict session verification for medical staff access states
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ' . appUrl('html/utils/index.php#auth-section'));
    exit();
}

$appointmentLib = new Appointment();

// Request Routing Pre-Processor: Sets and validates the current active clinic department parameter
$departments = ["General Medicine", "Cardiology", "Pediatrics", "Neurology"];
$current_dept = isset($_GET['dept']) ? $_GET['dept'] : "General Medicine";

if (!in_array($current_dept, $departments, true)) {
    $current_dept = "General Medicine";
}

$patient_list = $appointmentLib->fetchByDepartment($current_dept);
