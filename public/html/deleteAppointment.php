<?php 
require_once("appointLib.php");

// Check if a specific patient row ID was securely passed via the URL parameter string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment = new Appointment();
    
    // Pass the row identifier into the class instance property using Karl's setter
    $appointment->setId($_GET['id']);
    
    // Execute the database delete query built inside Karl's class library
    if ($appointment->deleteAppointment()) {
        // Automatically bounce back to the doctor dashboard view with a deletion query flag
        header("Location: doctor.php?deleted=1");
        exit();
    } else {
        echo "Error: Unable to process data removal at this time.";
    }
} else {
    header("Location: doctor.php");
    exit();
}
?>