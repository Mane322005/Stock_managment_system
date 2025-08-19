<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch all customers for dropdown
$customers = mysqli_query($conn, "SELECT * FROM customer");

// Initialize variables
$customer_id = $name = $address = $active = $email = $gstin = $balance = $mobile = $date = "";

// Fetch customer details when selected
if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $query = "SELECT * FROM customer WHERE id = '$customer_id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $name = $row['customer_name'];
        $address = $row['customer_address'];
        $active = $row['customer_status'];
        $email = $row['customer_email'];
        $gstin = $row['customer_gstin'];
        $balance = $row['customer_balance'];
        $mobile = $row['customer_mobileno'];
        $date = $row['customer_date'];
    }
}

// Handle Form Submission
if (isset($_POST['edit_customer'])) {
    $customer_id = $_POST['customer_id'];
    $name = trim($_POST['name']);
    $address = $_POST['address'];
    $active = $_POST['active'];
    $email = $_POST['email'];
    $gstin = $_POST['gstin'];
    $balance = $_POST['balance'];
    $mobile = $_POST['mobile'];
    $date = $_POST['date'];

    // Check if email or mobile already exists
    $check_query = mysqli_query($conn, "SELECT * FROM customer WHERE (customer_email='$email' OR customer_mobileno='$mobile') AND id != '$customer_id'");
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Email or Mobile number already exists!";
    } else {
        $query = "UPDATE customer SET customer_name='$name', customer_address='$address', customer_status='$active', customer_email='$email', customer_gstin='$gstin', customer_balance='$balance', customer_mobileno='$mobile', customer_date='$date' WHERE id='$customer_id'";
        mysqli_query($conn, $query);
        echo "<script>alert('Customer updated successfully!'); window.location.href='customerlist.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure consistency with dashboard styles -->
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Customer</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Edit Customer</h2>
        <?php if (isset($error)) { echo "<p class='invalid text-center' style='color:red;'>$error</p>"; } ?>
        <form method="POST" action="" onsubmit="return validateCustomer()">
            <div class="row g-3">
                <!-- Dropdown to Select Customer -->
                <div class="col-md-4">
                    <label>Select Customer</label>
                    <select id="customer_id" name="customer_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">Select Customer</option>
                        <?php while ($row = mysqli_fetch_assoc($customers)) { ?>
                            <option value="<?php echo $row['id']; ?>" <?php if ($customer_id == $row['id']) echo "selected"; ?>>
                                <?php echo $row['customer_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Customer Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>" required>
                    <small id="name-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="<?php echo $address; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Active</label>
                    <select name="active" id="active" class="form-control" required>
                        <option value="Active" <?php if ($active == "Active") echo "selected"; ?>>Active</option>
                        <option value="Inactive" <?php if ($active == "Inactive") echo "selected"; ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>" required>
                    <small id="email-message"></small>
                </div>
                <div class="col-md-4">
                    <label>GSTIN</label>
                    <input type="text" name="gstin" id="gstin" class="form-control" value="<?php echo $gstin; ?>" required>
                    <small id="gstin-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Remaining Balance</label>
                    <input type="number" step="0.01" name="balance" id="balance" class="form-control" value="<?php echo $balance; ?>" required>
                    <small id="balance-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Mobile No</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" value="<?php echo $mobile; ?>" required>
                    <small id="mobile-message"></small>
                </div>
                <div class="col-md-4">
                <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" name="edit_customer" class="btn btn-primary">Update Customer</button>
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
        if (!pattern.test(field.value)) {
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
    this.value = this.value.toUpperCase(); // Convert to uppercase
    validateField("gstin", /^[0-9A-Z]{15}$/, "Invalid GSTIN! Must be 15 alphanumeric characters.");
});

    document.getElementById("mobile").addEventListener("input", function() {
        validateField("mobile", /^[0-9]{10}$/, "Invalid mobile number! Must be 10 digits.");
    });
</script>
</body>
</html>
