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
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <title>Doctor Dashboard - Rodencia</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/animate.min.css">
    <link rel="stylesheet" href="../css/aos.css">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">

    <style>
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; }
        .bg-green-primary:hover { background-color: #1b5e20 !important; }
        
        .bg-green-light { background-color: #e8f5e9 !important; }
        .text-green-primary { color: #2e7d32 !important; }

        /* Custom Styles for Tab Layout */
        .dept-nav-link {
            display: block;
            padding: 15px 20px;
            color: #495057;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }
        .dept-nav-link:hover {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .dept-nav-link.active-tab {
            background-color: #e8f5e9 !important;
            color: #2e7d32 !important;
            border-left: 4px solid #2e7d32;
        }
    </style>
</head>

<body>

    <header class="bg-dark position-relative" data-aos="fade-down" style="padding-bottom: 20px;">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark py-4">
                <a class="navbar-brand" href="index.php">
                    <h1 class="h3 mt-0">Rodencia <span class="h6 text-muted">Portal</span></h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-target="#doctorNav"
                    data-bs-toggle="collapse" aria-controls="doctorNav" aria-expanded="false"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                
                <div class="collapse navbar-collapse ms-lg-5 mt-4 mt-lg-0" id="doctorNav">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item"><a class="nav-link" href="index.php"><span>Main Site</span></a></li>
                        <li class="nav-item"><a class="nav-link active" href="doctor.php"><span>Doctor Schedule</span></a></li>
                    </ul>
                    <ul class="navbar-nav mt-4 mt-lg-0 ms-auto align-items-center">
                        <li class="nav-item ms-lg-4 mt-4 mt-lg-0">
                            <a href="index.php" class="btn bg-green-primary text-white text-decoration-none">
                                <span class="btn-text">Book New Patient</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main class="py-7 py-lg-9 light-gray-1" style="min-height: 70vh;">
        <div class="container">
            
            <div class="row mb-4" data-aos="fade-up">
                <div class="col-12">
                    <a href="index.php" class="btn btn-outline-secondary py-2 px-3" style="border-radius: 4px; text-decoration: none; font-weight: 600;">
                        <i class="bi bi-arrow-left me-2" style="margin-right: 8px;"></i>Return to Main Site
                    </a>
                </div>
            </div>
            
            <div class="row mb-5" data-aos="fade-up">
                <div class="col-10">
                    <h2 class="h2 text-color">Medical Staff Schedule</h2>
                    <p class="paragraph second-text-color mt-2">
                        Select a clinical department sidebar tab below to review active patient queue files and booked operational blocks.
                    </p>
                </div>
            </div>

            <div class="row align-items-start mt-4">
                
                <div class="col-lg-4 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="card-item round bg-white p-4 border shadow-sm">
                        <h5 class="h5 text-color font-weight-bold mb-4" style="margin-bottom: 20px;">Clinical Fields</h5>
                        
                        <div class="navigation-tabs-wrapper">
                            <?php foreach ($departments as $dept): ?>
                                <a href="doctor.php?dept=<?php echo urlencode($dept); ?>" 
                                   class="dept-nav-link <?php echo ($current_dept === $dept) ? 'active-tab' : ''; ?>">
                                    <i class="bi bi-shield-plus me-2" style="margin-right: 10px;"></i>
                                    <?php echo $dept; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8" data-aos="fade-left">
                    <div class="card-item round bg-white p-4 p-md-5 border shadow-sm h-100">
                        
                        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4" style="margin-bottom: 30px; padding-bottom: 15px;">
                            <div>
                                <h3 class="h3 text-color font-weight-bold"><?php echo $current_dept; ?> Queue</h3>
                                <span class="badge bg-green-primary mt-1"><?php echo count($patient_list); ?> Confirmed Active</span>
                            </div>
                            <div class="icn-circle circle-md bg-green-light"><i class="bi bi-calendar-check text-green-primary"></i></div>
                        </div>

                        <?php if (empty($patient_list)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-clipboard-x text-muted display-4 d-block mb-3" style="font-size: 3rem; margin-bottom: 15px;"></i>
                                <h5 class="h5 second-text-color">No upcoming appointments found</h5>
                                <p class="paragraph text-muted small mt-1">There are no patient data profiles submitted for this tracking parameter row yet.</p>
                            </div>
                        <?php else: ?>
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-green-light">
                                        <tr>
                                            <th class="py-3 px-3 border-0 rounded-start">Patient Name</th>
                                            <th class="py-3 px-3 border-0">Email Communication File</th>
                                            <th class="py-3 px-3 border-0">Operational Block</th>
                                            <th class="py-3 px-3 border-0 rounded-end text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($patient_list as $patient): ?>
                                            <tr>
                                                <td class="py-3 px-3 font-weight-bold text-color">
                                                    <i class="bi bi-person me-2 text-green-primary" style="margin-right: 8px;"></i>
                                                    <?php echo htmlspecialchars($patient->name); ?>
                                                </td>
                                                <td class="py-3 px-3 second-text-color">
                                                    <?php echo htmlspecialchars($patient->email); ?>
                                                </td>
                                                <td class="py-3 px-3">
                                                    <span class="badge bg-green-light text-green-primary px-3 py-2">
                                                        <i class="bi bi-clock me-1" style="margin-right: 4px;"></i>
                                                        <?php echo htmlspecialchars($patient->time); ?>
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3 text-end">
                                                    <a href="updateAppointment.php?id=<?php echo $patient->id; ?>" 
                                                       class="btn btn-sm btn-outline-primary py-1 px-2 me-1" 
                                                       style="border-radius: 4px; text-decoration: none; font-size: 0.85rem; margin-right: 5px;">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                    <a href="deleteAppointment.php?id=<?php echo $patient->id; ?>" 
                                                       class="btn btn-sm btn-outline-danger py-1 px-2" 
                                                       onclick="return confirm('Are you sure you want to cancel this scheduled operational appointment?');"
                                                       style="border-radius: 4px; text-decoration: none; font-size: 0.85rem;">
                                                        <i class="bi bi-trash-fill"></i> Cancel
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

    <footer class="light-gray-1 border-top">
        <div class="container py-4">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <h6 class="h6 second-text-color text-md-center m-0">Made With Love By Figmaland All Right Reserved</h6>
                </div>
            </div>
        </div>
    </footer>

    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/aos.js"></script>
    <script src="../js/tools.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>