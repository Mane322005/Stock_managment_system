<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch all suppliers for dropdown
$suppliers = mysqli_query($conn, "SELECT * FROM supplier");

// Initialize variables
$supplier_id = $name = $address = $active = $email = $gstin = $balance = $mobile = $brand = $date = "";

// Fetch supplier details when selected
if (isset($_POST['supplier_id'])) {
    $supplier_id = $_POST['supplier_id'];
    $query = "SELECT * FROM supplier WHERE supplier_id = '$supplier_id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $name = $row['supplier_name'];
        $address = $row['supplier_address'];
        $active = $row['supplier_status'];
        $email = $row['supplier_email'];
        $gstin = $row['supplier_gstin'];
        $balance = $row['supplier_balance'];
        $mobile = $row['supplier_mobileno'];
        $brand = $row['supplier_brand'];
        $date = $row['supplier_data'];
    }
}

// Handle Form Submission
if (isset($_POST['edit_supplier'])) {
    $supplier_id = $_POST['supplier_id'];
    $name = trim($_POST['name']);
    $address = $_POST['address'];
    $active = $_POST['active'];
    $email = $_POST['email'];
    $gstin = $_POST['gstin'];
    $balance = $_POST['balance'];
    $mobile = $_POST['mobile'];
    $brand = $_POST['brand'];
    $date = $_POST['date'];

    // Check if email or mobile already exists
    $check_query = mysqli_query($conn, "SELECT * FROM supplier WHERE (supplier_email='$email' OR supplier_mobileno='$mobile') AND supplier_id != '$supplier_id'");
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Email or Mobile number already exists!";
    } else {
        $query = "UPDATE supplier SET supplier_name='$name', supplier_address='$address', supplier_status='$active', supplier_email='$email', supplier_gstin='$gstin', supplier_balance='$balance', supplier_mobileno='$mobile', supplier_brand='$brand', supplier_data='$date' WHERE supplier_id='$supplier_id'";
        mysqli_query($conn, $query);
        echo "<script>alert('Supplier updated successfully!'); window.location.href='supplierlist.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure consistency with dashboard styles -->
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Supplier</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Edit Supplier</h2>
        <?php if (isset($error)) { echo "<p class='invalid text-center' style='color:red;'>$error</p>"; } ?>
        <form method="POST" action="" onsubmit="return validateSupplier()">
            <div class="row g-3">
                <!-- Dropdown to Select Supplier -->
                <div class="col-md-4">
                    <label>Select Supplier</label>
                    <select id="supplier_id" name="supplier_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">Select Supplier</option>
                        <?php while ($row = mysqli_fetch_assoc($suppliers)) { ?>
                            <option value="<?php echo $row['supplier_id']; ?>" <?php if ($supplier_id == $row['supplier_id']) echo "selected"; ?>>
                                <?php echo $row['supplier_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Supplier Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $name; ?>" required>
                    <small id="name-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="<?php echo $address; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
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
                    <label>Balance</label>
                    <input type="number" step="0.01" name="balance" id="balance" class="form-control" value="<?php echo $balance; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Mobile No</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" value="<?php echo $mobile; ?>" required>
                    <small id="mobile-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Brand</label>
                    <input type="text" name="brand" id="brand" class="form-control" value="<?php echo $brand; ?>" required>
                </div>
                <div class="col-md-4">
                    <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" name="edit_supplier" class="btn btn-primary">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDateTime() {
        let now = new Date();
        document.getElementById('currentDateTime').value = now.toISOString().slice(0, 19).replace('T', ' ');
    }

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
        this.value = this.value.toUpperCase();
        validateField("gstin", /^[0-9A-Z]{15}$/, "Invalid GSTIN!");
    });
    document.getElementById("mobile").addEventListener("input", function() {
        validateField("mobile", /^[0-9]{10}$/, "Invalid mobile number!");
    });
</script>
</body>
</html>
