<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch categories and suppliers for dropdown
$categories = mysqli_query($conn, "SELECT * FROM category");
$suppliers = mysqli_query($conn, "SELECT * FROM supplier");

// Initialize product variable to null
$product = null;

// Handle Product Selection
if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_id = $_GET['product_id'];  // Get the product_id from the URL (GET method)
    $product_query = mysqli_query($conn, "SELECT * FROM product WHERE product_id = '$product_id'");
    if (mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);  // Get product details
    }
}

// Handle Form Submission for Product Edit
if (isset($_POST['edit_product']) && isset($_POST['product_id']) && !empty($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $product_name = trim($_POST['product_name']);
    $product_status = $_POST['product_status'];
    $product_description = $_POST['product_description'];
    $product_unit = $_POST['product_unit'];
    $product_sprice = $_POST['product_sprice'];
    $product_gst = $_POST['product_gst'];
    $product_maxprice = $_POST['product_maxprice'];
    $product_date = $_POST['date'];

    // Check if product name already exists
    $check_query = mysqli_query($conn, "SELECT * FROM product WHERE product_name='$product_name' AND product_id != '$product_id'");
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Product name already exists!";
    } else {
        // Update the product details
        $query = "UPDATE product SET category_id='$category_id', supplier_id='$supplier_id', product_name='$product_name', 
                  product_status='$product_status', product_description='$product_description', product_unit='$product_unit', 
                  product_sprice='$product_sprice', product_gst='$product_gst', product_maxprice='$product_maxprice', 
                  product_date='$product_date' WHERE product_id='$product_id'";

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>alert('Product updated successfully!'); window.location.href='productlist.php';</script>";
        } else {
            $error = "Failed to update product. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure consistency with dashboard styles -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Edit Product</h2>
        <?php if (isset($error)) { echo "<p class='invalid text-center' style='color:red;'>$error</p>"; } ?>

        <!-- Product Selection Dropdown -->
        <form method="GET" action="">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Select Product</label>
                    <select name="product_id" id="product_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">Select Product</option>
                        <?php
                        $products = mysqli_query($conn, "SELECT * FROM product");
                        while ($row = mysqli_fetch_assoc($products)) {
                            $selected = isset($product) && $product['product_id'] == $row['product_id'] ? 'selected' : '';
                            echo "<option value='{$row['product_id']}' $selected>{$row['product_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>

        <?php if ($product) { ?>
            <!-- Product Edit Form -->
            <form method="POST" action="">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />

                <div class="row g-3">
                    <!-- Dropdown to Select Category -->
                    <div class="col-md-4">
                        <label>Select Category</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                                <option value="<?php echo $row['category_id']; ?>" <?php echo ($row['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $row['category_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Dropdown to Select Supplier -->
                    <div class="col-md-4">
                        <label>Select Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="form-control" required>
                            <option value="">Select Supplier</option>
                            <?php while ($row = mysqli_fetch_assoc($suppliers)) { ?>
                                <option value="<?php echo $row['supplier_id']; ?>" <?php echo ($row['supplier_id'] == $product['supplier_id']) ? 'selected' : ''; ?>>
                                    <?php echo $row['supplier_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Product Name -->
                    <div class="col-md-4">
                    <label>Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo $product['product_name']; ?>" required>
                <small id="product_name_message" style="color: red;"></small>
                 </div>
                    <!-- Product Status -->
                    <div class="col-md-4">
                        <label>Status</label>
                        <select name="product_status" id="product_status" class="form-control" required>
                            <option value="Available" <?php echo ($product['product_status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Out of Stock" <?php echo ($product['product_status'] == 'Out of Stock') ? 'selected' : ''; ?>>Out of Stock</option>
                        </select>
                    </div>

                    <!-- Product Description -->
                    <div class="col-md-4">
                        <label>Description</label>
                        <textarea name="product_description" id="product_description" class="form-control" required><?php echo $product['product_description']; ?></textarea>
                    </div>

                    <!-- Product Unit -->
                    <div class="col-md-4">
                        <label>Unit</label>
                        <input type="text" name="product_unit" id="product_unit" class="form-control" value="<?php echo $product['product_unit']; ?>" required>
                    </div>

                    <!-- Selling Price -->
                    <div class="col-md-4">
                        <label>Selling Price</label>
                        <input type="number" name="product_sprice" id="product_sprice" class="form-control" value="<?php echo $product['product_sprice']; ?>" required>
                    </div>

                    <!-- GST -->
                    <div class="col-md-4">
                        <label>GST</label>
                        <input type="number" name="product_gst" id="product_gst" class="form-control" value="<?php echo $product['product_gst']; ?>" required>
                    </div>

                    <!-- Max Price -->
                    <div class="col-md-4">
                        <label>Max Price</label>
                        <input type="number" name="product_maxprice" id="product_maxprice" class="form-control" value="<?php echo $product['product_maxprice']; ?>" required>
                    </div>

                    <!-- Date -->
                    <div class="col-md-4">
                    <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" name="edit_product" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        <?php } else { ?>
            <p>Please select a product to edit.</p>
        <?php } ?>
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
        let message = document.getElementById("product_name_message");
        let regex = /^[a-zA-Z][a-zA-Z0-9 ]*$/;

        if (!regex.test(productName)) {
            message.innerText = "Invalid product name! Only letters and numbers allowed, must start with a letter.";
            message.style.color = "red";
        } else {
            message.innerText = "Valid product name!";
            message.style.color = "green";
        }
    });
</script>
</body>
</html>

</script>
</body>
</html>
