<?php 
include('subdash.php'); 
include 'database.php'; // Database connection file

// Handle Form Submission
if (isset($_POST['add_outward'])) {
    $product_id = $_POST['product_id'] ?? null;
    $customer_id = $_POST['customer_id'] ?? null;
    $outward_qty = $_POST['outward_qty'] ?? '';
    $delivered_by = $_POST['delivered_by'] ?? '';
    $total_price = $_POST['total_price'] ?? '';
    $outward_date = $_POST['date'] ?? '';

    if (empty($product_id) || empty($customer_id)) {
        $error = "Product and Customer selection are required!";
    } else {
        // Check current stock
        $stockQuery = "SELECT available_stock FROM stock WHERE product_id = '$product_id'";
        $stockResult = mysqli_query($conn, $stockQuery);
        $stockData = mysqli_fetch_assoc($stockResult);
        $currentStock = $stockData['available_stock'] ?? 0;

        if ($currentStock < $outward_qty) {
            echo "<script>alert('Error: Not enough stock available!'); window.location.href='outwardlist.php';</script>";
        } else {
            // Insert into outward table
            $query = "INSERT INTO outward (product_id, outward_qty, customer_id, delivered_by, total_price, outward_date) 
                      VALUES ('$product_id', '$outward_qty', '$customer_id', '$delivered_by', '$total_price', '$outward_date')";

            if (mysqli_query($conn, $query)) {
                // Update stock in product table
                $newStock = $currentStock - $outward_qty;
                $updateStockQuery = "UPDATE stock SET available_stock = '$newStock' WHERE product_id = '$product_id'";
                mysqli_query($conn, $updateStockQuery);

                echo "<script>alert('Outward Stock added successfully!'); window.location.href='outwardlist.php';</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch products and customers
$productResult = mysqli_query($conn, "SELECT product_id, product_name FROM product");
$customerResult = mysqli_query($conn, "SELECT id, customer_name FROM customer");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Outward Stock</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Outward Stock</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Add Outward Stock</h2>
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
                    <label>Customer</label>
                    <select name="customer_id" class="form-control" required>
                        <option value="">Select Customer</option>
                        <?php while ($customer = mysqli_fetch_assoc($customerResult)): ?>
                            <option value="<?= $customer['id'] ?>"><?= $customer['customer_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Outward Quantity</label>
                    <input type="number" name="outward_qty" id="outward_qty" class="form-control" required>
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
                    <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" name="add_outward" class="btn btn-primary">Add Outward Stock</button>
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
    document.getElementById("delivered_by").addEventListener("input", function() {
        validateField("delivered_by", /^[a-zA-Z\s]+$/, "Only letters and spaces allowed.");
    });
    function validateForm() {
        let qty = document.getElementById('outward_qty').value;
        let total = document.getElementById('total_price').value;
        if (qty <= 0 || total <= 0) {
            alert('Quantity and Total Price must be greater than 0.');
            return false;
        }
        return validateField("delivered_by", /^[a-zA-Z\s]+$/, "Only letters and spaces allowed.");
    }
</script>
</body>
</html>
