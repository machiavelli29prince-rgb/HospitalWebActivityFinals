<?php
require_once __DIR__ . '/../core/bootstrap.php';

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
                    $success = 'Account created successfully! Please log in on the left.';
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
                    header('Location: ' . appUrl('html/views/doctor.php'));
                } else {
                    header('Location: ' . appUrl('html/views/users.php'));
                }
                exit();
            }

            $error = 'Invalid email or password combination.';
        } else {
            $error = 'Please fill out all credential fields.';
        }
    }
}
