<?php
include('subdash.php');
include 'database.php';

// Fetch purchase orders with supplier details
$query = "SELECT purchase_orders.*, supplier.supplier_name 
          FROM purchase_orders 
          LEFT JOIN supplier ON purchase_orders.suplier_name = supplier.supplier_name"; 

$result = mysqli_query($conn, $query);

// Delete Purchase Order by ID
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    mysqli_query($conn, "DELETE FROM purchase_orders WHERE purchase_id = $id");
    echo "<script>alert('Purchase Order deleted successfully!'); window.location.href='podelete.php';</script>";
}

// Company and user info
$company_name = "Stock Management System"; 
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Purchase Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> 
    <style>
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        .table-container { overflow-x: auto; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; white-space: nowrap; }
        th { background-color: #4e73df; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .btn-danger { background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-danger:hover { background-color: darkred; }
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
                    <h3>Purchase Order List</h3>
                    <p><strong>Date:</strong> ${new Date().toLocaleDateString()} | <strong>Time:</strong> ${new Date().toLocaleTimeString()}</p>
                    <p><strong>Printed by:</strong> <?php echo $username; ?></p>
                </div>
                ` + document.getElementById("orderTable").outerHTML;

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
        <h1 class="h3 mb-0 text-gray-800">Delete Purchase Orders</h1>
        <button class="btn-print" onclick="printTable()">
            <i class="fa fa-print"></i> Print
        </button>
    </div>
    <div class="container">
        <h2 class="text-center">Delete Purchase Orders</h2>
        <input type="text" id="search" class="search-box" placeholder="Search Purchase Order...">
        
        <div class="table-container">
            <table id="orderTable" class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Supplier Name</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $row['purchase_id'] ?></td>
                            <td><?= isset($row['supplier_name']) ? $row['supplier_name'] : 'Unknown Supplier' ?></td>
                            <td>₹<?= number_format($row['po_total_price'], 2) ?></td>
                            <td><?= $row['po_date'] ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this purchase order?');">
                                    <input type="hidden" name="delete_id" value="<?= $row['purchase_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
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
        let rows = document.querySelectorAll("#orderTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
</body>
</html>
