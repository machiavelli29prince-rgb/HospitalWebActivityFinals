<?php
session_start();
require_once 'appointLib.php';
$appLib = new Appointment();

$error = '';
$success = '';

// Auth Controller: Handles account sign-up form processing
if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($name) && !empty($email) && !empty($password) && !empty($role)) {
        if ($appLib->getUserByEmail($email)) {
            $error = "This email is already registered in our system.";
        } else {
            if ($appLib->registerUser($name, $email, $password, $role)) {
                $success = "Account created successfully! Please log in on the left.";
            } else {
                $error = "An error occurred while creating your account.";
            }
        }
    } else {
        $error = "Please fill out all tracking registration properties.";
    }
}

// Auth Controller: Verifies crypt hashes and initializes specific dashboard sessions
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $user = $appLib->getUserByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_role'] = $user->role;

            if ($user->role === 'doctor') {
                header("Location: doctor.php");
            } else {
                header("Location: users.php");
            }
            exit();
        } else {
            $error = "Invalid email or password combination.";
        }
    } else {
        $error = "Please fill out all credential spaces.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Authentication Portal - Rodencia Hospital</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">
    <style>
        body { background-color: #f5f5f5; font-family: sans-serif; }
        .bg-green-primary { background-color: #2e7d32 !important; color: white !important; }
        .bg-green-primary:hover { background-color: #1b5e20 !important; }
        .bg-green-light { background-color: #e8f5e9 !important; }
        .text-green-primary { color: #2e7d32 !important; }
        .auth-card { border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #dee2e6; }
    </style>
</head>
<body>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
                <div class="alert alert-success mb-4"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="row bg-white auth-card g-0">
                
                <div class="col-md-6 p-5">
                    <h3 class="fw-bold text-dark">Sign In</h3>
                    <p class="text-muted small">Access your patient clinical records portal dashboard interface.</p>
                    <hr class="my-4 text-muted opacity-25">
                    
                    <form action="auth.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required autocomplete="username">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required autocomplete="current-password">
                        </div>
                        <button type="submit" name="login" class="btn bg-green-primary w-100 py-2 fw-bold shadow-sm">Sign Into System</button>
                    </form>
                </div>

                <div class="col-md-6 p-5 bg-green-light border-start">
                    <h3 class="fw-bold text-green-primary">Create Account</h3>
                    <p class="text-muted small">Register account parameters to our active hospital terminal logs.</p>
                    <hr class="my-4 text-muted opacity-25">
                    
                    <form action="auth.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Full Name</label>
                            <input type="text" name="name" class="form-control" required autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Email Address</label>
                            <input type="email" name="email" class="form-control" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Secure Password</label>
                            <input type="password" name="password" class="form-control" required autocomplete="new-password">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">Portal Authorization Role</label>
                            <select name="role" class="form-select" required>
                                <option value="patient">Patient System Client Node</option>
                                <option value="doctor">Medical Specialist Staff (Doctor)</option>
                            </select>
                        </div>
                        <button type="submit" name="signup" class="btn btn-dark w-100 py-2 fw-bold shadow-sm">Register Credentials</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>