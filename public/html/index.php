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
    </style>
</head>

<body>

    <header id="home" class="position-relative" data-aos="fade-down">
        <div class="background position-absolute z-index_-1 w-100 h-100"><img class="cover"
                src="../img/hero-cover.5.jpg"></img>
            <div class="filter basic"></div>
        </div>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark py-4"><a class="navbar-brand" href="#home">
                    <h1 class="h3 mt-0">Rodencia</h1>
                </a><button class="navbar-toggler" type="button" data-bs-target="#navbarSupportedContent"
                    data-bs-toggle="collapse" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                
                <div class="collapse navbar-collapse ms-lg-5  mt-4 mt-lg-0" id="navbarSupportedContent">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item"><a class="nav-link" href="#home"><span>Home</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#team"><span>Our Team</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#departments"><span>Departments</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#faq"><span>FAQ</span></a></li>
                    </ul>
                    
                    <ul class="navbar-nav mt-4 mt-lg-0 ms-auto align-items-center">
                        <li class="nav-item me-lg-2 mb-2 mb-lg-0">
                            <a href="doctor.php" class="btn btn-outline-light py-2 px-3" style="border-radius: 4px; text-decoration: none;">
                                <i class="bi bi-shield-lock me-2" style="margin-right: 5px;"></i>Doctor Portal
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="container py-5">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 col-xl-5 my-5 my-lg-0 text-center text-md-start">
                    <h1 class="h1 light-text-color mt-0">Welcome to Rodencia</h1>
                    <h4 class="h4 light-text-color" style="margin-top: 35px">Book an appointment with our wonderful doctors!</h4>
                </div>
                <div class="col-lg-6 col-xl-4">
                    <div class="card-item round bg-green-light" style="padding: 40px 40px">
                        <h3 class="h3 text-color text-center">Book Appointment</h3>
                        
                        <form action="process-appointment.php" method="POST">
                            <div class="card-content" style="margin-top: 40px">
                                
                                <div class="form-group"><label class="h6">Name *</label>
                                    <div class="form-group" style="margin-top: 10px">
                                        <input class="form-control" type="text" name="name" placeholder="Full Name * " required></input>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 10px"><label class="h6">Email address *</label>
                                    <div class="form-group" style="margin-top: 10px">
                                        <input class="form-control" type="email" name="email" placeholder="example@gmail.com" required></input>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 10px"><label class="h6">Department *</label>
                                    <div class="form-group" style="margin-top: 10px">
                                        <select class="custom-select form-control" name="department" style="margin-top: 10px" required>
                                            <option value="">Please Select</option>
                                            <option value="General Medicine">General Medicine</option>
                                            <option value="Cardiology">Cardiology</option>
                                            <option value="Pediatrics">Pediatrics</option>
                                            <option value="Neurology">Neurology</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 10px"><label class="h6">Time *</label>
                                    <div class="form-group" style="margin-top: 10px">
                                        <select class="custom-select form-control" name="time" required>
                                            <option value="">Select a time</option>
                                            <option value="09:00 AM">09:00 AM Available</option>
                                            <option value="11:00 AM">11:00 AM Available</option>
                                            <option value="02:00 PM">02:00 PM Available</option>
                                            <option value="04:00 PM">04:00 PM Available</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <button type="submit" class="btn bg-green-primary w-100" style="margin-top: 40px">
                                <span>Book Appointment</span>
                            </button>
                        </form>
                        
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
                            <div class="icn-circle circle-md bg-white mx-auto mb-3"><i class="bi bi-heart-pulse text-green-primary" style="font-size: 1.5rem;"></i></div>
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
                            <div class="icn-circle circle-md bg-white mx-auto mb-3"><i class="bi bi-activity text-green-primary" style="font-size: 1.5rem;"></i></div>
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
                            <div class="icn-circle circle-md bg-white mx-auto mb-3"><i class="bi bi-backpack text-green-primary" style="font-size: 1.5rem;"></i></div>
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
                            <div class="icn-circle circle-md bg-white mx-auto mb-3"><i class="bi bi-diagram-3 text-green-primary" style="font-size: 1.5rem;"></i></div>
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

    <section id="faq" class="light-gray-1">
        <div class="py-7">
            <div class="container py-5  py-md-7">
                <div class="row justify-content-center">
                    <div class="text-center col-lg-6">
                        <h2 class="h2 text-color">FAQ</h2>
                        <p class="paragraph second-text-color" style="margin-top: 10px">Problems trying to resolve the
                            conflict between
                            the two major realms of Classical physics: Newtonian mechanics </p>
                </div>
            </div>
            <div class="container mt-5">
                <div class="row align-items-stretch">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="d-flex card-item bg-green-light" style="padding: 25px 25px">
                            <div class="d-flex"><i class="bi bi-chevron-right icn-xs text-green-primary"></i>
                                <div style="margin-left: 20px">
                                    <h5 class="h5 text-color">the quick fox jumps over the
                                        lazy dog</h5>
                                    <h6 class="h6 second-text-color" style="margin-top: 5px">Things on a very small
                                        scale
                                        behave like nothing </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="d-flex card-item bg-green-light" style="padding: 25px 25px">
                            <div class="d-flex"><i class="bi bi-chevron-right icn-xs text-green-primary"></i>
                                <div style="margin-left: 20px">
                                    <h5 class="h5 text-color">the quick fox jumps over the
                                        lazy dog</h5>
                                    <h6 class="h6 second-text-color" style="margin-top: 5px">Things on a very small
                                        scale
                                        behave like nothing</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 ">
                        <div class="d-flex card-item bg-green-light" style="padding: 25px 25px">
                            <div class="d-flex"><i class="bi bi-chevron-right icn-xs text-green-primary"></i>
                                <div style="margin-left: 20px">
                                    <h5 class="h5 text-color">the quick fox jumps over the
                                        lazy dog</h5>
                                    <h6 class="h6 second-text-color" style="margin-top: 5px">Things on a very small
                                        scale
                                        behave like nothing </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row align-items-stretch mt-4">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="d-flex card-item bg-green-light" style="padding: 25px 25px">
                            <div class="d-flex"><i class="bi bi-chevron-right icn-xs text-green-primary"></i>
                                <div style="margin-left: 20px">
                                    <h5 class="h5 text-color">the quick fox jumps over the
                                        lazy dog</h5>
                                    <h6 class="h6 second-text-color" style="margin-top: 5px">Things on a very small
                                        scale
                                        behave like</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="d-flex card-item bg-green-light" style="padding: 25px 25px">
                            <div class="d-flex"><i class="bi bi-chevron-right icn-xs text-green-primary"></i>
                                <div style="margin-left: 20px">
                                    <h5 class="h5 text-color">the quick fox jumps over the
                                        lazy dog</h5>
                                    <h6 class="h6 second-text-color" style="margin-top: 5px">Things on a very small
                                        scale
                                        behave like</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="d-flex card-item bg-green-light" style="padding: 25px 25px">
                            <div class="d-flex"><i class="bi bi-chevron-right icn-xs text-green-primary"></i>
                                <div style="margin-left: 20px">
                                    <h5 class="h5 text-color">the quick fox jumps over the
                                        lazy dog</h5>
                                    <h6 class="h6 second-text-color" style="margin-top: 5px">Things on a very small
                                        scale
                                        behave like</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="dark-background-color">
        <div class="container py-5 py-md-7">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-5 mb-4 mb-lg-0">
                    <h2 class="h2 light-text-color">Get In Touch</h2>
                    <p class="paragraph light-text-color" style="margin-top: 15px">The gradual accumulation of
                        information about atomic and
                        small-scale behaviour during the first quarter of the 20th </p>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="form-group">
                        <div class="input-group input-style-2 mb-2 mr-sm-2"><input class="form-control" type="text"
                                placeholder="Your Email"></input>
                            <div class="input-group-append mt-2 mt-sm-0"><button
                                    class="btn bg-green-accent h-100"><span>Subscribe</span></button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="position-relative">
        <div>
            <div class="container py-5 py-md-7">
                <div class="row justify-content-center align-items-stretch">
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <h3 class="h3 text-color">Get In Touch</h3>
                        <p class="paragraph second-text-color mt-4">the quick fox jumps over the
                            lazy dog</p>
                        <div class="card-content py-2 mt-4"><a class="facebook" href="#"><i
                                    class="bi bi-facebook icn-sm text-green-primary"></i></a><a class="instagram"
                                href="#"><i class="bi bi-instagram icn-sm text-green-primary"
                                    style="margin-left: 20px"></i></a><a class="twitter" href="#"><i
                                    class="bi bi-twitter icn-sm text-green-primary" style="margin-left: 20px"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <h3 class="h3 text-color">Company info</h3>
                        <div class="links" style="margin-top: 20px"><a class="link second-text-color d-block"
                                href="#">About Us</a><a class="link second-text-color d-block" href="#"
                                style="margin-top: 10px">Carrier</a><a class="link second-text-color d-block" href="#"
                                style="margin-top: 10px">We are hiring</a><a class="link second-text-color d-block"
                                href="#" style="margin-top: 10px">Blog</a></div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <h3 class="h3 text-color">Features</h3>
                        <div class="links" style="margin-top: 20px"><a class="link second-text-color d-block"
                                href="#">Business Marketing</a><a class="link second-text-color d-block" href="#"
                                style="margin-top: 10px">User Analytic</a><a class="link second-text-color d-block"
                                href="#" style="margin-top: 10px">Live Chat</a><a class="link second-text-color d-block"
                                href="#" style="margin-top: 10px">Unlimited Support</a></div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h3 class="h3 text-color">Resources</h3>
                        <div class="links" style="margin-top: 20px"><a class="link second-text-color d-block"
                                href="#">IOS & Android</a><a class="link second-text-color d-block" href="#"
                                style="margin-top: 10px">Watch a Demo</a><a class="link second-text-color d-block"
                                href="#" style="margin-top: 10px">Customers</a><a class="link second-text-color d-block"
                                href="#" style="margin-top: 10px">API</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="light-gray-1">
            <div class="container py-4">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-6">
                        <h6 class="h6 second-text-color text-md-center ">Made With Love By Figmaland All Right Reserved
                        </h6>
                    </div>
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