<?php
include('subdash.php');
include 'database.php'; // Database connection

// Fetch purchase details from purchase_orders and order_items
$query = "SELECT 
            po.purchase_id, 
            po.supplier_id, 
            s.supplier_name, 
            oi.product_id, 
            p.product_name, 
            IFNULL(oi.quantity, 0) AS quantity, 
            oi.total_price, 
            po.po_date 
          FROM purchase_orders po
          LEFT JOIN order_items oi ON po.purchase_id = oi.purchase_id
          LEFT JOIN product p ON oi.product_id = p.product_id
          LEFT JOIN supplier s ON po.supplier_id = s.supplier_id
          ORDER BY po.po_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Report</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        .table-container { overflow-x: auto; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; white-space: nowrap; }
        th { background-color: #4e73df; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .btn-print { background-color: #4e73df; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-print:hover { background-color: #375ac0; }
        .print-header { text-align: center; margin-bottom: 20px; }

        /* Hide Sidebar and Other Elements During Print */
        @media print {
            body * { visibility: hidden; }
            .print-section, .print-section * { visibility: visible; }
            .print-section { position: absolute; left: 0; top: 0; width: 100%; }
            .btn-print { display: none; }
        }
    </style>
    <script>
        function printPurchaseReport() {
            window.print();
        }
    </script>
</head>
<body>
<div class="container print-section">
    <div class="header-container">
        <h2>Purchase Report</h2>
        <button class="btn-print" onclick="printPurchaseReport()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="print-header">
        <h2>Stock Management System</h2>
        <h3>Purchase Report</h3>
        <p><strong>Date:</strong> <?= date('d-m-Y') ?> | <strong>Time:</strong> <?= date('h:i A') ?></p>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Purchase ID</th>
                    <th>Product Name</th>
                    <th>Supplier Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $row['purchase_id'] ?></td>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['supplier_name'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= number_format($row['total_price'], 2) ?></td>
                        <td><?= date('d-m-Y', strtotime($row['po_date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
