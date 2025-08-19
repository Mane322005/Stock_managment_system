<?php
include('subdash.php');
include 'database.php';

// Fetch Suppliers
$supplierResult = $conn->query("SELECT * FROM supplier");

// Fetch Products
$productResult = $conn->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Purchase Order</h1>
    </div>

    <div class="card shadow p-4">
        <h2 class="text-center">Purchase Order</h2>
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Company Name: Dayanand</h5>
            </div>
            <div class="col-md-6 text-right">
                <h5>Date: <span id="current-date"></span></h5>
                <h5>Time: <span id="current-time"></span></h5>
            </div>
        </div>

        <form method="POST" action="generate_purchaseorder.php">
            <div class="row">
                <div class="col-md-6">
                    <label>Supplier:</label>
                    <select name="supplier" class="form-control" id="supplier" required>
                        <option value="">Select Supplier</option>
                        <?php while ($row = $supplierResult->fetch_assoc()) { ?>
                            <option value="<?= $row['supplier_id'] ?>" data-name="<?= $row['supplier_name'] ?>" data-address="<?= $row['supplier_address'] ?>" data-gstin="<?= $row['supplier_gstin'] ?>" data-mobileno="<?= $row['supplier_mobileno'] ?>">
                                <?= $row['supplier_name'] ?> - <?= $row['supplier_mobileno'] ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div id="supplier-details" class="mt-3"></div>
                </div>
            </div>

            <h5 class="mt-4">Select Products:</h5>
            <div id="product-list">
                <div class="row product-row">
                    <div class="col-md-5">
                        <select name="products[]" class="form-control product-select" required>
                            <option value="">Select Product</option>
                            <?php while ($row = $productResult->fetch_assoc()) { ?>
                                <option value="<?= $row['product_id'] ?>" data-price="<?= $row['product_sprice'] ?>" data-gst="<?= $row['product_gst'] ?>">
                                    <?= $row['product_name'] ?> (₹<?= $row['product_sprice'] ?>, GST: <?= $row['product_gst'] ?>%)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="qty[]" class="form-control qty" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control total" readonly placeholder="Total">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-product">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success mt-2" id="add-product">Add Product</button>

            <div class="text-end mt-3">
                <h4>Total Amount: ₹<span id="grand-total">0.00</span></h4>

                <input type="hidden" name="total_amount" id="total_amount">

                <button type="submit" class="btn btn-primary">Generate Purchase Order</button>
            </div>
        </form>
    </div>
</div>

<script>
function updateDateTime() {
    let now = new Date();
    document.getElementById('current-date').innerText = now.toLocaleDateString();
    document.getElementById('current-time').innerText = now.toLocaleTimeString();
}
setInterval(updateDateTime, 1000);
updateDateTime();

$(document).ready(function() {
    $('#supplier').change(function() {
        let selectedOption = $(this).find(':selected');
        let details = `
            <p><strong>Name:</strong> ${selectedOption.data('name')}</p>
            <p><strong>Address:</strong> ${selectedOption.data('address')}</p>
            <p><strong>GSTIN:</strong> ${selectedOption.data('gstin')}</p>
            <p><strong>Mobile No:</strong> ${selectedOption.data('mobileno')}</p>
        `;
        $('#supplier-details').html(details);
    });

    $('#add-product').click(function() {
        let newRow = $('.product-row:first').clone();
        newRow.find('select').val('');
        newRow.find('.qty').val(1);
        newRow.find('.total').val('');
        $('#product-list').append(newRow);
    });

    $(document).on('change', '.product-select, .qty', function() {
        calculateTotal();
    });

    $(document).on('click', '.remove-product', function() {
        if ($('.product-row').length > 1) {
            $(this).closest('.product-row').remove();
            calculateTotal();
        }
    });

    function calculateTotal() {
        let grandTotal = 0;
        $('.product-row').each(function() {
            let qty = $(this).find('.qty').val();
            let price = $(this).find('.product-select option:selected').data('price') || 0;
            let total = qty * price;
            $(this).find('.total').val(total.toFixed(2));
            grandTotal += total;
        });

        $('#grand-total').text(grandTotal.toFixed(2));
        $('#total_amount').val(grandTotal.toFixed(2));
    }
});
</script>
</body>
</html>
