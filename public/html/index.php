<?php
// 1. Enable errors during development for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'bootstrap.php';

$error = '';
$success = '';
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'];

        if (!empty($name) && !empty($email) && !empty($password) && !empty($role)) {
            if (User::emailExists($email)) {
                $error = 'This email is already registered in our system.';
            } else {
                if ($auth->register($name, $email, $password, $role)) {
                    $success = 'Account created successfully! You can now log in.';
                } else {
                    $error = 'An error occurred while creating your account.';
                }
            }
        } else {
            $error = 'Please fill out all mandatory registration fields.';
        }
    }

    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (!empty($email) && !empty($password)) {
            $user = $auth->login($email, $password);

            if ($user) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_role'] = $user->role;

                if ($user->role === 'doctor') {
                    header('Location: doctor.php');
                } else {
                    header('Location: users.php');
                }
                exit();
            }

            $error = 'Invalid email or password combination.';
        } else {
            $error = 'Please fill out all credential spaces.';
        }
    }

    if (isset($_POST['subscribe'])) {
        $subscribeEmail = trim($_POST['subscribe_email']);

        if (!empty($subscribeEmail) && filter_var($subscribeEmail, FILTER_VALIDATE_EMAIL)) {
            $mailer = new MailerHelper();
            $subscriberSubject = 'Rodencia Health Advisory Subscription';
            $subscriberBody = "<p>Thank you for following Rodencia Hospital.</p>" .
                "<p>You will receive official clinical updates, health advisories, and research news from our medical board.</p>" .
                "<p>If you want to manage your subscription, reply to this email or contact our support team.</p>";

            // Attempt to send email - for local development, this may not deliver but won't throw errors
            $mailer->sendEmail($subscribeEmail, $subscriberSubject, $subscriberBody);
            
            // Always show success - emails will be logged even if local mail() doesn't deliver
            $success = 'Thank you for subscribing! Check your email for confirmation details.';
        } else {
            $error = 'Please provide a valid business email address to subscribe.';
        }
    }
}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <title>Rodencia Hospital</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/animate.min.css">
    <link rel="stylesheet" href="../css/aos.css">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Smooth scrolling behavior for navigation anchors */
        html {
            scroll-behavior: smooth;
        }

        /* Main green brand colors */
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; } /* Deep medical green */
        .bg-green-primary:hover { background-color: #1b5e20 !important; }
        
        .bg-green-accent { background-color: #4caf50 !important; color: white !important; } /* Mid-tone green */
        .bg-green-accent:hover { background-color: #388e3c !important; }

        .bg-green-light { background-color: #e8f5e9 !important; } /* Soft light green tint background */
        .text-green-primary { color: #2e7d32 !important; } /* Deep green for text highlights */
        
        /* Team Profile Image Handler */
        .team-img-container {
            width: 100%;
            height: 250px;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .team-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Hover effect indicating the card can be interacted with */
        .clickable-team-card {
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .clickable-team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        }

        /* Fixes the Bootstrap modal backdrop overlay bug */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }

        /* Form Switch Tabs */
        .auth-tab { cursor: pointer; padding: 12px; text-align: center; font-weight: bold; border-bottom: 2px solid transparent; transition: 0.2s ease; }
        .auth-tab.active { border-bottom: 2px solid #2e7d32; color: #2e7d32; background-color: #f8f9fa; }
    </style>
</head>

<body>

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>
    <header class="position-relative" data-aos="fade-down">
        <div class="background position-absolute z-index_-1 w-100 h-100">
            <img class="cover" src="../img/hero-cover.5.jpg" alt="Cover Image">
            <div class="filter basic"></div>
        </div>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark py-4">
                <a class="navbar-brand" href="#">
                    <h1 class="h3 mt-0">Rodencia</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-target="#navbarSupportedContent"
                    data-bs-toggle="collapse" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="#auth-section">Access Portal</a></li>
                    </ul>
                </div>
            </nav>
            
            <div class="row align-items-center justify-content-between header-body" style="padding: 120px 0 160px 0;">
                <div class="col-lg-6 text-white">
                    <h1 class="display-4 fw-bold animate__animated animate__fadeInUp">Meet the Best Hospital Management System</h1>
                    <p class="lead opacity-75 my-4 animate__animated animate__fadeInUp animate__delay-1s">
                        Our centralized medical records routing architecture lets patients reserve consultations and doctors evaluate department data metrics natively.
                    </p>
                    <a href="#auth-section" class="btn bg-green-accent px-4 py-2.5 text-white fw-bold shadow animate__animated animate__fadeInUp animate__delay-2s" style="border-radius: 4px; text-decoration: none;">
                        Get Started / Sign In <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>

                <div class="col-lg-5 mt-5 mt-lg-0" id="auth-section">
                    <div class="card bg-white text-dark shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
                        
                        <div class="row g-0 border-bottom">
                            <div class="col-6 auth-tab active" id="tab-login" onclick="switchAuthTab('login')">Sign In</div>
                            <div class="col-6 auth-tab" id="tab-signup" onclick="switchAuthTab('signup')">Create Account</div>
                        </div>

                        <div class="card-body p-4">
                            <?php if(!empty($error)): ?>
                                <div class="alert alert-danger p-2 small"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            <?php if(!empty($success)): ?>
                                <div class="alert alert-success p-2 small"><?php echo htmlspecialchars($success); ?></div>
                            <?php endif; ?>

                            <form id="form-login-block" action="index.php#auth-section" method="POST">
                                <h5 class="fw-bold text-dark mb-3">Welcome Back</h5>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control" required autocomplete="username">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold">Password</label>
                                    <input type="password" name="password" class="form-control" required autocomplete="current-password">
                                </div>
                                <button type="submit" name="login" class="btn bg-green-primary text-white w-100 py-2 fw-bold shadow-sm">Sign Into System</button>
                            </form>

                            <form id="form-signup-block" action="index.php#auth-section" method="POST" style="display: none;">
                                <h5 class="fw-bold text-green-primary mb-3">Registration Form</h5>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Full Name</label>
                                    <input type="text" name="name" class="form-control" required autocomplete="name">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control" required autocomplete="email">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Secure Password</label>
                                    <input type="password" name="password" class="form-control" required autocomplete="new-password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">System Role</label>
                                    <select name="role" class="form-select" required>
                                        <option value="patient">Patient System Client</option>
                                        <option value="doctor">Medical Specialist Staff (Doctor)</option>
                                    </select>
                                </div>
                                <button type="submit" name="signup" class="btn btn-dark text-white w-100 py-2 fw-bold shadow-sm">Register Credentials</button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <section id="team">
        <div class="container py-7 py-lg-9">
            <div class="row justify-content-center">
                <div class="text-center col-lg-6">
                    <h2 class="h2 text-color">Meet the Team</h2>
                    <p class="paragraph second-text-color" style="margin-top: 10px">
                        The brilliant minds behind Rodencia Hospital, working seamlessly to engineer dependable, top-tier healthcare solutions.
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center mt-5 mt-lg-7">
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card-item round border shadow-sm h-100 clickable-team-card" data-bs-toggle="modal" data-bs-target="#modalKarl">
                        <div class="team-img-container rounded-top">
                            <img src="../img/team/karl.jpg" alt="Karl Armand C. Dela Torre" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'bi bi-person-fill h1 second-text-color\'></i>';">
                        </div>
                        <div class="card-content text-center py-4 px-3">
                            <h5 class="h5 text-color font-weight-bold">Karl Armand C. Dela Torre</h5>
                            <h6 class="h6 text-green-primary mt-1">Team Leader</h6>
                            <p class="paragraph second-text-color mt-3 small">Directs overall project architectures and backend engine milestones.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card-item round border shadow-sm h-100 clickable-team-card" data-bs-toggle="modal" data-bs-target="#modalRoland">
                        <div class="team-img-container rounded-top">
                            <img src="../img/team/roa.jpg" alt="Roland Machiavelli L. Roa" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'bi bi-person-fill h1 second-text-color\'></i>';">
                        </div>
                        <div class="card-content text-center py-4 px-3">
                            <h5 class="h5 text-color font-weight-bold">Roland Machiavelli L. Roa</h5>
                            <h6 class="h6 second-text-color mt-1">Core Developer</h6>
                            <p class="paragraph second-text-color mt-3 small">Specializes in frontend component dynamics and schema integrations.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card-item round border shadow-sm h-100 clickable-team-card">
                        <div class="team-img-container rounded-top">
                            <img src="../img/team/jude.jpg" alt="Jude Emmanuel M. Toralba" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'bi bi-person-fill h1 second-text-color\'></i>';">
                        </div>
                        <div class="card-content text-center py-4 px-3">
                            <h5 class="h5 text-color font-weight-bold">Jude Emmanuel M. Toralba</h5>
                            <h6 class="h6 second-text-color mt-1">Core Developer</h6>
                            <p class="paragraph second-text-color mt-3 small">Manages structural optimization benchmarks and local systems connectivity.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card-item round border shadow-sm h-100 clickable-team-card" data-bs-toggle="modal" data-bs-target="#modalMatthew">
                        <div class="team-img-container rounded-top">
                            <img src="../img/team/matt.jpg" alt="Matthew Franz T. Figueroa" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'bi bi-person-fill h1 second-text-color\'></i>';">
                        </div>
                        <div class="card-content text-center py-4 px-3">
                            <h5 class="h5 text-color font-weight-bold">Matthew Franz T. Figueroa</h5>
                            <h6 class="h6 second-text-color mt-1">Core Developer</h6>
                            <p class="paragraph second-text-color mt-3 small">Designs user behavior assets alongside comprehensive wireframe styles.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card-item round border shadow-sm h-100 clickable-team-card">
                        <div class="team-img-container rounded-top">
                            <img src="../img/team/gian.jpg" alt="Gian Carlos Cayari" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'bi bi-person-fill h1 second-text-color\'></i>';">
                        </div>
                        <div class="card-content text-center py-4 px-3">
                            <h5 class="h5 text-color font-weight-bold">Gian Carlos Cayari</h5>
                            <h6 class="h6 second-text-color mt-1">Core Developer</h6>
                            <p class="paragraph second-text-color mt-3 small">Maintains documentation structures and continuous testing integration.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="modalKarl" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Karl Armand C. Dela Torre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <img src="../img/team/karl-popup.jpg" class="img-fluid w-100 rounded-bottom" alt="Karl Easter Egg" onerror="this.src='https://via.placeholder.com/500x500?text=Karl+Meme+Easter+Egg';">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRoland" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Roland Machiavelli L. Roa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <img src="../img/team/roa-popup.jpg" class="img-fluid w-100 rounded-bottom" alt="Roland Easter Egg" onerror="this.src='https://via.placeholder.com/500x500?text=Roland+Meme+Easter+Egg';">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMatthew" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Matthew Franz T. Figueroa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">    
                    <img src="../img/team/matt-popup.jpg" class="img-fluid w-100 rounded-bottom" alt="Matthew Easter Egg" onerror="this.src='https://via.placeholder.com/500x500?text=Matthew+Meme+Easter+Egg';">
                </div>
            </div>
        </div>
    </div>

    <section id="departments" class="light-gray-1">
        <div class="container py-7 py-md-9">
            <div class="row justify-content-center">
                <div class="text-center col-lg-6">
                    <h2 class="h2 text-color">Our Clinical Departments</h2>
                    <p class="paragraph second-text-color" style="margin-top: 10px">
                        Explore our medical fields and connect directly with the specialist physicians managing active emergency logs and consultations.
                    </p>
                </div>
            </div>
            
            <div class="row justify-content-center mt-5 mt-md-7">
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card-item rounded bg-green-light border h-100" style="padding: 30px 25px">
                        <div class="card-content text-center">
                            <div class="icn-circle circle-md bg-white mx-auto mb-3">
                                <img src="../img/genmed.png" alt="General Medicine Icon" class="dept-icon-img">
                            </div>
                            <h5 class="h5 text-color font-weight-bold">General Medicine</h5>
                            <h6 class="h6 text-muted mt-2 small">Assigned Specialist:</h6>
                            <p class="paragraph text-green-primary font-weight-bold mt-1">Dr. Karl Armand</p>
                            <hr class="my-3">
                            <p class="paragraph second-text-color small">Handles baseline diagnostics, physical cleanups, and patient charting parameters.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card-item rounded bg-green-light border h-100" style="padding: 30px 25px">
                        <div class="card-content text-center">
                            <div class="icn-circle circle-md bg-white mx-auto mb-3">
                                <img src="../img/cardio.jpg" alt="Cardiology Icon" class="dept-icon-img">
                            </div>
                            <h5 class="h5 text-color font-weight-bold">Cardiology</h5>
                            <h6 class="h6 text-muted mt-2 small">Assigned Specialists:</h6>
                            <p class="paragraph text-green-primary font-weight-bold mt-1">Dr. Roland & Dr. Jude</p>
                            <hr class="my-3">
                            <p class="paragraph second-text-color small">Manages complex pulse logic dynamics, structural constraints, and rhythm optimizations.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card-item rounded bg-green-light border h-100" style="padding: 30px 25px">
                        <div class="card-content text-center">
                            <div class="icn-circle circle-md bg-white mx-auto mb-3">
                                <img src="../img/pedia.png" alt="Pediatrics Icon" class="dept-icon-img">
                            </div>
                            <h5 class="h5 text-color font-weight-bold">Pediatrics</h5>
                            <h6 class="h6 text-muted mt-2 small">Assigned Specialist:</h6>
                            <p class="paragraph text-green-primary font-weight-bold mt-1">Dr. Matthew Franz</p>
                            <hr class="my-3">
                            <p class="paragraph second-text-color small">Specializes in small scale objects, user-behavior wiring, and early growth benchmarks.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card-item rounded bg-green-light border h-100" style="padding: 30px 25px">
                        <div class="card-content text-center">
                            <div class="icn-circle circle-md bg-white mx-auto mb-3">
                                <img src="../img/neuro.png" alt="Neurology Icon" class="dept-icon-img">
                            </div>
                            <h5 class="h5 text-color font-weight-bold">Neurology</h5>
                            <h6 class="h6 text-muted mt-2 small">Assigned Specialist:</h6>
                            <p class="paragraph text-green-primary font-weight-bold mt-1">Dr. Gian Carlos</p>
                            <hr class="my-3">
                            <p class="paragraph second-text-color small">Oversees deep architectural circuitry, state tables logic, and continuous link optimization.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="faq" class="light-gray-1 py-7" style="background-color: #ffffff !important;">
    <div class="container py-5 py-md-7">
        <div class="row justify-content-center">
            <div class="text-center col-lg-8 mb-5">
                <h2 class="h2 text-color fw-bold" style="font-size: 2.2rem;">FAQ</h2>
                <p class="paragraph second-text-color mt-3 px-md-5">
                    Explore our comprehensive guide to frequently asked queries regarding medical care delivery pipelines, patient hospitality scheduling protocols, and network coverage framework portfolios.
                </p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-4 mb-4">
                <div class="accordion custom-faq-accordion" id="faqGroup1">
                    <div class="accordion-item border-0 overflow-hidden">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                What medical services do you provide?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqGroup1">
                            <div class="accordion-body">
                                <ul class="ps-3 mb-0" style="list-style-type: disc;">
                                    <li>24/7 Critical emergency care and trauma response.</li>
                                    <li>Multidisciplinary outpatient specialty consultations.</li>
                                    <li>Advanced diagnostic imaging and comprehensive laboratory analyses.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="accordion custom-faq-accordion" id="faqGroup2">
                    <div class="accordion-item border-0 overflow-hidden">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How do I book a doctor appointment?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqGroup2">
                            <div class="accordion-body">
                                <ul class="ps-3 mb-0" style="list-style-type: disc;">
                                    <li>Secure real-time coordination via the online patient portal.</li>
                                    <li>Direct telephone booking through our central concierge hotline.</li>
                                    <li>On-site registration at the main outpatient desk.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="accordion custom-faq-accordion" id="faqGroup3">
                    <div class="accordion-item border-0 overflow-hidden">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Which insurance plans do you accept?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqGroup3">
                            <div class="accordion-body">
                                <ul class="ps-3 mb-0" style="list-style-type: disc;">
                                    <li>Full PhilHealth case-rate benefits allocation.</li>
                                    <li>Accredited private HMO corporate plan frameworks.</li>
                                    <li>International health insurance networks for global patients.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="accordion custom-faq-accordion" id="faqGroup4">
                    <div class="accordion-item border-0 overflow-hidden">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                What are your emergency room hours?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqGroup4">
                            <div class="accordion-body">
                                <ul class="ps-3 mb-0" style="list-style-type: disc;">
                                    <li>Continuous 24/7 operational capability for urgent cases.</li>
                                    <li>Full staffing during holidays and weekend shifts.</li>
                                    <li>On-call surgical teams prepared for immediate mobilization.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="accordion custom-faq-accordion" id="faqGroup5">
                    <div class="accordion-item border-0 overflow-hidden">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                How can I request medical records?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqGroup5">
                            <div class="accordion-body">
                                <ul class="ps-3 mb-0" style="list-style-type: disc;">
                                    <li>Formal request submission via the health information window.</li>
                                    <li>Verification through verified government-issued identification keys.</li>
                                    <li>Standard processing timeline of 3 to 5 business days.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="accordion custom-faq-accordion" id="faqGroup6">
                    <div class="accordion-item border-0 overflow-hidden">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Are hospital visitor hours restricted?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqGroup6">
                            <div class="accordion-body">
                                <ul class="ps-3 mb-0" style="list-style-type: disc;">
                                    <li>Standard visitor clearance from 8:00 AM to 8:00 PM daily.</li>
                                    <li>Maximum allowance of two visitors per patient room.</li>
                                    <li>Specialized units require attending nurse station approval.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="get-in-touch-section py-5 py-md-7" style="background-color: #f0f2f5 !important; color: #1e2229 !important; position: relative; z-index: 2;">
        <div class="container py-4 text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8 mb-4">
                    <h2 class="h2 fw-bold tracking-tight" style="color: #1e2229 !important; font-family: 'Montserrat', sans-serif; font-size: 2.2rem;">Get In Touch</h2>
                    <p class="paragraph mt-3 px-md-5" style="color: #5a6578 !important; font-family: 'Montserrat', sans-serif; font-size: 0.95rem; line-height: 1.6;">
                        Follow our medical portal to receive official clinical updates, health advisory protocols, and research breakthroughs from our medical board.
                    </p>
                </div>
                
                <div class="col-lg-6 col-md-8 d-flex justify-content-center">
                    <form action="" method="POST" class="form-group mb-0 w-100 d-flex justify-content-center">
                        <div class="modern-subscribe-group d-flex p-2 rounded-pill bg-white align-items-center">
                            <input name="subscribe_email" class="form-control ps-4 pe-3 border-0 bg-transparent" type="email" placeholder="Enter business email address" style="font-family: 'Montserrat', sans-serif; color: #1e2229 !important;" required>
                            <button name="subscribe" class="btn modern-subscribe-btn rounded-pill px-5 fw-bold" type="submit" style="font-family: 'Montserrat', sans-serif;">
                                <span>Follow</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="modern-footer position-relative pb-5 text-white" style="background-color: #1e2229 !important; position: relative; z-index: 2;">
        <div class="container">
            <div class="row justify-content-between pt-5">
                <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
                    <h3 class="h3 footer-heading fw-bold mb-4" style="color: #ffffff !important; font-family: 'Montserrat', sans-serif;">Rodencia Hospital</h3>
                    <p class="paragraph text-muted-light mb-4" style="font-family: 'Montserrat', sans-serif;">
                        Advancing patient-centered healthcare delivery infrastructures through integrated laboratory networks, clinical excellence, and 24/7 critical trauma response systems.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="social-icon-link d-flex align-items-center justify-content-center rounded-circle" href="#" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a class="social-icon-link d-flex align-items-center justify-content-center rounded-circle" href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a class="social-icon-link d-flex align-items-center justify-content-center rounded-circle" href="#" aria-label="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a class="social-icon-link d-flex align-items-center justify-content-center rounded-circle" href="#" aria-label="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a class="social-icon-link d-flex align-items-center justify-content-center rounded-circle" href="#" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4 mb-xl-0 ps-xl-4">
                    <h3 class="h3 footer-heading fw-bold mb-4" style="color: #ffffff !important; font-family: 'Montserrat', sans-serif;">Institutional Profile</h3>
                    <div class="footer-links-list d-flex flex-column">
                        <a class="footer-link-item mb-2" href="#">About Our Medical System</a>
                        <a class="footer-link-item mb-2" href="#">Clinical Operations Team</a>
                        <a class="footer-link-item mb-2" href="#">Academic Medical Research</a>
                        <a class="footer-link-item" href="#">Hospital Governance Board</a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4 mb-md-0">
                    <h3 class="h3 footer-heading fw-bold mb-4" style="color: #ffffff !important; font-family: 'Montserrat', sans-serif;">Core Care Portfolios</h3>
                    <div class="footer-links-list d-flex flex-column">
                        <a class="footer-link-item mb-2" href="#">Telehealth & Remote Triage</a>
                        <a class="footer-link-item mb-2" href="#">Inpatient Logistics Portal</a>
                        <a class="footer-link-item mb-2" href="#">Emergency Resource Dispatch</a>
                        <a class="footer-link-item" href="#">Diagnostic Pricing Framework</a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <h3 class="h3 footer-heading fw-bold mb-4" style="color: #ffffff !important; font-family: 'Montserrat', sans-serif;">Patient Resources</h3>
                    <div class="footer-links-list d-flex flex-column">
                        <a class="footer-link-item mb-2" href="#">Mobile Electronic Health Record App</a>
                        <a class="footer-link-item mb-2" href="#">Physician Consultation Scheduler</a>
                        <a class="footer-link-item mb-2" href="#">Privacy & Statutory Legal Compliance</a>
                        <a class="footer-link-item" href="#">Developer API & Integration Documentation</a>
                    </div>
                </div>
            </div>
            
            <hr class="footer-divider my-5" style="border-top: 1px solid rgba(255, 255, 255, 0.08) !important;">
            
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 text-center">
                    <p class="mb-0 text-muted-light font-size-sm" style="font-family: 'Montserrat', sans-serif;">
                        &copy; 2026 Rodencia Hospital Systems. All Rights Reserved. Engineered with enterprise data protection protocols.
                    </p>
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

        // JS function to switch form tabs cleanly
        function switchAuthTab(type) {
            const loginTab = document.getElementById('tab-login');
            const signupTab = document.getElementById('tab-signup');
            const loginForm = document.getElementById('form-login-block');
            const signupForm = document.getElementById('form-signup-block');

            if (type === 'login') {
                loginTab.classList.add('active');
                signupTab.classList.remove('active');
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
            } else {
                signupTab.classList.add('active');
                loginTab.classList.remove('active');
                signupForm.style.display = 'block';
                loginForm.style.display = 'none';
            }
        }

        // Auto-show signup tab if registration fails or succeeds during submission
        <?php if (isset($_POST['signup'])): ?>
            switchAuthTab('signup');
        <?php endif; ?>
    </script>
    </body>

</html>