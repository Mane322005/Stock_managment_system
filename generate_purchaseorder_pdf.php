<?php
include 'database.php';

date_default_timezone_set('Asia/Kolkata'); // Set timezone

if (!isset($_GET['id'])) {
    die("Purchase Order ID not provided.");
}

$purchase_id = $_GET['id'];

// Fetch purchase order details
$query = "SELECT * FROM purchase_orders WHERE purchase_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Purchase Order not found.");
}

$formatted_po_date = date("d-m-Y h:i A", strtotime($order['po_date']));

// Fetch order items with GST and product price details
$itemQuery = "SELECT oi.product_id, p.product_name, p.product_gst, oi.quantity, oi.total_price, p.product_sprice 
              FROM order_items oi
              JOIN product p ON oi.product_id = p.product_id
              WHERE oi.purchase_id = ?";
$itemStmt = $conn->prepare($itemQuery);
$itemStmt->bind_param("i", $purchase_id);
$itemStmt->execute();
$itemResult = $itemStmt->get_result();

$items = [];
$total_gst_amount = 0;
$grand_total = 0;

while ($row = $itemResult->fetch_assoc()) {
    $product_price = $row['product_sprice'];
    $quantity = $row['quantity'];
    $gst_percentage = $row['product_gst'];

    // ✅ Correct calculation: GST applied to total product price
    $total_product_price = $product_price * $quantity;
    $gst_amount = ($total_product_price * $gst_percentage) / 100;
    $final_price = $total_product_price + $gst_amount;

    // ✅ Accumulate totals
    $total_gst_amount += $gst_amount;
    $grand_total += $final_price;

    $row['gst_percentage'] = $gst_percentage;
    $row['gst_amount'] = $gst_amount;
    $row['final_price'] = $final_price;
    $row['product_price'] = $product_price;

    $items[] = $row;
}

// Fetch company details from users table
$companyQuery = "SELECT company_name, adress, mobile_no, user_email FROM users WHERE user_id = 1";
$companyResult = $conn->query($companyQuery);
$company = $companyResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $order['suplier_name'] ?>_PO_<?= date("Y-m-d H:i") ?></title>
    <link rel="icon" type="image/png" href="images/favicon3.png">

    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; padding: 20px; border: 2px solid #000; }
        .header { text-align: center; }
        .details { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .box { width: 48%; padding: 10px; border: 1px solid #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #000; text-align: left; color: #000; }
        th { background: #4e73df; color: white; }
        .summary { display: flex; justify-content: space-between; padding: 10px; border-top: 2px solid #000; }
        .signature { display: flex; justify-content: space-between; margin-top: 20px; }
        .btn-print { background: #4e73df; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .thank-you { text-align: center; margin-top: 20px; font-size: 16px; font-weight: bold; }
        
        @media print {
            body { color: #000; }
            table, th, td { border: 1px solid #000; color: #000; }
            th { background: #ccc !important; color: #000 !important; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Stock Management System</h2>
            <h3>Purchase Order</h3>
            <p><strong>Order Date:</strong> <?= $formatted_po_date ?></p>
        </div>

        <div class="details">
            <div class="box">
                <h4>Supplier Details</h4>
                <p><strong>Name:</strong> <?= $order['suplier_name'] ?></p>
                <p><strong>Address:</strong> <?= $order['suplier_adress'] ?></p>
                <p><strong>Mobile:</strong> <?= $order['suplier_mobileno'] ?></p>
                <p><strong>Email:</strong> <?= $order['suplier_email'] ?></p>
            </div>
            <div class="box">
                <h4>Company Details</h4>
                <p><strong>Company Name:</strong> <?= $company['company_name'] ?></p>
                <p><strong>Address:</strong> <?= $company['adress'] ?></p>
                <p><strong>Phone:</strong> <?= $company['mobile_no'] ?></p>
                <p><strong>Email:</strong> <?= $company['user_email'] ?></p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price (₹)</th>
                    <th>GST (%)</th>
                    <th>GST Amount (₹)</th>
                    <th>Final Price (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($items as $item): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $item['product_name'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₹<?= number_format($item['product_price'], 2) ?></td>
                        <td><?= $item['gst_percentage'] ?>%</td>
                        <td>₹<?= number_format($item['gst_amount'], 2) ?></td>
                        <td>₹<?= number_format($item['final_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary">
            <h3><strong>Total GST Amount: ₹<?= number_format($total_gst_amount, 2) ?></strong></h3>
            <h3><strong>Grand Total: ₹<?= number_format($grand_total, 2) ?></strong></h3>
        </div>
        
        <div class="signature">
            <p>Approved By: ___________</p>
            <p>Created By: ___________</p>
        </div>

        <div class="thank-you">
            <p>Thank you, <?= $order['suplier_name'] ?>, for your support!</p>
        </div>
        
        <button class="btn-print" onclick="window.print()">Print Purchase Order</button>
    </div>
</body>
</html>
