<?php
include('subdash.php');
include 'database.php';

// Fetch sales report data with customer details and GST
$query = "SELECT invoices.invoice_id, customer.customer_name, invoices.total_amount, invoices.gst_amount, invoices.remaining_balance, invoices.final_total, invoices.invoice_date 
          FROM invoices 
          LEFT JOIN customer ON invoices.customer_id = customer.customer_id";
$result = mysqli_query($conn, $query);

$company_name = "Stock Management System"; 
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> 
    <style>
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        .table-container { overflow-x: auto; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; white-space: nowrap; }
        th { background-color: #4e73df; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .btn-print { background-color: #4e73df; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-print:hover { background-color: #375ac0; }
        .search-box { width: 25%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .print-header { text-align: center; margin-bottom: 20px; }
    </style>
    <script>
        function printTable() {
            var printContents = `
                <div class='print-header'>
                    <h2><?php echo $company_name; ?></h2>
                    <h3>Sales Report</h3>
                    <p><strong>Date:</strong> ${new Date().toLocaleDateString()} | <strong>Time:</strong> ${new Date().toLocaleTimeString()}</p>
                    <p><strong>Printed by:</strong> <?php echo $username; ?></p>
                </div>
                ` + document.getElementById("salesTable").outerHTML;

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
        <h1 class="h3 mb-0 text-gray-800">Sales Report</h1>
        <button class="btn-print" onclick="printTable()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="container">
        <h2 class="text-center">Sales Report</h2>
        <input type="text" id="search" class="search-box" placeholder="Search Sales Report...">
        
        <div class="table-container">
            <table id="salesTable" class="table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>GST Amount</th>
                        <th>Remaining Balance</th>
                        <th>Final Total</th>
                        <th>Invoice Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $row['invoice_id'] ?></td>
                            <td><?= isset($row['customer_name']) ? $row['customer_name'] : 'Unknown Customer' ?></td>
                            <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                            <td>₹<?= number_format($row['gst_amount'], 2) ?></td>  <!-- ✅ Added GST Amount -->
                            <td>₹<?= number_format($row['remaining_balance'], 2) ?></td>
                            <td>₹<?= number_format($row['final_total'], 2) ?></td>
                            <td><?= $row['invoice_date'] ?></td>
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
        let rows = document.querySelectorAll("#salesTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
</body>
</html>
