<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$message = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Retrieve the blog post by ID
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();

    if (!$post) {
        echo "Blog post not found.";
        exit();
    }
} else {
    echo "Invalid blog post ID.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image_path = $post['image_path']; // Keep the existing image path by default

    // Validate inputs
    if ($title && $content) {
        // Handle image upload if a new file is provided
        if (!empty($_FILES['image']['name'])) {
            $target_directory = 'uploads/';
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $image_path = $target_directory . $file_name;

            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $message = "Failed to upload image. Check folder permissions.";
                    $image_path = $post['image_path']; // Revert to the original image path
                }
            } else {
                $message = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
                $image_path = $post['image_path']; // Revert to the original image path
            }
        }

        // Update the blog post
        $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, content = ?, image_path = ? WHERE id = ?");
        if ($stmt->execute([$title, $content, $image_path, $id])) {
            $message = "Blog post updated successfully!";
        } else {
            $message = "Failed to update blog post.";
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
    <title>Edit Blog Post</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    
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
        <h2>Edit Blog Post</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image (optional)</label>
                <input type="file" name="image" id="image" class="form-control-file">
                <?php if (!empty($post['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($post['image_path']); ?>" width="100" alt="Current Image" class="mt-3">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Blog Post</button>
        </form>
    </div>
    
    <br><br>

    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => console.error(error));
    </script>
</body>
</html>
