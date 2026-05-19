<?php
require_once("appointLib.php");

$post = new Appointment();
$posts = $post->getAppointments();

$currentAppointment = null;

/* SAFELY LOAD TARGET APPOINTMENT RECORD FOR RESCHEDULING */
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Uses the class's public methods instead of attempting private global property manipulation
    $post->setId($_GET['id']);
    $currentAppointment = $post->getAppointment();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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

<header class="bg-green-primary py-5">
    <div class="container text-center text-white">
        <h1>Rodencia Hospital</h1>
        <p>Patient Portal: View, Schedule, and Manage Your Appointments</p>
        <a href="index.php" class="btn btn-sm btn-outline-light mt-2">← Back to Main Homepage</a>
    </div>
</header>

<div class="container mt-5 mb-5">
    <div class="row g-4">
        
        <div class="col-lg-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body bg-green-light p-4">
                    <h3 class="text-center text-green-primary mb-4">
                        <?php echo isset($currentAppointment) ? "Reschedule Appointment" : "Book New Appointment"; ?>
                    </h3>

                    <form action="<?php echo isset($currentAppointment) ? 'updateAppointment.php' : 'process-appointment.php'; ?>" method="POST">

                        <?php if(isset($currentAppointment)){ ?>
                            <input type="hidden" name="id" value="<?php echo $currentAppointment->id; ?>">
                            <input type="hidden" name="update" value="1">
                        <?php } ?>

                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required value="<?php echo isset($currentAppointment->name) ? $currentAppointment->name : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required value="<?php echo isset($currentAppointment->email) ? $currentAppointment->email : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Medical Department</label>
                            <select name="department" class="form-control" required>
                                <option value="">Select Department</option>
                                <option value="General Medicine" <?php if(isset($currentAppointment) && $currentAppointment->department=="General Medicine") echo "selected"; ?>>General Medicine</option>
                                <option value="Cardiology" <?php if(isset($currentAppointment) && $currentAppointment->department=="Cardiology") echo "selected"; ?>>Cardiology</option>
                                <option value="Pediatrics" <?php if(isset($currentAppointment) && $currentAppointment->department=="Pediatrics") echo "selected"; ?>>Pediatrics</option>
                                <option value="Neurology" <?php if(isset($currentAppointment) && $currentAppointment->department=="Neurology") echo "selected"; ?>>Neurology</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Preferred Time Slot</label>
                            <select name="time" class="form-control" required>
                                <option value="">Select Time</option>
                                <option value="09:00 AM" <?php if(isset($currentAppointment) && $currentAppointment->time=="09:00 AM") echo "selected"; ?>>09:00 AM</option>
                                <option value="11:00 AM" <?php if(isset($currentAppointment) && $currentAppointment->time=="11:00 AM") echo "selected"; ?>>11:00 AM</option>
                                <option value="02:00 PM" <?php if(isset($currentAppointment) && $currentAppointment->time=="02:00 PM") echo "selected"; ?>>02:00 PM</option>
                                <option value="04:00 PM" <?php if(isset($currentAppointment) && $currentAppointment->time=="04:00 PM") echo "selected"; ?>>04:00 PM</option>
                            </select>
                        </div>

                        <button type="submit" name="<?php echo isset($currentAppointment) ? 'update' : 'submit'; ?>" class="btn bg-green-accent w-100 fw-bold">
                            <?php echo isset($currentAppointment) ? "Confirm Reschedule" : "Book Appointment"; ?>
                        </button>

                        <?php if(isset($currentAppointment)){ ?>
                            <a href="users.php" class="btn btn-outline-secondary w-100 mt-2">Discard Changes</a>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow border-0 h-100">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-green-primary">My Scheduled Appointments</h3>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Appointment ID</th>
                                    <th>Patient Details</th>
                                    <th>Department</th>
                                    <th>Time Slot</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($posts)){ ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">You have no scheduled appointments.</td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach($posts as $p){ ?>
                                    <tr>
                                        <td class="fw-bold text-secondary">#<?php echo $p->id; ?></td>
                                        <td>
                                            <div><?php echo $p->name; ?></div>
                                            <small class="text-muted"><?php echo $p->email; ?></small>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?php echo $p->department; ?></span></td>
                                        <td><strong><?php echo $p->time; ?></strong></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
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
function cancelAppointment(id) {
    Swal.fire({
        title: 'Cancel Appointment?',
        text: "Are you sure you want to cancel this appointment? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'Keep Appointment'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "deleteAppointment.php?id=" + id;
        }
    });
}
</script>
</body>
</html>