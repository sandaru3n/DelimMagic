<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch all blog posts
$stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
$blog_posts = $stmt->fetchAll();

// Fetch settings from the database
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo htmlspecialchars($settings['favicon_path']); ?>" type="image/x-icon">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            display: flex;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
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
            width: calc(100% - 250px);
            overflow: hidden;
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
        <h1>Admin Dashboard</h1>
        <p>Use the sidebar to navigate through the options.</p>

        <div class="container mt-4">
            <h2>Manage Blog Posts</h2>
            <a href="add_blog.php" class="btn btn-success mb-3">Add New Blog Post</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blog_posts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td>
                                <a href="edit_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
