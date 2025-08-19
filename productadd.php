<?php 
include('subdash.php'); 
include 'database.php'; // Database connection file  

// Handle Form Submission
if (isset($_POST['add_product'])) {
    $product_name = trim($_POST['product_name']);
    $product_status = $_POST['product_status'];
    $product_description = $_POST['product_description'];
    $product_unit = $_POST['product_unit']; // from dropdown
    $product_sprice = $_POST['product_sprice'];
    $product_gst = $_POST['product_gst'];
    $product_maxprice = $_POST['product_max_price'];
    $supplier_id = $_POST['product_supplier'];
    $category_id = $_POST['product_category'];
    $product_date = $_POST['date'];  // ✅ fixed: matches form input name

    // Check if product exists for the same supplier
    $check_query = mysqli_query($conn, "SELECT * FROM product WHERE product_name='$product_name' AND supplier_id='$supplier_id'");
    
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Product already exists for this supplier!";
    } elseif (!preg_match('/^[a-zA-Z][a-zA-Z0-9 ]*$/', $product_name)) {
        $error = "Invalid product name! The first letter must be a letter, and only letters, numbers, and spaces are allowed.";
    } else {
        // Insert product into the database
        $query = "INSERT INTO product 
            (product_name, product_status, product_description, product_unit, product_sprice, product_gst, product_maxprice, supplier_id, category_id, product_date) 
            VALUES 
            ('$product_name', '$product_status', '$product_description', '$product_unit', '$product_sprice', '$product_gst', '$product_maxprice', '$supplier_id', '$category_id', '$product_date')";
        
        mysqli_query($conn, $query);

        // ✅ Redirect to Product List page after success
        echo "<script>alert('Product added successfully!'); window.location.href='productlist.php';</script>";
        exit;
    }
}

// Fetch suppliers
$supplier_query = "SELECT * FROM supplier";
$supplier_result = mysqli_query($conn, $supplier_query);

// Fetch categories
$category_query = "SELECT * FROM category";
$category_result = mysqli_query($conn, $category_query);

// List of product units
$product_units = ["Kilograms", "Grams", "Pieces", "Liters", "Boxes"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Product</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Add Product</h2>
        <?php if (isset($error)) { echo "<p class='invalid text-center' style='color:red;'>$error</p>"; } ?>
        <form method="POST" onsubmit="return validateProduct()">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Product Name</label>
                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                    <small id="product_name-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="product_status" id="product_status" class="form-control" required>
                        <option value="">Select</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Available">Available</option>
                        <option value="Out of Stock">Out of Stock</option>
                    </select>
                    <small id="product_status-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Description</label>
                    <textarea name="product_description" id="product_description" class="form-control" required></textarea>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label>Product Unit</label>
                    <select name="product_unit" class="form-control" required>
                        <option value="">Select Unit</option>
                        <?php foreach ($product_units as $unit) { ?>
                            <option value="<?php echo $unit; ?>"><?php echo $unit; ?></option>
                        <?php } ?>
                    </select>
                    <small id="product_unit-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Product Price</label>
                    <input type="number" name="product_sprice" id="product_sprice" class="form-control" required min="0" step="0.01">
                    <small id="product_price-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Product Maximum Price</label>
                    <input type="number" name="product_max_price" id="product_max_price" class="form-control" required min="0" step="0.01">
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label>Product GST (%)</label>
                    <input type="number" name="product_gst" id="product_gst" class="form-control" required min="0" max="100" step="0.1">
                </div>
                <div class="col-md-4">
                    <label>Select Supplier</label>
                    <select name="product_supplier" class="form-control" required>
                        <?php while ($supplier = mysqli_fetch_assoc($supplier_result)) { ?>
                            <option value="<?php echo $supplier['supplier_id']; ?>">
                                <?php echo $supplier['supplier_name']; ?> (<?php echo $supplier['supplier_mobileno']; ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Select Category</label>
                    <select name="product_category" class="form-control" required>
                        <?php while ($category = mysqli_fetch_assoc($category_result)) { ?>
                            <option value="<?php echo $category['category_id']; ?>">
                                <?php echo $category['category_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
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
    // Product name validation on input
    document.getElementById("product_name").addEventListener("input", function() {
        let productName = this.value.trim();
        let message = document.getElementById("product_name-message");
        let regex = /^[a-zA-Z][a-zA-Z0-9 ]*$/;

        if (!regex.test(productName)) {
            message.innerText = "Invalid product name!";
            message.style.color = "red";
        } else {
            message.innerText = "Valid product name!";
            message.style.color = "green";
        }
    });

    // Product unit validation
    document.getElementById("product_unit").addEventListener("input", function() {
        let productUnit = this.value.trim();
        let message = document.getElementById("product_unit-message");

        if (productUnit.length === 0) {
            message.innerText = "Product unit is required!";
            message.style.color = "red";
        } else {
            message.innerText = "";
        }
    });
</script>
</body>
</html>
