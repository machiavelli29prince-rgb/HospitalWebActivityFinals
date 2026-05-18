<?php


require_once 'appointLib.php';


if (isset($_GET['id']) && $_GET['id'] !== '') {

    $id = (int) $_GET['id'];    // cast to int to prevent SQL injection

    $appointment = new Appointment();
    $appointment->setId($id);

    if ($appointment->deleteAppointment()) {
        // Success — bounce back to the doctor dashboard with a status flag
        header("Location: doctor.php?deleted=1");
    } else {
        // Deletion failed — go back with an error flag
        header("Location: doctor.php?error=delete_failed");
    }

} else {
    // No valid ID supplied — redirect silently
    header("Location: doctor.php");
}

exit();
?>