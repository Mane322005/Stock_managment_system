<?php
session_start();
include('database.php');

// Show PHP errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set timezone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

$login_message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_input = trim($_POST['user_input']); // Username, Email, or Mobile Number
    $password = trim($_POST['password']);
    
    if (!empty($user_input) && !empty($password)) {
        // Check if the input matches username, email, or mobile number
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ? OR user_email = ? OR mobile_no = ?");
        $stmt->bind_param("sss", $user_input, $user_input, $user_input);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_username, $db_password);
            $stmt->fetch();

            // Convert entered password to MD5 and compare
            if ($password === $db_password) { // Assuming md5 is used for password check
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $db_username;
                
                // Log user login in system_logs
                $action = "User logged in";
                $log_date = date('Y-m-d H:i:s'); // Current timestamp in Asia/Kolkata timezone
                
                $log_stmt = $conn->prepare("INSERT INTO system_logs (user_id, action, log_date) VALUES (?, ?, ?)");
                $log_stmt->bind_param("iss", $id, $action, $log_date);
                $log_stmt->execute();
                $log_stmt->close();

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $login_message = "Invalid password.";
                $message_type = "danger";
            }
        } else {
            $login_message = "Account not found.";
            $message_type = "danger";
        }
        $stmt->close();
    } else {
        $login_message = "Please fill in all fields.";
        $message_type = "warning";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
   <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <h1 class="text-light">Stock Management System</h1>
        </div>
   </nav>

    <!-- Login Form -->
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Login</h2>

            <?php if (!empty($login_message)): ?>
                <div class="alert alert-<?php echo $message_type; ?> text-center">
                    <?php echo $login_message; ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="post" action="">
                <div class="mb-3">
                    <label class="form-label">Username / Email / Mobile Number</label>
                    <input type="text" class="form-control" name="user_input" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </div>
                <div class="text-center mt-3">
                    <a href="forgot_password.php">Forgot Password?</a> | <a href="registeration.php">Register Here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
