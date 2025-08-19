<?php
session_start();
include("database.php");

if (!isset($_SESSION['reset_user'])) {
    echo "<script>window.location.href='forgotpassword.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password']; // Plain text password
    $username = $_SESSION['reset_user'];

    // Update password in plain text (⚠️ Not secure! Only for testing purposes)
    $sql = "UPDATE users SET password='$new_password' WHERE username='$username'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Password updated successfully!'); window.location.href='login.php';</script>";
        session_destroy();
    } else {
        echo "<script>alert('Error updating password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary">
        <div class="logo">
            <h1 class="text-light">Stock Management System</h1>
        </div>
       </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Reset Your Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">New Password:</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
