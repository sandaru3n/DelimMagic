<?php
// index.php

require 'admin/db.php';



// Fetch latest blog posts
$blog_posts_stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 3");
$blog_posts = $blog_posts_stmt->fetchAll();

// Fetch settings from the database
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();

// Default settings in case values are missing in the database
$seo_title = $settings['seo_title'] ?? 'Welcome to Delim Magic';
$seo_description = $settings['seo_description'] ?? 'This is the default description for our website.';
$seo_keywords = $settings['seo_keywords'] ?? 'default, keywords, website';
$google_analytics_code = $settings['google_analytics_code'] ?? '';
$google_ads_code = $settings['google_ads_code'] ?? '';
$google_verification_code = $settings['google_verification_code'] ?? '';
$header_name = $settings['header_name'] ?? 'Delim Magic';
$logo_path = $settings['logo_path'] ?? '/admin/uploads/default-logo.png';


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
    <meta name="keywords" content="<?php echo htmlspecialchars($seo_keywords); ?>">

    <!-- Google Verification Code for Search Console -->
    <?php if (!empty($google_verification_code)): ?>
        <meta name="google-site-verification" content="<?php echo htmlspecialchars($google_verification_code); ?>">
    <?php endif; ?>
    
    

    <!-- Favicon -->
    <link rel="icon" href="<?php echo htmlspecialchars($settings['favicon_path']); ?>" type="image/x-icon">

    <!-- Google Analytics Code -->
    <?php if (!empty($google_analytics_code)): ?>
            <?php echo $google_analytics_code; ?>
    <?php endif; ?>

    <!-- Google Ads Code -->
    <?php if (!empty($google_ads_code)): ?>
            <?php echo $google_ads_code; ?>
    <?php endif; ?>
    

    <!-- Bootstrap CSS and Google Fonts -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
   <!-- Slider -->
   <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    
    <style>
        .container1 { max-width: 1200px; margin: auto; padding: 20px; }
        .navbar-brand img { height: 70px; width: 210px; }
        .blog-section { padding: 40px 0; }
        .blog-post { margin-bottom: 20px; }
        .footer { background-color: #333; color: white; padding: 15px; text-align: center; }
    
        /* Apply Google Fonts */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
             background-color: #f4f4f4; /* Optional: sets a light gray background for the page */
        }

        /* Main Content Styling */
        main {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .container {
            display: flex;
            /*justify-content: space-between;)*/
            gap: 50px; /* Reduced the gap between columns from 20px to 10px */
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .column {
            width: 100%;
            max-width: 300px;
        }

        /* Textarea and Line Number Styling */
        .textarea-wrapper {
            position: relative;
            display: flex;
            width: 100%;
            margin-bottom: 10px;
        }

        .line-numbers {
            position: sticky;
            top: 0;
            width: 30px;
            background-color: #f3f3f3;
            color: #666;
            padding: 10px 5px;
            border-right: 1px solid #ddd;
            text-align: right;
            overflow: hidden;
            font-size: 0.9rem;
            height: 300px;
        }

        textarea {
            width: 100%;
            height: 300px;
            padding-left: 40px;
            padding-top: 10px;
            resize: none;
            overflow-y: scroll;
            font-size: 1rem;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        /* Custom Button Styling */
        .btn-custom {
            width: 80px;
            font-size: 16px;
            margin: 5px 0;
        }

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
        
        
        
     .blog-section {
        padding: 40px 0;
    }
    .blog-post {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
    }
    .blog-post img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
    .blog-post-content {
        max-width: 600px;
    }
    .blog-post-content p {
        margin: 0;
        font-size: 0.9rem;
        color: #555;
    }
    
    .text-slider p {
    padding: 20px;
    margin: 0;
    color: #fff; /* White text color */
    font-size: 1.2rem;
    line-height: 1.4;
    text-align: center;
    transition: background-color 0.5s ease-in-out; /* Smooth background color transition */
}


.container {
    max-width: 960px; /* Adjusts the maximum width of the content */
    margin: 20px auto; /* Centers the container with automatic margins */
    padding: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: adds a subtle shadow around the container */
}

.details {
    
    background-color:gold;
    color: white;
    padding: 20px;
    border-radius: 5px; /* Optional: rounds the corners of the details block */
}

.swiper-slide {
    margin-bottom: 10px; /* Adds space between slides */
}

.slider-item {
    margin: 0; /* Removes default paragraph margins */
    padding: 8px 0; /* Adds vertical padding but no horizontal padding */
    font-size: 16px; /* Sets a standard font size for text */
    color:black;
}

.slider-item.title {
    font-weight: bold; /* Makes the title bold */
    font-size: 27px; /* Larger font size for the title */
    color:black;
    text-align:center;
}

.slider-item.description {
    
}

.slider-item.instructions {
    font-size: 20px; /* Slightly smaller font size for instructions */
    color:black;
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
    
    

<!-- Single Navbar for Desktop and Mobile -->






<!-- Main Content Section -->
<main>
<div class="container">
    <div class="details">
        <div class="swiper-slide"><p class="slider-item title"><?php echo htmlspecialchars($seo_title); ?></p></div>
        <div class="swiper-slide"><p class="slider-item description"><?php echo htmlspecialchars($seo_description); ?></p></div>
        
    </div>
    
    <div class="swiper-slide"><p class="slider-item instructions" align="center">Enter your column data on either side, and use the buttons to convert between formats.</p></div>

    <div class="container">
        <div class="column">
            <h3>Column Data Here...</h3>
            <div class="textarea-wrapper">
                <div class="line-numbers" id="lineNumbers">1</div>
                <textarea name="inputData" id="inputData" placeholder="Enter data here, one item per line..." oninput="updateLineNumbers()" onscroll="syncScroll()"></textarea>
            </div>
        </div>
        
        <!-- Controls Section -->
        <div class="controls">
            <select id="delimiterSelect" class="form-control mb-3">
                <option value=",">Comma</option>
                <option value=";">Semicolon</option>
                <option value="|">Vertical Bar</option>
                <option value=" ">Space</option>
                <option value="\n">New Line</option>
            </select>
            
            <!-- Each button wrapped in a div for vertical alignment -->
            <div class="mb-2">
            <button type="button" class="btn btn-primary btn-block" onclick="convertToDelimited('inputData', 'outputData')">
            <img src="uploads/rightarrow.png" alt="Convert" style="width: 40px; height: 40px;">
            </button>
            </div>
            
            <div class="mb-2">
            <button type="button" class="btn btn-primary btn-block" onclick="convertToLineSeparated('outputData', 'inputData')">
            <img src="uploads/leftarrow.png" alt="Convert" style="width: 40px; height: 40px;">
            </button>
            </div>
            
            
             <div class="mb-2">
            <button type="button" class="btn btn-danger btn-block" onclick="clearText()">
            <img src="uploads/closed.png" alt="Convert" style="width: 40px; height: 30px;">
            </button>
            </div>
            
            <div class="mb-2">
                <button type="button" class="btn btn-warning btn-block" onclick="copyToClipboard()">Copy</button>
            </div>
        </div>

        <!-- Output Column -->
        <div class="column">
            <h3>Delimited Data Here...</h3>
            <textarea id="outputData" placeholder="Enter delimited data here..."></textarea>
        </div>
    </div>
</main>




<main class="container1">
<section class="blog-section">
    <h2>Latest Blog Posts</h2>
    <?php foreach ($blog_posts as $post): ?>
        <div class="blog-post">
            <img src="admin/<?php echo htmlspecialchars($post['image_path']); ?>" alt="Blog Image" width="200px" height="200px">
            <div class="blog-post-content">
                <h5><?php echo htmlspecialchars($post['title']); ?></h5>
                <p><?php echo substr(strip_tags($post['content']), 0, 150); ?>...</p>
                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm mt-2">Read More</a>
            </div>
        </div>
    <?php endforeach; ?>
</section>
</main>


<script>
    function updateLineNumbers() {
        const textarea = document.getElementById("inputData");
        const lineNumbers = document.getElementById("lineNumbers");

        // Calculate number of lines based on the textarea's value
        const lines = textarea.value.split('\n').length || 1;
        lineNumbers.innerHTML = Array.from({ length: lines }, (_, i) => i + 1).join('<br>');
    }

    function clearText() {
        document.getElementById("inputData").value = "";
        document.getElementById("outputData").value = "";
        updateLineNumbers();
    }

    function syncScroll() {
        const textarea = document.getElementById("inputData");
        const lineNumbers = document.getElementById("lineNumbers");
        lineNumbers.scrollTop = textarea.scrollTop;
    }

    function getSelectedDelimiter() {
        const delimiter = document.getElementById("delimiterSelect").value;
        return delimiter === "\\n" ? "\n" : delimiter;
    }

    function convertToDelimited(fromId, toId) {
        const fromTextarea = document.getElementById(fromId);
        const toTextarea = document.getElementById(toId);
        const delimiter = getSelectedDelimiter();

        // Convert line-separated text to the chosen delimiter format
        const lines = fromTextarea.value.split('\n').map(line => line.trim()).filter(line => line);
        toTextarea.value = lines.join(delimiter);
    }

    function convertToLineSeparated(fromId, toId) {
        const fromTextarea = document.getElementById(fromId);
        const toTextarea = document.getElementById(toId);
        const delimiter = getSelectedDelimiter();

        // Convert delimited text to line-separated text
        const items = fromTextarea.value.split(delimiter).map(item => item.trim()).filter(item => item);
        toTextarea.value = items.join("\n");
        updateLineNumbers();
    }

    function copyToClipboard() {
        const output = document.getElementById("outputData");
        output.select();
        output.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");

        // Provide user feedback
        const copyButton = document.querySelector(".btn-warning");
        copyButton.textContent = "Copied!";
        setTimeout(() => { copyButton.textContent = "Copy"; }, 2000);
    }

    document.addEventListener("DOMContentLoaded", updateLineNumbers);
</script>


<!-- Include Bootstrap JS and its dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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

        <hr class="border-light">

        <!-- Footer Bottom -->
        <div class="row">
            
        </div>
    </div>
</footer>




<!-- Footer Section -->
<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($header_name); ?> | <a href="/page.php?slug=privacy">Privacy Policy</a> | <a href="/page.php?slug=terms">Terms of Service</a></p>
</footer>




</body>
</html>
