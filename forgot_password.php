<?php
session_start();
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username='$username' OR mobile_no='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['reset_user'] = $user['username'];  // Store username for next step
        $_SESSION['security_question'] = $user['security_question'];
        $_SESSION['security_answer'] = $user['security_answer'];
        
        echo "<script>window.location.href='verifysecurity.php';</script>";
        exit();
    } else {
        echo "<script>alert('User not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    <link rel="icon" type="image/png" href="images/favicon3.png">

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
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Enter Username or Mobile:</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Next</button>
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
