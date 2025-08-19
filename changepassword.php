<?php
ob_start();  // Start output buffering
include('subdash.php');
include('database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $current_username = $user['username'];
} else {
    die("User not found!");
}

// Handle form submission
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input fields
    if (empty($new_username)) {
        $errors[] = "Username is required.";
    }

    if (empty($new_password) || strlen($new_password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = $new_password;
        $sql = "UPDATE users SET username = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_username, $hashed_password, $user_id);

        if ($stmt->execute()) {
            ob_end_clean(); // Clear any previous output before redirection
            header("Location: profile.php");
            exit();
        } else {
            $errors[] = "Error updating profile!";
        }
    }
}
ob_end_flush(); // Send the output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    <style>
        .settings-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background: #fff;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .icon {
            color: #007bff;
            font-size: 18px;
            margin-right: 10px;
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 40%;
            transform: translateY(-50%);
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container mt-4" style="max-width: 600px;">
    <div class="settings-card">
        <h2 class="text-center text-primary"><i class="bi bi-gear"></i> Settings</h2>
        <hr>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error) { echo "<p>$error</p>"; } ?>
            </div>
        <?php endif; ?>

        <form action="changepassword.php" method="POST">
            <div class="form-group">
                <label for="username"><i class="bi bi-person icon"></i> New Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($current_username); ?>" required>
            </div>

            <div class="form-group position-relative">
                <label for="password"><i class="bi bi-lock icon"></i> New Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password')"></i>
            </div>

            <div class="form-group position-relative">
                <label for="confirm_password"><i class="bi bi-shield-lock icon"></i> Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('confirm_password')"></i>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Update</button>
        </form>
    </div>
</div>

<!-- Password Toggle Script -->
<script>
    function togglePassword(id) {
        let input = document.getElementById(id);
        let icon = event.target;
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
