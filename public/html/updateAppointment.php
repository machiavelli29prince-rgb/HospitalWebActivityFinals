<?php
require_once("appointLib.php");

$appointment = new Appointment();
$currentPatient = null;

// Catch the incoming entry row identifier to load target workspace files
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment->setId($_GET['id']);
    $currentPatient = $appointment->getAppointment();
    
    // If no row record returned match tracking IDs, redirect out safely
    if (!$currentPatient) {
        header("Location: doctor.php");
        exit();
    }
} else {
    header("Location: doctor.php");
    exit();
}

// Intercept form modification submission triggers
if (isset($_POST['update'])) {
    $appointment->setName($_POST['name']);
    $appointment->setEmail($_POST['email']);
    $appointment->setDepartment($_POST['department']);
    $appointment->setTime($_POST['time']); // Aligned to the 'time' schema column parameter

    if ($appointment->updateAppointment()) {
        header("Location: doctor.php?updated=1");
        exit();
    } else {
        $errorMessage = "Failed to synchronize changes with system logs.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Appointment - Rodencia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">
    <style>
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; }
        .bg-green-light { background-color: #e8f5e9 !important; }
    </style>
</head>
<body class="light-gray-1">

    <div class="container py-7">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card-item round bg-white border p-4 p-md-5 shadow-sm">
                    <h3 class="h3 text-color font-weight-bold text-center">Modify Schedule File</h3>
                    <p class="paragraph second-text-color text-center small mt-1">Adjust active record properties below to update tracking queues.</p>
                    <hr class="my-4">

                    <form action="updateAppointment.php?id=<?php echo $appointment->getId(); ?>" method="POST">
                        
                        <div class="form-group mb-3">
                            <label class="h6">Patient Name *</label>
                            <input class="form-control mt-1" type="text" name="name" value="<?php echo htmlspecialchars($currentPatient->name); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3" style="margin-top: 15px;">
                            <label class="h6">Email Communication File *</label>
                            <input class="form-control mt-1" type="email" name="email" value="<?php echo htmlspecialchars($currentPatient->email); ?>" required>
                        </div>
                        
                        <div class="form-group mb-3" style="margin-top: 15px;">
                            <label class="h6">Department *</label>
                            <select class="custom-select form-control mt-1" name="department" required>
                                <option value="General Medicine" <?php echo ($currentPatient->department == 'General Medicine') ? 'selected' : ''; ?>>General Medicine</option>
                                <option value="Cardiology" <?php echo ($currentPatient->department == 'Cardiology') ? 'selected' : ''; ?>>Cardiology</option>
                                <option value="Pediatrics" <?php echo ($currentPatient->department == 'Pediatrics') ? 'selected' : ''; ?>>Pediatrics</option>
                                <option value="Neurology" <?php echo ($currentPatient->department == 'Neurology') ? 'selected' : ''; ?>>Neurology</option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-4" style="margin-top: 15px; margin-bottom: 30px;">
                            <label class="h6">Time *</label>
                            <select class="custom-select form-control mt-1" name="time" required>
                                <option value="09:00 AM" <?php echo ($currentPatient->time == '09:00 AM') ? 'selected' : ''; ?>>09:00 AM Available</option>
                                <option value="11:00 AM" <?php echo ($currentPatient->time == '11:00 AM') ? 'selected' : ''; ?>>11:00 AM Available</option>
                                <option value="02:00 PM" <?php echo ($currentPatient->time == '02:00 PM') ? 'selected' : ''; ?>>02:00 PM Available</option>
                                <option value="04:00 PM" <?php echo ($currentPatient->time == '04:00 PM') ? 'selected' : ''; ?>>04:00 PM Available</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="update" class="btn bg-green-primary w-100 py-2">
                            <span>Save Changes</span>
                        </button>
                        <a href="doctor.php" class="btn btn-outline-secondary w-100 mt-2 py-2" style="margin-top: 10px; display: block; text-align: center; text-decoration: none;">
                            <span>Cancel</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>