<?php
include('subdash.php');
include 'database.php'; // Database connection

// ✅ Set the timezone to Chennai/Mumbai (Asia/Kolkata)
date_default_timezone_set('Asia/Kolkata');

// ✅ Fetch total sales revenue from invoices (NO GST calculation)
$salesQuery = "SELECT IFNULL(SUM(final_total), 0) AS total_sales FROM invoices";
$salesResult = mysqli_query($conn, $salesQuery);
$salesRow = mysqli_fetch_assoc($salesResult);
$totalSales = $salesRow['total_sales'];

// ✅ Fetch total purchase cost from inward table (includes GST)
$purchaseQuery = "SELECT IFNULL(SUM(total_price), 0) AS total_purchases FROM inward";
$purchaseResult = mysqli_query($conn, $purchaseQuery);
$purchaseRow = mysqli_fetch_assoc($purchaseResult);
$totalPurchases = $purchaseRow['total_purchases'];

// ✅ Calculate profit or loss
$profitLoss = $totalSales - $totalPurchases;
$status = ($profitLoss >= 0) ? "Profit" : "Loss";
$statusColor = ($profitLoss >= 0) ? "green" : "red";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit & Loss Report</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        th {
            background-color: #4e73df;
            color: white;
            font-size: 16px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .profit-loss {
            font-size: 24px;
            font-weight: bold;
            color: <?= $statusColor ?>;
            margin-top: 15px;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .btn-print {
            background-color: #4e73df;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn-print:hover {
            background-color: #375ac0;
        }
        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }

        /* ✅ Hide Sidebar and Other Elements During Print */
        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
<div class="container print-section">
    <div class="header-container">
        <h2>Profit & Loss Report</h2>
        <button class="btn-print" onclick="printReport()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="print-header">
        <h2>Stock Management System</h2>
        <h3>Profit & Loss Report</h3>
        <p><strong>Date:</strong> <?= date('d-m-Y') ?> | <strong>Time:</strong> <?= date('h:i A') ?></p>
    </div>
    <div class="table-container">
        <table>
            <tr>
                <th>Total Sales Revenue</th>
                <td>₹<?= number_format($totalSales, 2) ?></td>
            </tr>
            <tr>
                <th><strong>Total Purchase Cost</strong></th>
                <td><strong>₹<?= number_format($totalPurchases, 2) ?></strong></td>
            </tr>
            <tr>
                <th>Profit / Loss</th>
                <td class="profit-loss"><?= $status ?>: ₹<?= number_format(abs($profitLoss), 2) ?></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
