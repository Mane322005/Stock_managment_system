<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch all outward entries for dropdown selection
$outwardResult = mysqli_query($conn, "SELECT * FROM outward");

// Initialize form fields
$outward_id = $product_id = $customer_id = $outward_qty = $delivered_by = $total_price = $outward_date = "";

// Fetch outward data when an ID is selected
if (isset($_POST['outward_id'])) {
    $outward_id = $_POST['outward_id'];
    $query = "SELECT * FROM outward WHERE outward_id = '$outward_id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $customer_id = $row['customer_id'];
        $outward_qty = $row['outward_qty'];
        $delivered_by = $row['delivered_by'];
        $total_price = $row['total_price'];
        $outward_date = $row['outward_date'];
    }
}

// Fetch product name
$product_name = '';
if (!empty($product_id)) {
    $productQuery = mysqli_query($conn, "SELECT product_name FROM product WHERE product_id = '$product_id'");
    if ($productRow = mysqli_fetch_assoc($productQuery)) {
        $product_name = $productRow['product_name'];
    }
}

// Fetch customer name
$customer_name = '';
if (!empty($customer_id)) {
    $customerQuery = mysqli_query($conn, "SELECT customer_name FROM customer WHERE customer_id = '$customer_id'");
    if ($customerRow = mysqli_fetch_assoc($customerQuery)) {
        $customer_name = $customerRow['customer_name'];
    }
}

// Handle Form Submission
if (isset($_POST['update_outward'])) {
    $new_outward_qty = $_POST['outward_qty'];
    $delivered_by = $_POST['delivered_by'];
    $total_price = $_POST['total_price'];
    $outward_date = $_POST['date'];

    // Get old quantity before update
    $oldQtyQuery = mysqli_query($conn, "SELECT outward_qty FROM outward WHERE outward_id = '$outward_id'");
    $oldQtyRow = mysqli_fetch_assoc($oldQtyQuery);
    $old_outward_qty = $oldQtyRow['outward_qty'];

    // Update outward entry
    $updateQuery = "UPDATE outward SET 
                    outward_qty='$new_outward_qty', 
                    delivered_by='$delivered_by', 
                    total_price='$total_price', 
                    outward_date='$outward_date' 
                    WHERE outward_id='$outward_id'";

    if (mysqli_query($conn, $updateQuery)) {
        // Adjust stock based on difference
        $qty_diff = $old_outward_qty - $new_outward_qty;

        // Update stock quantity
        $updateStockQuery = "UPDATE stock SET available_stock = available_stock + $qty_diff WHERE product_id = '$product_id'";
        mysqli_query($conn, $updateStockQuery);

        echo "<script>alert('Outward Stock updated successfully!'); window.location.href='outwardlist.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Outward Stock</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1 class="h3 mb-0 text-gray-800">Edit Outward Stock</h1>
    <div class="card shadow p-4">
    <h2 class="text-center">Edit Outward Stock</h2>
        <form method="POST" id="outwardForm">
            <div class="row">
                <div class="col-md-6">
                    <label>Select Outward ID</label>
                    <select name="outward_id" class="form-control" onchange="this.form.submit()" required>
                        <option value="">Select ID</option>
                        <?php while ($outward = mysqli_fetch_assoc($outwardResult)): ?>
                            <option value="<?= $outward['outward_id'] ?>" <?= ($outward_id == $outward['outward_id']) ? 'selected' : '' ?>>
                                <?= $outward['outward_id'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Product</label>
                    <input type="text" class="form-control" value="<?= $product_name ?>" readonly>
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                </div>

                <div class="col-md-6">
                    <label>Customer</label>
                    <input type="text" class="form-control" value="<?= $customer_name ?>" readonly>
                    <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
                </div>

                <div class="col-md-6">
                    <label>Outward Quantity</label>
                    <input type="number" name="outward_qty" id="outward_qty" class="form-control" value="<?= $outward_qty ?>" required>
                </div>

                <div class="col-md-6">
                    <label>Delivered By</label>
                    <input type="text" name="delivered_by" id="delivered_by" class="form-control" value="<?= $delivered_by ?>" required>
                </div>

                <div class="col-md-6">
                    <label>Total Price</label>
                    <input type="number" name="total_price" id="total_price" step="0.01" class="form-control" value="<?= $total_price ?>" required>
                </div>

                <div class="col-md-6">
                    <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" name="update_outward" class="btn btn-primary">Update Outward Stock</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDateTime() {
        let now = new Date();
        let formattedDateTime = now.toISOString().slice(0, 19).replace("T", " ");
        document.getElementById('currentDateTime').value = formattedDateTime;
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });
</script>
</body>
</html>
