<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch all inward entries for dropdown selection
$inwardResult = mysqli_query($conn, "SELECT * FROM inward");

// Initialize form fields
$inward_id = $product_id = $supplier_id = $inward_qty = $received_by = $delivered_by = $total_price = $inward_date = "";

// Fetch inward data when an ID is selected
if (isset($_POST['inward_id'])) {
    $inward_id = $_POST['inward_id'];
    $query = "SELECT * FROM inward WHERE inward_id = '$inward_id'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $supplier_id = $row['supplier_id'];
        $inward_qty = $row['inward_qty'];
        $received_by = $row['received_by'];
        $delivered_by = $row['delivered_by'];
        $total_price = $row['total_price'];
        $inward_date = $row['inward_date'];
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

// Fetch supplier name
$supplier_name = '';
if (!empty($supplier_id)) {
    $supplierQuery = mysqli_query($conn, "SELECT supplier_name FROM supplier WHERE supplier_id = '$supplier_id'");
    if ($supplierRow = mysqli_fetch_assoc($supplierQuery)) {
        $supplier_name = $supplierRow['supplier_name'];
    }
}

// Handle Form Submission
if (isset($_POST['update_inward'])) {
    $new_inward_qty = $_POST['inward_qty'];
    $received_by = $_POST['received_by'];
    $delivered_by = $_POST['delivered_by'];
    $total_price = $_POST['total_price'];
    $inward_date = $_POST['date'];

    // Get old quantity before update
    $oldQtyQuery = mysqli_query($conn, "SELECT inward_qty FROM inward WHERE inward_id = '$inward_id'");
    $oldQtyRow = mysqli_fetch_assoc($oldQtyQuery);
    $old_inward_qty = $oldQtyRow['inward_qty'];

    // Update inward entry
    $updateQuery = "UPDATE inward SET 
                    inward_qty='$new_inward_qty', 
                    received_by='$received_by', 
                    delivered_by='$delivered_by', 
                    total_price='$total_price', 
                    inward_date='$inward_date' 
                    WHERE inward_id='$inward_id'";

    if (mysqli_query($conn, $updateQuery)) {
        // Adjust stock based on difference
        $qty_diff = $new_inward_qty - $old_inward_qty;

        // Update stock quantity
        $updateStockQuery = "UPDATE stock SET available_stock = available_stock + $qty_diff WHERE product_id = '$product_id'";
        mysqli_query($conn, $updateStockQuery);

        echo "<script>alert('Inward Stock updated successfully!'); window.location.href='inwardlist.php';</script>";
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
    <title>Edit Inward Stock</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Inward Stock</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Edit Inward Stock</h2>
        <form method="POST" id="inwardForm">
            <div class="row">
                <div class="col-md-6">
                    <label>Select Inward ID</label>
                    <select name="inward_id" class="form-control" onchange="this.form.submit()" required>
                        <option value="">Select ID</option>
                        <?php while ($inward = mysqli_fetch_assoc($inwardResult)): ?>
                            <option value="<?= $inward['inward_id'] ?>" <?= ($inward_id == $inward['inward_id']) ? 'selected' : '' ?>>
                                <?= $inward['inward_id'] ?>
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
                    <label>Supplier</label>
                    <input type="text" class="form-control" value="<?= $supplier_name ?>" readonly>
                    <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">
                </div>

                <div class="col-md-6">
                    <label>Inward Quantity</label>
                    <input type="number" name="inward_qty" id="inward_qty" class="form-control" value="<?= $inward_qty ?>" required>
                </div>

                <div class="col-md-6">
                    <label>Received By</label>
                    <input type="text" name="received_by" id="received_by" class="form-control" value="<?= $received_by ?>" required>
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
                <button type="submit" name="update_inward" class="btn btn-primary">Update Inward Stock</button>
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

    function validateField(input, pattern, errorMessage) {
        input.addEventListener("input", function () {
            if (!pattern.test(input.value.trim())) {
                input.style.borderColor = "red";
            } else {
                input.style.borderColor = "green";
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        validateField(document.getElementById("inward_qty"), /^[1-9]\d*$/, "Enter a valid quantity.");
        validateField(document.getElementById("received_by"), /^[a-zA-Z\s]+$/, "Only letters allowed.");
        validateField(document.getElementById("delivered_by"), /^[a-zA-Z\s]+$/, "Only letters allowed.");
        validateField(document.getElementById("total_price"), /^[0-9]+(\.[0-9]{1,2})?$/, "Enter a valid price.");
    });
</script>
</body>
</html>
