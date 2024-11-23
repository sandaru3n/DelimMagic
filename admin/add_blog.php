<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Initialize message variable
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = $_POST['content']; // Receive content as raw HTML from Quill editor
    $image_path = '';

    // Validate inputs
    if ($title && $content) {
        // Handle image upload if a file is provided
        if (!empty($_FILES['image']['name'])) {
            $target_directory = 'uploads/';
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $image_path = $target_directory . $file_name;

            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $message = "Failed to upload image. Check folder permissions.";
                }
            } else {
                $message = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            }
        }

        // Insert the new blog post
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, image_path) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $content, $image_path])) {
            $message = "Blog post added successfully!";
        } else {
            $message = "Failed to add blog post.";
        }
    } else {
        $message = "Title and content are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Blog Post</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
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
            flex-grow: 1;
        }
        .ql-container {
            height: 250px; /* Adjust the height of the editor container as needed */
        }
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
            }
            .content {
                margin-left: 0;
            }
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
            <h2>Add New Blog Post</h2>
            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <div id="editor-container">
                        <div id="content" class="form-control"></div>
                    </div>
                    <input type="hidden" name="content" id="content-hidden">
                </div>
                <div class="form-group">
                    <label for="image">Image (optional)</label>
                    <input type="file" name="image" id="image" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary">Add Blog Post</button>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        var quill = new Quill('#content', {
            theme: 'snow'
        });

        var form = document.querySelector('form');
        form.onsubmit = function() {
            var content = document.querySelector('input[name=content]');
            content.value = quill.root.innerHTML;
        };
    </script>
</body>
</html>
