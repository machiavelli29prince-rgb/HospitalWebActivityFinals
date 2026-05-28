<?php
require_once __DIR__ . '/core/bootstrap.php';

echo "<pre>";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "\n";
echo "appUrl(''): " . appUrl('') . "\n";
echo "appUrl('css/main.css'): " . appUrl('css/main.css') . "\n";
echo "appUrl('js/jquery-3.4.1.min.js'): " . appUrl('js/jquery-3.4.1.min.js') . "\n";
echo "appUrl('img/rodencia.png'): " . appUrl('img/rodencia.png') . "\n";
echo "appUrl('html/views/forgot-password.php'): " . appUrl('html/views/forgot-password.php') . "\n";
echo "</pre>";
?>
