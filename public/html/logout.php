<?php
require_once __DIR__ . '/core/bootstrap.php';

// Destroy the session and redirect to home
session_destroy();
header('Location: utils/index.php');
exit();
