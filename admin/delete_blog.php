<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

// Confirm the post exists before deleting
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: dashboard.php");
    exit();
}

// Delete the blog post
$stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit();
?>
