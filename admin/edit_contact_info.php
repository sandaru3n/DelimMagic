<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch contact information
$stmt = $pdo->query("SELECT * FROM contact_info LIMIT 1");
$contact_info = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $faboutus = $_POST['faboutus'];
    

    // Update contact information in the database
    if ($contact_info) {
        $stmt = $pdo->prepare("UPDATE contact_info SET email = ?, phone = ?, address = ?,faboutus = ? WHERE id = ?");
        $stmt->execute([$email, $phone, $address,$faboutus, $contact_info['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO contact_info (email, phone, address,faboutus) VALUES (?, ?, ?,?)");
        $stmt->execute([$email, $phone, $address,$faboutus]);
    }

    header("Location: edit_contact_info.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Contact Information</title>
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
        }
        .container {
            margin-top: 30px;
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
            <div class="container">
                <h1>Edit Contact Information</h1>
                <form action="edit_contact_info.php" method="post">
                    <div class="form-group">
                        <label for="email">Contact Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($contact_info['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Contact Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($contact_info['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Contact Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control"><?php echo htmlspecialchars($contact_info['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Footer about Us</label>
                        <textarea name="faboutus" id="faboutus" rows="3" class="form-control"><?php echo htmlspecialchars($contact_info['faboutus'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
