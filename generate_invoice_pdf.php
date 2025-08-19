<?php
include 'database.php';

date_default_timezone_set('Asia/Kolkata');

if (!isset($_GET['id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = $_GET['id'];

// Fetch Invoice and Customer Details
$query = "SELECT invoices.*, 
                 customer.customer_name, 
                 customer.customer_address, 
                 customer.customer_mobileno, 
                 customer.customer_email, 
                 customer.customer_gstin, 
                 customer.customer_balance, 
                 supplier.supplier_name, 
                 supplier.supplier_address, 
                 supplier.supplier_mobileno, 
                 supplier.supplier_email, 
                 supplier.supplier_gstin 
          FROM invoices 
          LEFT JOIN customer ON invoices.customer_id = customer.customer_id 
          LEFT JOIN supplier ON invoices.supplier_id = supplier.supplier_id 
          WHERE invoices.invoice_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

// Fetch Invoice Items
$query_items = "SELECT invoice_items.*, 
                        product.product_name, 
                        product.product_sprice, 
                        product.product_gst 
                FROM invoice_items 
                LEFT JOIN product ON invoice_items.product_id = product.product_id 
                WHERE invoice_items.invoice_id = ?";
$stmt_items = $conn->prepare($query_items);
$stmt_items->bind_param("i", $invoice_id);
$stmt_items->execute();
$items = $stmt_items->get_result();

// Initialize total calculations
$total_gst = 0;
$grand_total = 0;
$subtotal = 0;

while ($row = $items->fetch_assoc()) {
    $item_total_price = $row['product_sprice'] * $row['quantity']; // Price without GST
    $gst_amount = ($item_total_price * $row['product_gst']) / 100; // GST Calculation
    $total_price_with_gst = $item_total_price + $gst_amount; // Total Price with GST
    
    $subtotal += $item_total_price;
    $total_gst += $gst_amount;
    $grand_total += $total_price_with_gst;
}

// Fetch remaining balance from the customer table
$remaining_balance = isset($invoice['customer_balance']) ? $invoice['customer_balance'] : 0;
$final_total = $grand_total + $remaining_balance;
$formatted_invoice_date = date("d-m-Y h:i A", strtotime($invoice['invoice_date']));
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $invoice_id ?></title>
   

    <style>
        body { font-family: Arial, sans-serif; }
        .logo {
    width: 120px; /* Adjust size as needed */
    height: auto;
    display: block;
    margin: 0 auto 10px; /* Center the logo and add spacing */
}

        .container { width: 80%; margin: auto; padding: 20px; border: 2px solid #000; }
        .header { text-align: center; }
        .details { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .box { width: 48%; padding: 10px; border: 1px solid #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #000; text-align: left; }
        th { background: #4e73df; color: white; }
        .summary { display: flex; justify-content: space-between; padding: 10px; border-top: 2px solid #000; }
        .signature { display: flex; justify-content: space-between; margin-top: 20px; }
        .btn-print { background: #4e73df; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .thank-you { text-align: center; margin-top: 20px; font-size: 16px; font-weight: bold; }
        .summary-left { width: 50%; text-align: left; }
        .summary-right { width: 50%; text-align: right; }
        @media print {
    th, td {
        font-size: 14px; /* Increase font size */
        font-weight: bold; /* Make text bold */
    }
    th {
        background: #4e73df !important;
        color: white !important;
        -webkit-print-color-adjust: exact; /* Ensure background color prints */
    }
}

    </style>
</head>
<body>
    <div class="container">
    <div class="header">
    <img src="smsl.png" alt="Company Logo" class="logo">
    <h2>Stock Management System</h2>
    <h3>GST Tax Invoice</h3>
    <p>Date: <?= date("d-m-Y h:i A") ?></p> <!-- Current Date and Time -->
    <p><strong>Invoice ID:</strong> <?= $invoice['invoice_id'] ?></p>
    <p><strong>Invoice Date:</strong> <?= $formatted_invoice_date ?></p>
</div>

        
        <div class="details">
            <div class="box">
                <h4>Customer Details</h4>
                <p><strong>Name:</strong> <?= $invoice['customer_name'] ?></p>
                <p><strong>Address:</strong> <?= $invoice['customer_address'] ?></p>
                <p><strong>Mobile:</strong> <?= $invoice['customer_mobileno'] ?></p>
                <p><strong>Email:</strong> <?= $invoice['customer_email'] ?></p>
                <p><strong>GSTIN:</strong> <?= $invoice['customer_gstin'] ?></p>
            </div>
            <div class="box">
                <h4>Supplier Details</h4>
                <p><strong>Name:</strong> <?= $invoice['supplier_name'] ?></p>
                <p><strong>Address:</strong> <?= $invoice['supplier_address'] ?></p>
                <p><strong>Mobile:</strong> <?= $invoice['supplier_mobileno'] ?></p>
                <p><strong>Email:</strong> <?= $invoice['supplier_email'] ?></p>
                <p><strong>GSTIN:</strong> <?= $invoice['supplier_gstin'] ?></p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>GST %</th>
                    <th>GST Amount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $items->data_seek(0); // Reset the items result set pointer to the start
                while ($row = $items->fetch_assoc()) :
                    $gst_amount = ($row['product_sprice'] * $row['product_gst']) / 100;
                ?>
                <tr>
                <td><?= $i++ ?></td>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td>₹<?= number_format($row['product_sprice'], 2) ?></td>
    <td><?= $row['product_gst'] ?>%</td>
    <td>₹<?= number_format(($row['product_sprice'] * $row['quantity'] * $row['product_gst']) / 100, 2) ?></td>
    <td>₹<?= number_format(($row['product_sprice'] * $row['quantity']) + (($row['product_sprice'] * $row['quantity'] * $row['product_gst']) / 100), 2) ?></td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div class="summary">
            <div class="summary-left">
                <h3>GST Amount: ₹<?= number_format($total_gst, 2) ?></h3>
                <h3>Grand Total: ₹<?= number_format($grand_total, 2) ?></h3>
            </div>
            <div class="summary-right">
                <h3>Remaining Balance: ₹<?= number_format($remaining_balance, 2) ?></h3>
                <h3><strong>Final Total Amount: ₹<?= number_format($final_total, 2) ?></strong></h3>
            </div>
        </div>
        
        <div class="signature">
            <div>
                <p>Received By: ___________</p>
            </div>
            <div>
                <p>Created By: ___________</p>
            </div>
        </div>

        <div class="thank-you">
            <p>Thank you, <?= $invoice['customer_name'] ?>, for your business! Visit Again.</p>
        </div>
        
        <button class="btn-print" onclick="printInvoice()">Print Invoice</button>

    </div>
    <script>
    function printInvoice() {
        document.title = "Invoice_<?= $invoice['customer_name'] ?>_<?= date('Y-m-d_H-i') ?>"; // Change the document title
        window.print();
    }
</script>

</body>
</html>
