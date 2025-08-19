<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch Suppliers
$result = mysqli_query($conn, "SELECT * FROM supplier");

// Delete Supplier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    mysqli_query($conn, "DELETE FROM supplier WHERE supplier_id = $id");
    echo "<script>alert('Supplier deleted successfully!'); window.location.href='supplierdelete.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Supplier</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure consistency with dashboard styles -->
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
            width: 100%;
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
        .btn-danger {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-danger:hover {
            background-color: darkred;
        }
        .search-box {
            width: 25%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Delete Supplier</h1>
    </div>

    <h2 class="text-center">Delete Supplier</h2>
    <input type="text" id="search" class="search-box" placeholder="Search Supplier...">

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-container">
                <table class="table table-bordered" id="supplierTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier Name</th>
                            <th>Email</th>
                            <th>GSTIN</th>
                            <th>Mobile No</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Balance</th>
                            <th>Brand</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['supplier_id']; ?></td>
                                <td><?php echo $row['supplier_name']; ?></td>
                                <td><?php echo $row['supplier_email']; ?></td>
                                <td><?php echo $row['supplier_gstin']; ?></td>
                                <td><?php echo $row['supplier_mobileno']; ?></td>
                                <td><?php echo $row['supplier_address']; ?></td>
                                <td><?php echo $row['supplier_status']; ?></td>
                                <td><?php echo $row['supplier_balance']; ?></td>
                                <td><?php echo $row['supplier_brand']; ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['supplier_id']; ?>">
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
</div>

<script>
    document.getElementById("search").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#supplierTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>

</body>
</html>
