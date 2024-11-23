<?php
// install.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get database credentials and admin details from the form
    $host = $_POST['db_host'];
    $db = $_POST['db_name'];
    $user = $_POST['db_user'];
    $pass = $_POST['db_pass'];
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];
    $reset_code = $_POST['reset_code'];

    // Establish database connection
    try {
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
        $pdo->exec("USE $db");

        // Create or alter the settings table with necessary columns
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                header_name VARCHAR(255) DEFAULT 'My Website',
                logo_path VARCHAR(255) DEFAULT 'uploads/default-logo.png',
                favicon_path VARCHAR(255) DEFAULT 'admin/uploads/default-favicon.png',
                seo_title VARCHAR(255) DEFAULT 'Welcome to My Website',
                seo_description TEXT DEFAULT 'This is a default description.',
                seo_keywords TEXT DEFAULT 'default, keywords, website',
                google_analytics_code TEXT DEFAULT NULL,
                google_ads_code TEXT DEFAULT NULL,
                google_verification_code VARCHAR(255) DEFAULT NULL,
                verification_code VARCHAR(255) DEFAULT NULL
            );
        ");

        // Insert default settings along with the verification code
        $stmt = $pdo->prepare("
            INSERT INTO settings (header_name, logo_path, favicon_path, seo_title, seo_description, seo_keywords, google_analytics_code, google_ads_code, google_verification_code, verification_code)
            VALUES ('My Website', 'uploads/default-logo.png', 'admin/uploads/default-favicon.png', 'Welcome to My Website', 'This is a default description.', 'default, keywords, website', NULL, NULL, NULL, ?)
            ON DUPLICATE KEY UPDATE verification_code=VALUES(verification_code);
        ");
        $stmt->execute([$reset_code]);

        // Create the admins table if it doesn't exist
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admins (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL
            );
        ");
        
        
        // Create `blog_posts` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS blog_posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            image_path VARCHAR(255) DEFAULT NULL
            
        );
    ");
    
    
        // Create `pages` table for editable pages like Privacy, Terms, etc.
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS pages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL
            );
        ");

        // Insert default pages
        $stmt = $pdo->prepare("
            INSERT INTO pages (title, content, slug) VALUES 
            ('Privacy Policy', 'This is the Privacy Policy page content.', 'privacy'),
            ('Terms of Service', 'This is the Terms of Service page content.', 'terms'),
            ('About Us', 'This is the About Us page content.', 'about')
        ");
        $stmt->execute();

        // Create `contact_info` table to store contact details
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS contact_info (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) DEFAULT NULL,
                phone VARCHAR(50) DEFAULT NULL,
                address TEXT DEFAULT NULL
                faboutus TEXT DEFAULT NULL;
            );
        ");
        
        $stmt = $pdo->prepare("
            INSERT INTO contact_info (email, phone, address,faboutus) VALUES 
            ('your-email@example.com', '123-456-7890', '123 Street, City, Country','aboutme')
        ");
        $stmt->execute();

        // Create `contact_messages` table to store messages sent through the contact form
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS contact_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        
        

        // Insert admin credentials
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->execute([$admin_username, $hashed_password]);

        // Create uploads directory if it doesn't exist and set permissions
        $upload_dir = __DIR__ . '/admin/uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Create the admin directory and add .htaccess file
        $admin_dir = __DIR__ . '/admin';
        $admin_htaccess_file = $admin_dir . '/.htaccess';
        $htaccess_content = <<<HTACCESS
RewriteEngine On
RewriteBase /admin/
RewriteRule ^$ login.php [L]
HTACCESS;

        // Ensure the admin directory exists and is writable
        if (!is_dir($admin_dir)) {
            if (!mkdir($admin_dir, 0755, true)) {
                throw new Exception("Failed to create the admin directory.");
            }
        }

        // Write the .htaccess file
        if (file_put_contents($admin_htaccess_file, $htaccess_content) === false) {
            throw new Exception("Failed to create .htaccess file in the admin directory.");
        }

        echo "Installation complete! Please delete this file (install.php) for security purposes.";
        exit();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Installation</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Install Admin Panel</h2>
                <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                <form method="post">
                    <h4>Database Information</h4>
                    <div class="form-group">
                        <label for="db_host">Database Host</label>
                        <input type="text" name="db_host" class="form-control" id="db_host" placeholder="localhost" required>
                    </div>
                    <div class="form-group">
                        <label for="db_name">Database Name</label>
                        <input type="text" name="db_name" class="form-control" id="db_name" placeholder="admin_panel" required>
                    </div>
                    <div class="form-group">
                        <label for="db_user">Database User</label>
                        <input type="text" name="db_user" class="form-control" id="db_user" placeholder="root" required>
                    </div>
                    <div class="form-group">
                        <label for="db_pass">Database Password</label>
                        <input type="password" name="db_pass" class="form-control" id="db_pass">
                    </div>
                    <h4>Admin Account</h4>
                    <div class="form-group">
                        <label for="admin_username">Admin Username</label>
                        <input type="text" name="admin_username" class="form-control" id="admin_username" placeholder="admin" required>
                    </div>
                    <div class="form-group">
                        <label for="admin_password">Admin Password</label>
                        <input type="password" name="admin_password" class="form-control" id="admin_password" required>
                    </div>
                    
                    <h4>Password Reset Verification Code</h4>
                    <div class="form-group">
                        <label for="reset_code">Verification Code (for password reset):</label>
                        <input type="text" name="reset_code" class="form-control" id="reset_code" placeholder="Enter a code for password resets" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Install</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
