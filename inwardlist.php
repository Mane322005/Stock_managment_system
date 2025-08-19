<?php
include('subdash.php');
include 'database.php';

// Fetch Inward Stock Data with Product & Supplier details
$sql = "SELECT i.product_id, p.product_name, i.inward_qty, i.supplier_id, 
               i.received_by, i.delivered_by, i.total_price, i.inward_date, 
               s.supplier_name
        FROM inward i
        LEFT JOIN supplier s ON i.supplier_id = s.supplier_id
        LEFT JOIN product p ON i.product_id = p.product_id";

$result = $conn->query($sql);

if (!$result) {
    die("Query Failed: " . $conn->error); 
}

// Fetch company name and username dynamically
$company_name = "Stock Management System";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inward Stock List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td {
            padding: 10px; text-align: left; border-bottom: 1px solid #ddd; white-space: nowrap;
        }
        th { background-color: #4e73df; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .btn-print {
            background-color: #4e73df; color: white; padding: 10px 15px;
            border: none; border-radius: 5px; cursor: pointer;
        }
        .btn-print:hover { background-color: #375ac0; }
        .search-box {
            width: 25%; padding: 8px; margin-bottom: 10px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        .print-header { text-align: center; margin-bottom: 20px; }
    </style>
    <script>
        function printTable() {
            var printContents = `
                <div class='print-header'>
                    <h2><?php echo $company_name; ?></h2>
                    <h3>Inward Stock List</h3>
                    <p><strong>Date:</strong> ${new Date().toLocaleDateString()} | 
                       <strong>Time:</strong> ${new Date().toLocaleTimeString()}</p>
                    <p><strong>Printed by:</strong> <?php echo $username; ?></p>
                </div>
                ` + document.getElementById("inwardTable").outerHTML;

            var originalContents = document.body.innerHTML;
            document.body.innerHTML = "<html><head><title>Print</title></head><body>" + printContents + "</body></html>";
            window.print();
            location.reload();
        }
    </script>
</head>
<body id="page-top">
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inward Stock List</h1>
        <button class="btn-print" onclick="printTable()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="container">
        <h2 class="text-center">Inward Stock List</h2>
        <input type="text" id="search" class="search-box" placeholder="Search...">
        <div class="table-container">
            <table id="inwardTable" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Supplier</th>
                        <th>Quantity</th>
                        <th>Received By</th>
                        <th>Delivered By</th>
                        <th>Total Price</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($inward = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $inward['product_id'] ?></td>
                            <td><?= $inward['product_name'] ?></td>
                            <td><?= $inward['supplier_name'] ?></td>
                            <td><?= $inward['inward_qty'] ?></td>
                            <td><?= $inward['received_by'] ?></td>
                            <td><?= $inward['delivered_by'] ?></td>
                            <td><?= $inward['total_price'] ?></td>
                            <td><?= $inward['inward_date'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.getElementById("search").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#inwardTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
</body>
</html>
