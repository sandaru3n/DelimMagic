<?php
require 'admin/db.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    // Validate inputs
    if ($name && $email && $message) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Insert into the database
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $message])) {
                $success = "Thank you! Your message has been sent.";
            } else {
                $error = "Sorry, something went wrong. Please try again.";
            }
        } else {
            $error = "Invalid email format.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

// Fetch settings from the database
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();

// Fetch contact information from the database
$stmt = $pdo->query("SELECT * FROM contact_info LIMIT 1");
$contact_info = $stmt->fetch();

$logo_path = $settings['logo_path'] ?? '/admin/uploads/default-logo.png';
$google_analytics_code = $settings['google_analytics_code'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Analytics Code -->
    <?php if (!empty($google_analytics_code)): ?>
            <?php echo $google_analytics_code; ?>
    <?php endif; ?>
   
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo htmlspecialchars($settings['favicon_path']); ?>" type="image/x-icon">
    
    <style>
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
    <a class="navbar-brand" href="#">
        <img src="<?php echo htmlspecialchars($logo_path); ?>" height="70px" width="210px" alt="Logo"> <!-- Display logo from settings -->
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


 
    


    <div class="container mt-5">
        
        <div class="mb-4">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($contact_info['email'] ?? 'Not available'); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($contact_info['phone'] ?? 'Not available'); ?></p>
        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($contact_info['address'] ?? 'Not available')); ?></p>
    </div>
        
        
        
        <h1>Contact Us</h1>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="contact.php" method="post">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>
    
    <br>

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

    <!-- Include Bootstrap JS and its dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
