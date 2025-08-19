<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Handle Form Submission
if (isset($_POST['add_customer'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $active = $_POST['active'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gstin = mysqli_real_escape_string($conn, $_POST['gstin']);
    $balance = $_POST['balance'];
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $date = $_POST['date']; // Capturing updated date & time

    // Check if customer already exists
    $check_query = mysqli_query($conn, "SELECT * FROM customer WHERE customer_email='$email' OR customer_mobileno='$mobile'");
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Customer already exists!";
    } elseif (!preg_match('/^[a-zA-Z][a-zA-Z ]*$/', $name)) {
        $error = "Invalid name! Only letters and spaces allowed.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match('/^[0-9A-Z]{15}$/', $gstin)) {
        $error = "Invalid GSTIN! It must be 15 alphanumeric characters.";
    } elseif (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $balance)) {
        $error = "Invalid balance! Enter a positive number.";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $error = "Invalid mobile number! Must be 10 digits.";
    } else {
        $query = "INSERT INTO customer (customer_name, customer_address, customer_status, customer_email, customer_gstin, customer_balance, customer_mobileno, customer_date) 
                  VALUES ('$name', '$address', '$active', '$email', '$gstin', '$balance', '$mobile', '$date')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Customer added successfully!'); window.location.href='customerlist.php';</script>";
        } else {
            $error = "Error adding customer: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure consistency with dashboard styles -->
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Customer</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Add Customer</h2>
        <?php if (isset($error)) { echo "<p class='invalid text-center' style='color:red;'>$error</p>"; } ?>
        <form method="POST" onsubmit="return validateCustomer()">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Customer Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <small id="name-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Address</label>
                    <input type="text" name="address" id="address" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Active</label>
                    <select name="active" id="active" class="form-control" required>
                        <option value="">Select</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <small id="email-message"></small>
                </div>
                <div class="col-md-4">
                    <label>GSTIN</label>
                    <input type="text" name="gstin" id="gstin" class="form-control" required>
                    <small id="gstin-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Remaining Balance</label>
                    <input type="number" step="0.01" name="balance" id="balance" class="form-control" required>
                    <small id="balance-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Mobile No</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" required>
                    <small id="mobile-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" name="add_customer" class="btn btn-primary">Add Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDateTime() {
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');
        let seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('currentDateTime').value = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    // Update time every second
    document.addEventListener("DOMContentLoaded", function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });

    function validateField(id, pattern, message) {
        let field = document.getElementById(id);
        let feedback = document.getElementById(id + "-message");
        if (!pattern.test(field.value.trim())) {
            feedback.innerText = message;
            feedback.style.color = "red";
            return false;
        } else {
            feedback.innerText = "Valid!";
            feedback.style.color = "green";
            return true;
        }
    }

    document.getElementById("name").addEventListener("input", function() {
        validateField("name", /^[a-zA-Z][a-zA-Z ]*$/, "Invalid name! Only letters and spaces allowed.");
    });
    document.getElementById("email").addEventListener("input", function() {
        validateField("email", /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, "Enter a valid email.");
    });
    document.getElementById("gstin").addEventListener("input", function() {
    this.value = this.value.toUpperCase(); // Convert GSTIN to uppercase automatically
    validateField("gstin", /^[0-9A-Z]{15}$/, "Invalid GSTIN! Must be 15 alphanumeric characters.");
});

    document.getElementById("balance").addEventListener("input", function() {
        validateField("balance", /^[0-9]+(\.[0-9]{1,2})?$/, "Invalid balance! Enter a positive number.");
    });
    document.getElementById("mobile").addEventListener("input", function() {
        validateField("mobile", /^[0-9]{10}$/, "Invalid mobile number! Must be 10 digits.");
    });
</script>

</body>
</html>
