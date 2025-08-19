<?php

include('subdash.php');
include 'database.php';

// Fetch Products
$product_query = "SELECT p.product_id, p.product_name, p.product_status, p.product_description, p.product_unit, 
                          p.product_sprice, p.product_gst, p.product_maxprice, s.supplier_name, c.category_name, 
                          p.product_date
                  FROM product p
                  JOIN supplier s ON p.supplier_id = s.supplier_id  -- Assuming supplier_id in product table
                  JOIN category c ON p.category_id = c.category_id";
$product_result = mysqli_query($conn, $product_query);

// Fetch company name and username dynamically
$company_name = "Stock Management System"; // Replace with a dynamic value if stored in the database
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User'; // Auto-fetch logged-in user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
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
            overflow-x: auto; /* Enables horizontal scroll */
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
            white-space: nowrap; /* Prevents text from wrapping */
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
                    <h3>Product List</h3>
                    <p><strong>Date:</strong> ${new Date().toLocaleDateString()} | <strong>Time:</strong> ${new Date().toLocaleTimeString()}</p>
                    <p><strong>Printed by:</strong> <?php echo $username; ?></p>
                </div>
                ` + document.getElementById("productTable").outerHTML;

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
        <h1 class="h3 mb-0 text-gray-800">Product List</h1>
        <button class="btn-print" onclick="printTable()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="container">
        <h2 class="text-center">Product List</h2>
        <input type="text" id="search" class="search-box" placeholder="Search Product...">
        <div class="table-container">
            <table id="productTable" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Selling Price</th>
                        <th>GST (%)</th>
                        <th>Max Price</th>
                        <th>Supplier</th>
                        <th>Category</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($product_result)) : ?>
                        <tr>
                            <td><?= $product['product_id'] ?></td>
                            <td><?= $product['product_name'] ?></td>
                            <td><?= $product['product_status'] ?></td>
                            <td><?= $product['product_description'] ?></td>
                            <td><?= $product['product_unit'] ?></td>
                            <td><?= $product['product_sprice'] ?></td>
                            <td><?= $product['product_gst'] ?></td>
                            <td><?= $product['product_maxprice'] ?></td>
                            <td><?= $product['supplier_name'] ?></td>
                            <td><?= $product['category_name'] ?></td>
                            <td><?= $product['product_date'] ?></td>
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
        let rows = document.querySelectorAll("#productTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
</body>
</html> 
