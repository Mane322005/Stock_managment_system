<?php
include('subdash.php');
include('database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $user_email = trim($_POST['user_email']);
    $password = trim($_POST['password']);
    $mobile_no = trim($_POST['mobile_no']);
    $company_name = trim($_POST['company_name']);
    $address = trim($_POST['address']);
    $security_question = trim($_POST['security_question']);
    $security_answer = trim($_POST['security_answer']);

    // Create uploads directory if it doesn't exist
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle profile picture upload
    $profile_pic_path = "uploads/default.jpg"; // Default profile picture

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $profile_pic_name = time() . "_" . basename($_FILES['profile_pic']['name']); // Unique file name
        $profile_pic_target = $upload_dir . $profile_pic_name;

        // Allow only certain file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $imageFileType = strtolower(pathinfo($profile_pic_target, PATHINFO_EXTENSION));

        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        } elseif ($_FILES["profile_pic"]["size"] > 5000000) { // Limit: 500KB
            echo "<script>alert('File size too large. Max: 500KB.');</script>";
        } elseif (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profile_pic_target)) {
            $profile_pic_path = $profile_pic_target; // Set uploaded file path
        } else {
            echo "<script>alert('Failed to upload profile picture.');</script>";
        }
    }

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE (username=? OR user_email=? OR mobile_no=?) AND user_id != ?");
    $stmt->bind_param("sssi", $username, $user_email, $mobile_no, $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Username, Email, or Mobile No already exists!');</script>";
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name=?, username=?, user_email=?, password=?, mobile_no=?, company_name=?, adress=?, security_question=?, security_answer=?, profile_pic=? WHERE user_id=?");
        $stmt->bind_param("ssssssssssi", $full_name, $username, $user_email, $password, $mobile_no, $company_name, $address, $security_question, $security_answer, $profile_pic_path, $user_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Error updating profile!');</script>";
        }
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT full_name, username, user_email, mobile_no, company_name, adress, security_question, security_answer, profile_pic FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $username, $user_email, $mobile_no, $company_name, $address, $security_question, $security_answer, $profile_pic);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center text-primary">Edit Profile</h2>
    <div class="card p-4 shadow-sm">
        <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="user_email" value="<?php echo htmlspecialchars($user_email); ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile No</label>
                        <input type="text" name="mobile_no" value="<?php echo htmlspecialchars($mobile_no); ?>" class="form-control" required>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control"><?php echo htmlspecialchars($address); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Security Question</label>
                        <select name="security_question" class="form-control" required>
                            <option value="What is your favorite color?" <?php echo ($security_question == "What is your favorite color?") ? "selected" : ""; ?>>What is your favorite color?</option>
                            <option value="What is your pet’s name?" <?php echo ($security_question == "What is your pet’s name?") ? "selected" : ""; ?>>What is your pet’s name?</option>
                            <option value="What is your mother’s maiden name?" <?php echo ($security_question == "What is your mother’s maiden name?") ? "selected" : ""; ?>>What is your mother’s maiden name?</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Security Answer</label>
                        <input type="text" name="security_answer" value="<?php echo htmlspecialchars($security_answer); ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="profile_pic" class="form-control">
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                <a href="profile.php" class="btn btn-danger"><i class="bi bi-x"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function validateForm() {
    let securityAnswer = document.querySelector('[name="security_answer"]').value;
    if (securityAnswer.trim() === "") {
        alert("Security Answer cannot be empty!");
        return false;
    }
    return true;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
