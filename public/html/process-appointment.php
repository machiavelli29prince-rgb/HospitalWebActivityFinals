<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize the form inputs
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $department = $conn->real_escape_string($_POST['department']);
    $appointment_time = $conn->real_escape_string($_POST['appointment_time']);

    // SQL statement to insert the data
    $sql = "INSERT INTO appointments (name, email, department, appointment_time) 
            VALUES ('$full_name', '$email', '$department', '$appointment_time')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back or display a success message
        echo "<script>alert('Appointment booked successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error saving appointment: " . $conn->error;
    }
}

$conn->close();
?>