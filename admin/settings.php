<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Load existing settings
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();

// Initialize variables for any error or success messages
$uploadError = "";
$uploadSuccess = "";

// Update settings if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $header_name = $_POST['header_name'];
    $seo_title = $_POST['seo_title'];
    $seo_description = $_POST['seo_description'];
    $seo_keywords = $_POST['seo_keywords'];
    $google_analytics_code = $_POST['google_analytics_code'];
    $google_ads_code = $_POST['google_ads_code'];
    $google_verification_code = $_POST['google_verification_code'];
    $logo_path = $settings['logo_path']; // Default to current logo path if no new file is uploaded
    $favicon_path = $settings['favicon_path'];
    
    
    // Handle favicon upload
    if (!empty($_FILES['favicon']['name'])) {
        $favicon = $_FILES['favicon'];
        $target_directory = __DIR__ . '/uploads/';
        if (!is_dir($target_directory)) {
            mkdir($target_directory, 0755, true);
        }
        $new_favicon_path = $target_directory . basename($favicon['name']);
        $web_favicon_path = 'admin/uploads/' . basename($favicon['name']);

        $fileType = strtolower(pathinfo($new_favicon_path, PATHINFO_EXTENSION));
        $validTypes = ['ico', 'png'];

        if (in_array($fileType, $validTypes) && $favicon['size'] <= 500000) { // 500KB limit
            if (move_uploaded_file($favicon['tmp_name'], $new_favicon_path)) {
                $favicon_path = $web_favicon_path;
                $uploadSuccess = "Favicon uploaded successfully!";
            } else {
                $uploadError = "Error uploading the favicon file.";
            }
        } else {
            $uploadError = "Invalid favicon file type or size exceeds 500KB.";
        }
    }
    
    
    

    // Handle logo upload
    if (!empty($_FILES['logo']['name'])) {
        $logo = $_FILES['logo'];
        $target_directory = __DIR__ . '/uploads/';
        
        // Ensure the uploads directory exists and is writable
        if (!is_dir($target_directory)) {
            mkdir($target_directory, 0755, true); // Create the directory if it doesn't exist
        }

        $new_logo_path = $target_directory . basename($logo['name']);
        $web_logo_path = 'admin/uploads/' . basename($logo['name']); // Path to store in database

        // Validate file type and size
        $fileType = strtolower(pathinfo($new_logo_path, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $validTypes)) {
            if ($logo['size'] <= 2000000) { // Limit file size to 2MB
                // Move file to the upload directory
                if (move_uploaded_file($logo['tmp_name'], $new_logo_path)) {
                    $logo_path = $web_logo_path; // Update logo path to store in database
                    $uploadSuccess = "Logo uploaded successfully!";
                } else {
                    $uploadError = "Error uploading the file. Please check server permissions.";
                }
            } else {
                $uploadError = "File size exceeds the 2MB limit.";
            }
        } else {
            $uploadError = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    // Update settings in the database
    $stmt = $pdo->prepare("UPDATE settings SET header_name = ?, logo_path = ?, favicon_path = ?, seo_title = ?, seo_description = ?, seo_keywords = ?, google_analytics_code = ?, google_ads_code = ?, google_verification_code = ? WHERE id = 1");
    $stmt->execute([$header_name, $logo_path, $favicon_path, $seo_title, $seo_description, $seo_keywords, $google_analytics_code, $google_ads_code, $google_verification_code]);

    header("Location: settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    <h2>Website Settings</h2>

    <!-- Display success or error messages for file upload -->
    <?php if ($uploadSuccess): ?>
        <div class="alert alert-success"><?php echo $uploadSuccess; ?></div>
    <?php elseif ($uploadError): ?>
        <div class="alert alert-danger"><?php echo $uploadError; ?></div>
    <?php endif; ?>

    <form action="settings.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Header Name:</label>
            <input type="text" name="header_name" class="form-control" value="<?php echo htmlspecialchars($settings['header_name']); ?>">
        </div>

        <div class="form-group">
            <label>Logo:</label>
            <input type="file" name="logo" class="form-control">
            <?php if (!empty($settings['logo_path']) && file_exists(__DIR__ . '/' . $settings['logo_path'])): ?>
                <img src="<?php echo htmlspecialchars($settings['logo_path']); ?>" width="100" class="mt-2" alt="Current Logo">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>SEO Title:</label>
            <input type="text" name="seo_title" class="form-control" value="<?php echo htmlspecialchars($settings['seo_title']); ?>">
        </div>

        <div class="form-group">
            <label>SEO Description:</label>
            <textarea name="seo_description" class="form-control"><?php echo htmlspecialchars($settings['seo_description']); ?></textarea>
        </div>

        <div class="form-group">
            <label>SEO Keywords:</label>
            <textarea name="seo_keywords" class="form-control"><?php echo htmlspecialchars($settings['seo_keywords']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Favicon:</label>
            <input type="file" name="favicon" class="form-control">
            <?php if (!empty($settings['favicon_path']) && file_exists(__DIR__ . '/' . $settings['favicon_path'])): ?>
                <img src="<?php echo htmlspecialchars($settings['favicon_path']); ?>" width="16" height="16" class="mt-2" alt="Current Favicon">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Google Analytics Code:</label>
            <textarea name="google_analytics_code" class="form-control"><?php echo htmlspecialchars($settings['google_analytics_code']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Google Ads Code:</label>
            <textarea name="google_ads_code" class="form-control"><?php echo htmlspecialchars($settings['google_ads_code']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Google Verification Code:</label>
            <input type="text" name="google_verification_code" class="form-control" value="<?php echo htmlspecialchars($settings['google_verification_code']); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

</body>
</html>
