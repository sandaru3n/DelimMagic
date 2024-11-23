<?php
// db.php

$host = 'localhost';
$db = 'queenzbf_website_admin_panel';
$user = 'queenzbf_delimcomma';
$pass = 'Sandaru2002@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>