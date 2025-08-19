<?php
include('subdash.php');
include 'database.php'; // Database connection

// Fetch stock details with default values for NULL quantities
$query = "SELECT 
            p.product_id, 
            p.product_name, 
            c.category_name, 
            IFNULL(SUM(i.inward_qty), 0) AS total_inward, 
            (IFNULL(SUM(i.inward_qty), 0) - IFNULL((SELECT SUM(quantity) FROM invoice_items WHERE product_id = p.product_id), 0)) AS current_stock
          FROM product p
          LEFT JOIN category c ON p.category_id = c.category_id
          LEFT JOIN inward i ON p.product_id = i.product_id
          GROUP BY p.product_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Report</title>
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
        function printStockReport() {
            window.print();
        }
    </script>
</head>
<body>
<div class="container print-section">
    <div class="header-container">
        <h2>Stock Report</h2>
        <button class="btn-print" onclick="printStockReport()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="print-header">
        <h2>Stock Management System</h2>
        <h3>Stock Report</h3>
        <p><strong>Date:</strong> <?= date('d-m-Y') ?> | <strong>Time:</strong> <?= date('h:i A') ?></p>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Total Inward</th>
                    <th>Current Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $row['product_id'] ?></td>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['category_name'] ?></td>
                        <td><?= $row['total_inward'] ?></td>
                        <td><?= $row['current_stock'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
