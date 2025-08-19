
<?php
include('database.php');

// Show PHP errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$registration_message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Collect form data
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $mobile_number = trim($_POST['mobile_number']);
    $company_name = trim($_POST['company_name']);
    $address = trim($_POST['address']);
    $registration_date = $_POST['registration_date'];
    $security_question = trim($_POST['security_question']);
    $security_answer = trim($_POST['security_answer']);

    // Validation Errors Array
    $errors = [];

    // Check if username, email, or mobile number already exists
    $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR user_email = ? OR mobile_no = ?");
    $check_stmt->bind_param("sss", $username, $email, $mobile_number);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $errors[] = "Username, Email, or Mobile Number already exists.";
    }
    $check_stmt->close();

    // Validate Full Name (only letters and spaces, 3-50 chars)
    if (!preg_match("/^[a-zA-Z ]{3,50}$/", $full_name)) {
        $errors[] = "Invalid full name. Only letters and spaces allowed (3-50 characters).";
    }

    // Validate Username (alphanumeric, underscores allowed, 4-20 chars)
    if (!preg_match("/^[a-zA-Z][a-zA-Z0-9!@#$%^&*()_+={}\[\]:;<>,.?~\\/-]{3,19}$/", $username)) {
        $errors[] = "Invalid username. It must start with a letter, be 4-20 characters long, and can include letters, numbers, and special characters.";
    }
    
    

    // Validate Email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate Password (min 6 characters)
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Validate Mobile Number (10 digits)
    if (!preg_match("/^[0-9]{10}$/", $mobile_number)) {
        $errors[] = "Invalid mobile number. Must be a 10-digit number.";
    }

    // Validate Security Question (not empty)
    if (empty($security_question)) {
        $errors[] = "Security question cannot be empty.";
    }

    // Validate Security Answer (not empty)
    if (empty($security_answer)) {
        $errors[] = "Security answer cannot be empty.";
    }

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
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES["profile_pic"]["size"] > 5000000) { // Limit: 500KB
            $errors[] = "File size too large. Max: 500KB.";
        } elseif (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profile_pic_target)) {
            $profile_pic_path = $profile_pic_target; // Set uploaded file path
        } else {
            $errors[] = "Failed to upload profile picture.";
        }
    } elseif (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "An error occurred during the file upload.";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        $hashed_password = $password; // Keeping as plain text as requested (not recommended!)

        $stmt = $conn->prepare("INSERT INTO users (full_name, username, user_email, password, mobile_no, company_name, adress, reg_date, profile_pic, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $full_name, $username, $email, $hashed_password, $mobile_number, $company_name, $address, $registration_date, $profile_pic_path, $security_question, $security_answer);

        if ($stmt->execute()) {
            echo "<pre>Success: User registered!</pre>";
            header("Location: login.php");
            exit();
        } else {
            echo "<pre>Error: " . $stmt->error . "</pre>";
        }
        $stmt->close();
    } else {
       // echo "<pre>Errors: " . implode(" || ", $errors) . "</pre>";
    }
}

$conn->close();
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 255, 0.3);
        }
        .btn-center {
            display: flex;
            justify-content: center;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
        .success-message {
            color: green;
            font-size: 14px;
        }
        .password-feedback {
            font-size: 14px;
        }
        .input-focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .alert-error {
        color: red;
        border: 1px solid red;
        background-color: #f8d7da;
        padding: 15px;
        margin-bottom: 20px;
        font-size: 14px;
        border-radius: 5px;
    }
    .alert-success {
        color: green;
        border: 1px solid green;
        background-color: #d4edda;
        padding: 15px;
        margin-bottom: 20px;
        font-size: 14px;
        border-radius: 5px;
    }
        
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="logo">
            <h1 class="text-light">Stock Management System</h1>
        </div>
       </nav>
    

<div class="container mt-5">
    <div class="col-md-8 mx-auto form-container">
        <h2 class="mb-4 text-center">Registration Form</h2>
        <?php if (!empty($errors)): ?>
    <div class="alert alert-error text-center">
        <?php echo implode("<br>", $errors); ?>
    </div>
<?php endif; ?>




            <form action="registeration.php" method="POST" enctype="multipart/form-data">

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="full_name" >
                        <small class="error-message" id="fullNameError"></small>
                        <small class="success-message" id="fullNameSuccess"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                        <small class="error-message" id="usernameError"></small>
                        <small class="success-message" id="usernameSuccess"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                        <small class="error-message" id="emailError"></small>
                        <small class="success-message" id="emailSuccess"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile No</label>
                        <input type="tel" class="form-control" id="mobile" name="mobile_number">
                        <small class="error-message" id="mobileError"></small>
                        <small class="success-message" id="mobileSuccess"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="error-message" id="passwordError"></small>
                        <small class="password-feedback" id="passwordFeedback"></small>
                    </div>
                    <div class="mb-3">
    <label class="form-label">Security Question</label>
    <select class="form-control" id="securityQuestion" name="security_question" required>
        <option value="">Select a security question</option>
        <option value="imaginary_friend">What was the name of your first childhood imaginary friend?</option>
        <option value="dream_job">What was the first job you dreamed of having as a child?</option>
        <option value="memorable_teacher">What is the name of a memorable teacher from your school?</option>
        <option value="first_book">What is the title of the first book you ever read completely?</option>
        <option value="first_school_trip">What is the name of the first school trip you ever went on?</option>
        <option value="desired_pet">What was the name of the first pet you wanted but never had?</option>
        <option value="best_friend_sibling">What is the name of your childhood best friend's sibling?</option>
        <option value="first_concert">What was the first concert or event you attended?</option>
        <option value="first_travel">What is the first place you traveled to outside your hometown?</option>
        <option value="first_mobile_brand">What was the brand of your first mobile phone?</option>
    </select>
</div>
                        
                    
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="companyName" name="company_name">
                        <small class="error-message" id="companyNameError"></small>
                        <small class="success-message" id="companyNameSuccess"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address">
                        <small class="error-message" id="addressError"></small>
                        <small class="success-message" id="addressSuccess"></small>
                    </div>
                    <div class="mb-3">
                     <label class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profilePic" name="profile_pic">
                    <small class="error-message" id="profilePicError"></small>
                     <small class="success-message" id="profilePicSuccess"></small>
                        </div>

                    <div class="mb-3">
                        <label class="form-label">Registration Date</label>
                        <input type="date" class="form-control" id="registrationDate" name="registration_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                        <small class="error-message" id="confirmPasswordError"></small>
                        <small class="success-message" id="confirmPasswordSuccess"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Security Answer</label>
                        <input type="text" class="form-control" id="securityAnswer" name="security_answer">
                        <small class="error-message" id="securityAnswerError"></small>
                        <small class="success-message" id="securityAnswerSuccess"></small>
                    </div>
                </div>
            </div>

            <div class="btn-center">
                <button type="submit" name="register" class="btn btn-primary mt-3" id="registerBtn">Register</button>
            </div>
            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>

        </form>
    </div>
</div>

<script>
    // Auto-fill the registration date with the current date
    document.getElementById('registrationDate').valueAsDate = new Date();

    // Password strength check
    document.getElementById("password").addEventListener("input", function() {
        const password = this.value;
        const feedback = document.getElementById("passwordFeedback");

        // Password validation criteria
        const minLength = 6; // Minimum password length
        const hasUpperCase = /[A-Z]/.test(password); // Must contain at least one uppercase letter
        const hasLowerCase = /[a-z]/.test(password); // Must contain at least one lowercase letter
        const hasNumber = /\d/.test(password); // Must contain at least one number
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password); // Must contain at least one special character

        // Check password strength
        if (password.length >= minLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar) {
            feedback.textContent = "Password is valid.";
            feedback.style.color = "green";
        } else {
            feedback.textContent = "Password is invalid. It must be at least 6 characters long, contain uppercase, lowercase, a number, and a special character.";
            feedback.style.color = "red";
        }
    });

    // Validate fields and show success/error messages in real-time
    function validateField(fieldId, errorId, successId, validationFunc, errorMessage, successMessage) {
        const field = document.getElementById(fieldId);
        const error = document.getElementById(errorId);
        const success = document.getElementById(successId);

        if (!validationFunc(field.value.trim())) {
            error.innerText = errorMessage;
            success.innerText = "";
        } else {
            error.innerText = "";
            success.innerText = successMessage;
        }
    }

    // Real-time validation
    document.getElementById('fullName').addEventListener('input', function() {
        validateField("fullName", "fullNameError", "fullNameSuccess", value => /^[A-Za-z\s]+$/.test(value), "Only letters are allowed.", "Valid Full Name");
    });

    document.getElementById('username').addEventListener('input', function() {
        validateField("username", "usernameError", "usernameSuccess", value => /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>]).+$/.test(value), "Username must contain at least one letter, number, and special character. No spaces allowed. at least 4 char long", "Valid Username");
    });

    document.getElementById('email').addEventListener('input', function() {
        validateField("email", "emailError", "emailSuccess", value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value), "Enter a valid email.", "Valid Email");
    });

    document.getElementById('mobile').addEventListener('input', function() {
        validateField("mobile", "mobileError", "mobileSuccess", value => /^\d{10}$/.test(value), "Mobile number must be 10 digits.", "Valid Mobile Number");
    });

    document.getElementById('password').addEventListener('input', function() {
        validateField("password", "passwordError", "passwordSuccess", value => value.length >= 6, "Password must be at least 6 characters.", "Valid Password");
    });

    document.getElementById('confirmPassword').addEventListener('input', function() {
        validateField("confirmPassword", "confirmPasswordError", "confirmPasswordSuccess", value => value === document.getElementById("password").value, "Passwords do not match.", "Password Confirmed");
    });

    document.getElementById('companyName').addEventListener('input', function() {
        validateField("companyName", "companyNameError", "companyNameSuccess", value => /^[A-Za-z\s]+$/.test(value), "Only letters are allowed.", "Valid Company Name");
    });

    document.getElementById('address').addEventListener('input', function() {
        validateField("address", "addressError", "addressSuccess", value => /[A-Za-z]/.test(value), "Address must contain at least one letter. Numbers and spaces are allowed.", "Valid Address");
    });

    document.getElementById('profilePic').addEventListener('change', function() {
        validateField("profilePic", "profilePicError", "profilePicSuccess", value => document.getElementById("profilePic").files.length > 0, "Profile Picture is required.", "Profile Picture Selected");
    });
    document.getElementById('securityAnswer').addEventListener('input', function() {
        validateField(
            "securityAnswer",
            "securityAnswerError",
            "securityAnswerSuccess",
            value => /^[A-Za-z\s]{3,}$/.test(value), 
            "Security answer must contain at least 3 letters.",
            "Valid Security Answer"
        );
    });
    

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
