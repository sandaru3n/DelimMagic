<?php
// page.php
require 'admin/db.php';

// Get the page slug from the URL, default to 'privacy' if not provided
$slug = isset($_GET['slug']) ? $_GET['slug'] : 'privacy';

// Fetch the page content based on slug
$stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ?");
$stmt->execute([$slug]);
$page = $stmt->fetch();

// Fetch settings from the database
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();


if (!$page) {
    echo "Page not found.";
    exit();
}

// Set up SEO meta details
$seo_title = $page['title'] ?? 'My Website';
$seo_description = substr(strip_tags($page['content']), 0, 160);
$favicon_path = '/admin/uploads/favicon.png'; // Replace with your favicon path
$logo_path = '/admin/uploads/logo.png'; // Replace with your logo path
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
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo htmlspecialchars($settings['favicon_path']); ?>" type="image/x-icon">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .footer { background-color: #333; color: white; padding: 15px; text-align: center; }
        
        
        /* Footer Styling */
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
    <a class="navbar-brand" href="/">
        <img src="<?php echo htmlspecialchars($logo_path); ?>" height="70px" width="210px" alt="Logo"> <!-- Display logo from settings -->
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

<div class="container mt-5">
    <h1><?php echo htmlspecialchars($page['title']); ?></h1>
    <div><?php echo $page['content']; ?></div>
</div>

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
