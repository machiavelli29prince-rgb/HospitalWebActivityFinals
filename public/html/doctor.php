<?php
session_start();

// Access Guard: Enforces strict session verification for medical staff access states
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
    header("Location: index.php#auth-section");
    exit();
}

require_once 'appointLib.php';
$appointmentLib = new Appointment();

// Request Routing Pre-Processor: Sets and validates the current active clinic department parameter
$departments = ["General Medicine", "Cardiology", "Pediatrics", "Neurology"];
$current_dept = isset($_GET['dept']) ? $_GET['dept'] : "General Medicine";

if (!in_array($current_dept, $departments)) {
    $current_dept = "General Medicine";
}

// Data Request: Pulls the collection of appointments linked to the active department scope
$patient_list = $appointmentLib->getAppointmentsByDepartment($current_dept);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Dashboard - Rodencia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">
    <style>
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; }
        .bg-green-light { background-color: #e8f5e9 !important; }
        .text-green-primary { color: #2e7d32 !important; }
        .sidebar-link.active { background-color: #2e7d32 !important; color: white !important; font-weight: bold; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-green-primary shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Rodencia Staff Panel</a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-white small fw-bold"><i class="bi bi-shield-lock-fill"></i> Specialist: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-light px-3">Sign Out</a>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div class="row">
            
            <div class="col-lg-3 mb-4">
                <div class="card border shadow-sm p-3 bg-white">
                    <h6 class="fw-bold text-muted mb-3 small text-uppercase">Department Nodes</h6>
                    <div class="nav flex-column nav-pills">
                        <?php foreach ($departments as $dept): ?>
                            <a href="doctor.php?dept=<?php echo urlencode($dept); ?>" 
                               class="nav-link p-3 mb-2 text-dark <?php echo ($current_dept === $dept) ? 'active' : 'bg-light'; ?>">
                                <i class="bi bi-heart-pulse-fill me-2"></i> <?php echo $dept; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card border shadow-sm bg-white">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h5 class="m-0 fw-bold text-green-primary"><?php echo htmlspecialchars($current_dept); ?> Queue</h5>
                        <span class="badge bg-green-primary px-3 py-2"><?php echo count($patient_list); ?> Pinned</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($patient_list)): ?>
                            <div class="p-5 text-center text-muted">No active appointment records found.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover">
                                    <thead class="bg-light text-secondary small fw-bold">
                                        <tr>
                                            <th class="p-3">Patient Name</th>
                                            <th class="p-3">Email Address</th>
                                            <th class="p-3">Reserved Time Block</th>
                                            <th class="p-3 text-center">Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        <?php foreach ($patient_list as $patient): ?>
                                            <tr>
                                                <td class="p-3 fw-bold"><?php echo htmlspecialchars($patient->name); ?></td>
                                                <td class="p-3 text-muted"><?php echo htmlspecialchars($patient->email); ?></td>
                                                <td class="p-3">
                                                    <span class="badge bg-green-light text-green-primary px-3 py-2">
                                                        <?php echo htmlspecialchars($patient->time); ?>
                                                    </span>
                                                </td>
                                                <td class="p-3 text-center">
                                                    <a href="deleteAppointment.php?id=<?php echo $patient->id; ?>" 
                                                       class="btn btn-sm btn-outline-danger px-3"
                                                       onclick="return confirm('Remove this appointment permanently?');">
                                                        Remove
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>