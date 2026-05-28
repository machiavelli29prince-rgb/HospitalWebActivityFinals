<?php
$pdo = new PDO("mysql:host=localhost;dbname=rodencia","root","", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->query('SHOW CREATE TABLE password_resets');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($row);
?>
