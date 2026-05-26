<?php
session_start();
require_once("appointLib.php");

// Auth Guard: Redirects guest users away from private system space
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php#auth-section");
    exit();
}

// Access Guard: Restricts medical staff from accessing patient specific view states
if ($_SESSION['user_role'] === 'doctor') {
    header("Location: doctor.php");
    exit();
}

$post = new Appointment();
$posts = $post->getAppointmentsByPatient($_SESSION['user_id']);
$currentAppointment = null;

// Routing Pre-Processor: Checks entity parameters before populating modification interfaces
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $post->setId($_GET['id']);
    $currentAppointment = $post->getAppointment();
    
    if ($currentAppointment && $currentAppointment->user_id != $_SESSION['user_id']) {
        header("Location: users.php?error=unauthorized");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Patient Portal - Rodencia Hospital</title>
    <style>
        body { background-color: #f5f5f5; }
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; }
        .bg-green-accent { background-color: #4caf50 !important; color: white !important; }
        .bg-green-accent:hover { background-color: #388e3c !important; }
        .bg-green-light { background-color: #e8f5e9 !important; }
        .text-green-primary { color: #2e7d32 !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-green-primary shadow-sm py-3 mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Rodencia Patient Desk</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white small fw-bold"><i class="bi bi-person-circle"></i> Connected: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="logout.php" class="btn btn-sm btn-outline-light px-3">Sign Out</a>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="card border shadow-sm">
                <div class="card-header bg-green-primary py-3">
                    <h5 class="card-title m-0 fw-bold text-center">
                        <?php echo $currentAppointment ? 'Reschedule Booking' : 'Book Appointment'; ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo $currentAppointment ? 'updateAppointment.php' : 'process-appointment.php'; ?>" method="POST">
                        <?php if ($currentAppointment) { ?>
                            <input type="hidden" name="id" value="<?php echo $currentAppointment->id; ?>">
                            <input type="hidden" name="update" value="1">
                        <?php } ?>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $currentAppointment ? htmlspecialchars($currentAppointment->name) : htmlspecialchars($_SESSION['user_name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $currentAppointment ? htmlspecialchars($currentAppointment->email) : htmlspecialchars($_SESSION['user_email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Department</label>
                            <select name="department" class="form-select" required>
                                <option value="General Medicine" <?php echo ($currentAppointment && $currentAppointment->department == 'General Medicine') ? 'selected' : ''; ?>>General Medicine</option>
                                <option value="Cardiology" <?php echo ($currentAppointment && $currentAppointment->department == 'Cardiology') ? 'selected' : ''; ?>>Cardiology</option>
                                <option value="Pediatrics" <?php echo ($currentAppointment && $currentAppointment->department == 'Pediatrics') ? 'selected' : ''; ?>>Pediatrics</option>
                                <option value="Neurology" <?php echo ($currentAppointment && $currentAppointment->department == 'Neurology') ? 'selected' : ''; ?>>Neurology</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Time Slot</label>
                            <select name="time" class="form-select" required>
                                <option value="09:00 AM" <?php echo ($currentAppointment && $currentAppointment->time == '09:00 AM') ? 'selected' : ''; ?>>09:00 AM</option>
                                <option value="11:00 AM" <?php echo ($currentAppointment && $currentAppointment->time == '11:00 AM') ? 'selected' : ''; ?>>11:00 AM</option>
                                <option value="02:00 PM" <?php echo ($currentAppointment && $currentAppointment->time == '02:00 PM') ? 'selected' : ''; ?>>02:00 PM</option>
                                <option value="04:00 PM" <?php echo ($currentAppointment && $currentAppointment->time == '04:00 PM') ? 'selected' : ''; ?>>04:00 PM</option>
                            </select>
                        </div>

                        <button type="submit" name="<?php echo $currentAppointment ? 'update' : 'submit'; ?>" class="btn bg-green-accent w-100 fw-bold py-2 shadow-sm text-white">
                            <?php echo $currentAppointment ? 'Save Changes' : 'Confirm Request'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 fw-bold text-green-primary">Your Scheduled Appointments</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small fw-bold">
                                <tr>
                                    <th class="p-3">ID</th>
                                    <th class="p-3">Patient</th>
                                    <th class="p-3">Department</th>
                                    <th class="p-3">Timing</th>
                                    <th class="p-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php if (empty($posts)) { ?>
                                    <tr><td colspan="5" class="text-center p-4 text-muted">No appointments found under your account.</td></tr>
                                <?php } else { ?>
                                    <?php foreach ($posts as $p) { ?>
                                    <tr>
                                        <td class="p-3 text-muted">#<?php echo $p->id; ?></td>
                                        <td class="p-3 fw-bold"><?php echo htmlspecialchars($p->name); ?></td>
                                        <td class="p-3"><span class="badge bg-green-light text-green-primary px-2 py-1"><?php echo htmlspecialchars($p->department); ?></span></td>
                                        <td class="p-3 text-secondary"><?php echo htmlspecialchars($p->time); ?></td>
                                        <td class="text-center p-3">
                                            <div class="btn-group">
                                                <a href="users.php?id=<?php echo $p->id; ?>" class="btn btn-outline-primary btn-sm">Reschedule</a>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="cancelAppointment(<?php echo $p->id; ?>)">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// UI Component Interaction: Triggers user prompt confirmations before routing deletion requests
function cancelAppointment(id) {
    Swal.fire({
        title: 'Cancel Appointment?',
        text: "Are you sure you want to delete this appointment log entry?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete Row'
    }).then((result) => {
        if (result.isConfirmed) { window.location.href = "deleteAppointment.php?id=" + id; }
    });
}
</script>
</body>
</html>