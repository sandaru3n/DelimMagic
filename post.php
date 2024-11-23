<?php
// post.php

require 'admin/db.php';

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the specific blog post from the database
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    // If no post found, redirect to a 404 page or display a message
    header("HTTP/1.0 404 Not Found");
    echo "Post not found.";
    exit();
}

// Fetch website settings for SEO metadata
$settings_stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $settings_stmt->fetch();

// Default SEO data
$seo_title = $post['title'] ?? 'Blog Post';
$seo_description = substr(strip_tags($post['content']), 0, 160);
$favicon_path = $settings['favicon_path'] ?? '/admin/uploads/favicon.png';
$logo_path = $settings['logo_path'] ?? '/admin/uploads/default-logo.png';
$header_name = $settings['header_name'] ?? 'My Website';
$google_analytics_code = $settings['google_analytics_code'] ?? '';


// Fetch contact information from the database
$stmt = $pdo->query("SELECT * FROM contact_info LIMIT 1");
$contact_info = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seo_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seo_description); ?>">
    
    <!-- Google Analytics Code -->
    <?php if (!empty($google_analytics_code)): ?>
            <?php echo $google_analytics_code; ?>
    <?php endif; ?>

    <link rel="icon" href="<?php echo htmlspecialchars($favicon_path); ?>" type="image/x-icon">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        main { flex: 1; }
        footer {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 0.9rem;
        }
        footer a {
            color: #ccc;
            text-decoration: none;
            margin: 0 5px;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="#">
        <img src="<?php echo htmlspecialchars($logo_path); ?>" height="70px" width="210px" alt="Logo">
        <?php echo htmlspecialchars($header_name); ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/page.php?slug=about">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="/page.php?slug=privacy">Privacy</a></li>
            <li class="nav-item"><a class="nav-link" href="/page.php?slug=terms">Terms</a></li>
            <li class="nav-item"><a class="nav-link" href="/contact.php">Contact Us</a></li>
        </ul>
    </div>
</nav>

<main class="container">
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <img src="admin/<?php echo htmlspecialchars($post['image_path']); ?>" alt="Blog Image" width="400px" height="300px">
    <p><small>Published on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></small></p>
    <div>
        <?php echo $post['content']; ?>
    </div>
</main>

<br>

<br><br>
<br><br><br><br><br>
<br><br><br><br>
<!-- Left-Aligned Modern Footer -->
<footer class="footer bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <!-- About Us Section -->
            <div class="col-md-4 mb-4 text-left">
                <h5 class="text-uppercase">About Us</h5>
                <p class="text-muted">
                    <?php echo htmlspecialchars($contact_info['faboutus'] ?? 'Not available'); ?>
                </p>
            </div>

            <!-- Quick Links Section -->
            <div class="col-md-4 mb-4 text-left">
                <h5 class="text-uppercase">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="/index.php" class="text-muted">Home</a></li>
                    <li><a href="/page.php?slug=about" class="text-muted">About Us</a></li>
                    <li><a href="/page.php?slug=privacy" class="text-muted">Privacy Policy</a></li>
                    <li><a href="/page.php?slug=terms" class="text-muted">Terms of Service</a></li>
                    <li><a href="/contact.php" class="text-muted">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="col-md-4 mb-4 text-left">
                <h5 class="text-uppercase">Contact Us</h5>
                <ul class="list-unstyled text-muted">
                    <li><strong>Email:</strong> <?php echo htmlspecialchars($contact_info['email'] ?? 'Not available'); ?></li>
                    <li><strong>Phone:</strong><?php echo htmlspecialchars($contact_info['phone'] ?? 'Not available'); ?></li>
                    <li><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($contact_info['address'] ?? 'Not available')); ?></li>
                </ul>
                <!-- Social Media Icons -->
                <div class="social-links mt-3">
                    <a href="#" class="text-white mr-3"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white mr-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white mr-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white mr-3"><i class="fab fa-linkedin fa-lg"></i></a>
                </div>
            </div>
        </div>

        
            
        </div>
    </div>
</footer>




<!-- Footer Section -->
<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($header_name); ?> | <a href="/page.php?slug=privacy">Privacy Policy</a> | <a href="/page.php?slug=terms">Terms of Service</a></p>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
