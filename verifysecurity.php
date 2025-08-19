<?php
session_start();
include("database.php");

if (!isset($_SESSION['reset_user'])) {
    echo "<script>window.location.href='forgotpassword.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $answer = strtolower(trim($_POST['security_answer']));  // Convert to lowercase
    $stored_answer = strtolower(trim($_SESSION['security_answer']));

    if ($answer == $stored_answer) {
        echo "<script>window.location.href='resetpassword.php';</script>";
        exit();
    } else {
        echo "<script>alert('Incorrect Answer!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Security Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary">
        <div class="logo">
            <h1 class="text-light">Stock Management System</h1>
        </div>
       </nav>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Verify Security Question</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Security Question:</label>
                            <input type="text" class="form-control" value="<?php echo $_SESSION['security_question']; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Answer:</label>
                            <input type="text" name="security_answer" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verify</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="forgotpassword.php" class="text-decoration-none">Back</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
