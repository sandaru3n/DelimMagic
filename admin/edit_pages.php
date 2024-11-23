<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch all pages
$stmt = $pdo->query("SELECT * FROM pages");
$pages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Pages</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            padding-top: 20px;
        }
        .sidebar h2 {
            color: white;
            text-align: center;
            margin-bottom: 1rem;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #007bff;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
<div class="wrapper">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="edit_pages.php">Edit Pages</a>
        <a href="settings.php">Settings</a>
        <a href="view_messages.php">View Messages</a>
        <a href="edit_contact_info.php">Edit Info</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h2>Edit Pages</h2>
        <p>Select a page to edit its content.</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Page Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(ucfirst($page['title'])); ?></td>
                        <td>
                            <a href="edit_page.php?id=<?php echo $page['id']; ?>" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
