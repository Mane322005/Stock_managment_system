<?php
include('subdash.php');
include 'database.php';

// Fetch Suppliers
$supplierResult = $conn->query("SELECT * FROM supplier");

// Fetch Customers
$customerResult = $conn->query("SELECT * FROM customer");

// Fetch Products
$productResult = $conn->query("SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Invoice</h1>
    </div>

    <div class="card shadow p-4">
        <h2 class="text-center">Tax Invoice</h2>
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Company Name: Dayanand</h5>
            </div>
            <div class="col-md-6 text-right">
                <h5>Date: <span id="invoice-date"></span></h5>
                <h5>Time: <span id="invoice-time"></span></h5>
            </div>
        </div>
        <form method="POST" action="generate_invoice.php">
            <div class="row">
                <div class="col-md-6">
                    <label>Supplier:</label>
                    <select name="supplier" class="form-control" id="supplier" required>
                        <option value="">Select Supplier</option>
                        <?php while ($row = $supplierResult->fetch_assoc()) { ?>
                            <option value="<?= $row['supplier_id'] ?>" data-name="<?= $row['supplier_name'] ?>" data-address="<?= $row['supplier_address'] ?>" data-gstin="<?= $row['supplier_gstin'] ?>" data-mobileno="<?= $row['supplier_mobileno'] ?>" data-balance="<?= $row['supplier_balance'] ?>">
                                <?= $row['supplier_name'] ?> - <?= $row['supplier_mobileno'] ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div id="supplier-details" class="mt-3"></div>
                </div>
                <div class="col-md-6">
                    <label>Customer:</label>
                    <select name="customer" class="form-control" id="customer" required>
                        <option value="">Select Customer</option>
                        <?php while ($row = $customerResult->fetch_assoc()) { ?>
                            <option value="<?= $row['customer_id'] ?>" data-name="<?= $row['customer_name'] ?>" data-address="<?= $row['customer_address'] ?>" data-gstin="<?= $row['customer_gstin'] ?>" data-mobileno="<?= $row['customer_mobileno'] ?>" data-balance="<?= $row['customer_balance'] ?>">
                                <?= $row['customer_name'] ?> - <?= $row['customer_mobileno'] ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div id="customer-details" class="mt-3"></div>
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
                <h4>Remaining Balance: ₹<span id="remaining-balance">0.00</span></h4>
                <h4>Total Amount: ₹<span id="grand-total">0.00</span></h4>

                <input type="hidden" name="total_amount" id="total_amount">
                <input type="hidden" name="remaining_balance" id="remaining_balance">
                <input type="hidden" name="final_total" id="final_total">

                <button type="submit" class="btn btn-primary">Generate Invoice</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    let remainingBalance = 0;

    $('#invoice-date').text(new Date().toLocaleDateString());
    $('#invoice-time').text(new Date().toLocaleTimeString());

    $('#supplier, #customer').change(function() {
        let selectedOption = $(this).find(':selected');
        let name = selectedOption.data('name');
        let address = selectedOption.data('address');
        let gstin = selectedOption.data('gstin');
        let mobileno = selectedOption.data('mobileno');
        let balance = parseFloat(selectedOption.data('balance')) || 0;

        let detailsDiv = $(this).attr('id') === 'supplier' ? '#supplier-details' : '#customer-details';
        $(detailsDiv).html(`
            <p><strong>Name:</strong> ${name}</p>
            <p><strong>Address:</strong> ${address}</p>
            <p><strong>GSTIN:</strong> ${gstin}</p>
            <p><strong>Mobile No:</strong> ${mobileno}</p>
        `);

        $('#remaining-balance').text(balance.toFixed(2));
        remainingBalance = balance;
        calculateTotal();
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

        let finalTotal = grandTotal + remainingBalance;

        $('#grand-total').text(finalTotal.toFixed(2));
        $('#total_amount').val(grandTotal.toFixed(2));
        $('#remaining_balance').val(remainingBalance.toFixed(2));
        $('#final_total').val(finalTotal.toFixed(2));
    }
});
</script>
</body>
</html>
