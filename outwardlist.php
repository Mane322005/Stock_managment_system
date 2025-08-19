<?php
include('subdash.php');
include 'database.php';

// Fetch Outward Stock Data
$outward_query = "SELECT o.outward_id, p.product_name, c.customer_name, o.outward_qty, 
                         o.delivered_by, o.total_price, o.outward_date 
                  FROM outward o
                  JOIN product p ON o.product_id = p.product_id
                  JOIN customer c ON o.customer_id = c.customer_id";
$outward_result = mysqli_query($conn, $outward_query);

// Fetch company name and username dynamically
$company_name = "Stock Management System";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outward Stock List</title>
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
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        th {
            background-color: #4e73df;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn-print {
            background-color: #4e73df;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #375ac0;
        }
        .search-box {
            width: 25%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            .btn-print {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
    <script>
        function printTable() {
            var printContents = `
                <div class='print-header'>
                    <h2><?php echo $company_name; ?></h2>
                    <h3>Outward Stock List</h3>
                    <p><strong>Date:</strong> ${new Date().toLocaleDateString()} | <strong>Time:</strong> ${new Date().toLocaleTimeString()}</p>
                    <p><strong>Printed by:</strong> <?php echo $username; ?></p>
                </div>
                ` + document.getElementById("outwardTable").outerHTML;

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
        <h1 class="h3 mb-0 text-gray-800">Outward Stock List</h1>
        <button class="btn-print" onclick="printTable()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="container">
        <h2 class="text-center">Outward Stock List</h2>
        <input type="text" id="search" class="search-box" placeholder="Search...">
        <div class="table-container">
            <table id="outwardTable" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Delivered By</th>
                        <th>Total Price</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($outward = mysqli_fetch_assoc($outward_result)) : ?>
                        <tr>
                            <td><?= $outward['outward_id'] ?></td>
                            <td><?= $outward['product_name'] ?></td>
                            <td><?= $outward['customer_name'] ?></td>
                            <td><?= $outward['outward_qty'] ?></td>
                            <td><?= $outward['delivered_by'] ?></td>
                            <td><?= $outward['total_price'] ?></td>
                            <td><?= $outward['outward_date'] ?></td>
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
        let rows = document.querySelectorAll("#outwardTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
</body>
</html>
