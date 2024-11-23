<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verification_code = $_POST['verification_code'];
    $new_password = $_POST['new_password'];

    // Fetch the stored verification code
    $stmt = $pdo->query("SELECT verification_code FROM settings LIMIT 1");
    $settings = $stmt->fetch();

    if ($settings && $settings['verification_code'] === $verification_code) {
        // Hash the new password and update it in the admins table
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Assuming there is only one admin account with ID 1
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = 1");
        $stmt->execute([$hashed_password]);

        echo "Password reset successfully! You can now <a href='login.php'>login</a> with your new password.";
        exit();
    } else {
        $error = "Invalid verification code!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Reset Password</h2>
                <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="verification_code">Verification Code:</label>
                        <input type="text" name="verification_code" class="form-control" id="verification_code" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" name="new_password" class="form-control" id="new_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
