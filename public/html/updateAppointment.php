<?php
require_once("appointLib.php");

$appointment = new Appointment();

// Handle form submission from the Patient Portal (users.php)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    
    // Bind form variables to class fields
    $appointment->setId($_POST['id']);
    $appointment->setName($_POST['name']);
    $appointment->setEmail($_POST['email']);
    $appointment->setDepartment($_POST['department']);
    $appointment->setTime($_POST['time']);

    // Execute database update query rule
    if ($appointment->updateAppointment()) {
        // Determine where the user came from to redirect them appropriately
        if (isset($_POST['update']) && $_POST['update'] == "1") {
            // Redirect back to the Patient Portal with success parameter
            header("Location: users.php?updated=1");
        } else {
            // Safe fallback rule for legacy doctor panel
            header("Location: doctor.php?updated=1");
        }
        exit();
    } else {
        echo "Error: Failed to synchronize and save your appointment schedule updates.";
    }
}

// Legacy GET request routing block (Used when loading the separate standalone edit page)
$currentPatient = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment->setId($_GET['id']);
    $currentPatient = $appointment->getAppointment();
    
    if (!$currentPatient) {
        header("Location: doctor.php");
        exit();
    }
} else {
    header("Location: doctor.php");
    exit();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-green-primary text-white p-3">
                        <h4 class="m-0">Edit Appointment Record</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="updateAppointment.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $currentPatient->id; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $currentPatient->name; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $currentPatient->email; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Department</label>
                                <select class="form-control" name="department" required>
                                    <option value="General Medicine" <?php echo ($currentPatient->department == 'General Medicine') ? 'selected' : ''; ?>>General Medicine</option>
                                    <option value="Cardiology" <?php echo ($currentPatient->department == 'Cardiology') ? 'selected' : ''; ?>>Cardiology</option>
                                    <option value="Pediatrics" <?php echo ($currentPatient->department == 'Pediatrics') ? 'selected' : ''; ?>>Pediatrics</option>
                                    <option value="Neurology" <?php echo ($currentPatient->department == 'Neurology') ? 'selected' : ''; ?>>Neurology</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Time Slot</label>
                                <select class="form-control" name="time" required>
                                    <option value="09:00 AM" <?php echo ($currentPatient->time == '09:00 AM') ? 'selected' : ''; ?>>09:00 AM</option>
                                    <option value="11:00 AM" <?php echo ($currentPatient->time == '11:00 AM') ? 'selected' : ''; ?>>11:00 AM</option>
                                    <option value="02:00 PM" <?php echo ($currentPatient->time == '02:00 PM') ? 'selected' : ''; ?>>02:00 PM</option>
                                    <option value="04:00 PM" <?php echo ($currentPatient->time == '04:00 PM') ? 'selected' : ''; ?>>04:00 PM</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn bg-green-primary w-100 fw-bold py-2 mb-2">Save Changes</button>
                            <a href="doctor.php" class="btn btn-outline-secondary w-100">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>