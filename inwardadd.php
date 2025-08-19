<?php 
include('subdash.php'); 
include 'database.php'; // Database connection file

// Handle Form Submission
if (isset($_POST['add_inward'])) {
    $product_id = $_POST['product_id'] ?? null;
    $supplier_id = $_POST['supplier_id'] ?? null;
    $inward_qty = $_POST['inward_qty'] ?? '';
    $received_by = $_POST['received_by'] ?? '';
    $delivered_by = $_POST['delivered_by'] ?? '';
    $total_price = $_POST['total_price'] ?? '';
    $inward_date = $_POST['date'] ?? '';

    if (empty($product_id) || empty($supplier_id)) {
        $error = "Product and Supplier selection are required!";
    } else {
        // Insert into inward table
        $query = "INSERT INTO inward 
        (product_id, inward_qty, supplier_id, received_by, delivered_by, total_price, inward_date) 
        VALUES ('$product_id', '$inward_qty', '$supplier_id', '$received_by', '$delivered_by', '$total_price', '$inward_date')";

        if (mysqli_query($conn, $query)) {
            // ✅ Check if stock table exists
            $stockCheck = mysqli_query($conn, "SHOW TABLES LIKE 'stock'");
            if ($stockCheck && mysqli_num_rows($stockCheck) > 0) {
                $checkStock = mysqli_query($conn, "SELECT * FROM stock WHERE product_id = '$product_id'");
                if (mysqli_num_rows($checkStock) > 0) {
                    // Update stock quantity
                    mysqli_query($conn, "UPDATE stock 
                        SET available_stock = available_stock + $inward_qty 
                        WHERE product_id = '$product_id'");
                } else {
                    // Insert new stock record
                    mysqli_query($conn, "INSERT INTO stock (product_id, available_stock) 
                        VALUES ('$product_id', '$inward_qty')");
                }
            }
            echo "<script>alert('Inward Stock added successfully!'); window.location.href='inwardlist.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch products and suppliers
$productResult = mysqli_query($conn, "SELECT product_id, product_name FROM product");
$supplierResult = mysqli_query($conn, "SELECT supplier_id, supplier_name FROM supplier");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inward Stock</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Inward Stock</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Add Inward Stock</h2>
        <?php if (isset($error)) { echo "<p class='text-danger text-center'>$error</p>"; } ?>
        <form method="POST" onsubmit="return validateForm()">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Product</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">Select Product</option>
                        <?php while ($product = mysqli_fetch_assoc($productResult)): ?>
                            <option value="<?= $product['product_id'] ?>"><?= $product['product_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-control" required>
                        <option value="">Select Supplier</option>
                        <?php while ($supplier = mysqli_fetch_assoc($supplierResult)): ?>
                            <option value="<?= $supplier['supplier_id'] ?>"><?= $supplier['supplier_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Inward Quantity</label>
                    <input type="number" name="inward_qty" id="inward_qty" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Received By</label>
                    <input type="text" name="received_by" id="received_by" class="form-control" required>
                    <small id="received_by-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Delivered By</label>
                    <input type="text" name="delivered_by" id="delivered_by" class="form-control" required>
                    <small id="delivered_by-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Total Price</label>
                    <input type="number" name="total_price" id="total_price" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Date</label>
                    <input type="text" id="currentDate" name="date" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" name="add_inward" class="btn btn-primary">Add Inward Stock</button>
            </div>
        </form>
    </div>
</div>

<script>
function updateDate() {
    let now = new Date();
    let year = now.getFullYear();
    let month = String(now.getMonth() + 1).padStart(2, '0');
    let day = String(now.getDate()).padStart(2, '0');  
    document.getElementById('currentDate').value = `${year}-${month}-${day}`;
}

document.addEventListener("DOMContentLoaded", function() {
    updateDate();

    document.getElementById("received_by").addEventListener("input", function() {
        validateField("received_by", /^[a-zA-Z\s]+$/, "Only letters and spaces allowed.");
    });

    document.getElementById("delivered_by").addEventListener("input", function() {
        validateField("delivered_by", /^[a-zA-Z\s]+$/, "Only letters and spaces allowed.");
    });
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

function validateForm() {
    let qty = document.getElementById('inward_qty').value;
    let total = document.getElementById('total_price').value;

    if (qty <= 0) {
        alert('Inward Quantity must be greater than 0.');
        return false;
    }
    if (total <= 0) {
        alert('Total Price must be greater than 0.');
        return false;
    }

    let isReceivedValid = validateField("received_by", /^[a-zA-Z\s]+$/, "Only letters and spaces allowed.");
    let isDeliveredValid = validateField("delivered_by", /^[a-zA-Z\s]+$/, "Only letters and spaces allowed.");

    return isReceivedValid && isDeliveredValid;
}
</script>
</body>
</html>
