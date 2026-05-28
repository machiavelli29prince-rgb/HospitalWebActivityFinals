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
                    $success = 'Account created successfully! You can now log in.';
                } else {
                    $error = $auth->getLastError() ?: 'An error occurred while creating your account.';
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

            $mailer->sendEmail($subscribeEmail, $subscriberSubject, $subscriberBody);
            $success = 'Thank you for subscribing! Check your email for confirmation details.';
        } else {
            $error = 'Please provide a valid business email address to subscribe.';
        }
    }
}
