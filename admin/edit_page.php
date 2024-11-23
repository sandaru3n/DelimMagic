<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$page_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
$stmt->execute([$page_id]);
$page = $stmt->fetch();

if (!$page) {
    echo "Page not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("UPDATE pages SET title = ?, content = ? WHERE id = ?");
    $stmt->execute([$title, $content, $page_id]);

    header("Location: edit_pages.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
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
        <a href="logout.php">Logout</a>
    </div>
    
    <div class="content">
        <h1>Edit Page: <?php echo htmlspecialchars($page['title']); ?></h1>
        <form action="edit_page.php?id=<?php echo $page_id; ?>" method="post">
            <div class="form-group">
                <label for="title">Page Title</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($page['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="10"><?php echo htmlspecialchars($page['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => console.error(error));
</script>
</body>
</html>
