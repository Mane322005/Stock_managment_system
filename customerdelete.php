<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch Customers
$result = mysqli_query($conn, "SELECT * FROM customer");

// Delete Customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    mysqli_query($conn, "DELETE FROM customer WHERE id = $id");
    echo "<script>alert('Customer deleted successfully!'); window.location.href='customerdelete.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Customer</title>
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
            overflow-x: auto; /* Enables horizontal scrolling */
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
            white-space: nowrap; /* Prevents text wrapping */
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
        <h1 class="h3 mb-0 text-gray-800">Delete Customer</h1>
    </div>

    <h2 class="text-center">Delete Customer</h2>
    <input type="text" id="search" class="search-box" placeholder="Search Customer...">

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-container">
                <table class="table table-bordered" id="customerTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Email</th>
                            <th>GSTIN</th>
                            <th>Balance</th>
                            <th>Mobile No</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['customer_name']; ?></td>
                                <td><?php echo $row['customer_address']; ?></td>
                                <td><?php echo $row['customer_status']; ?></td>
                                <td><?php echo $row['customer_email']; ?></td>
                                <td><?php echo $row['customer_gstin']; ?></td>
                                <td><?php echo $row['customer_balance']; ?></td>
                                <td><?php echo $row['customer_mobileno']; ?></td>
                                <td><?php echo $row['customer_date']; ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
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
        let rows = document.querySelectorAll("#customerTable tbody tr");
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>

</body>
</html>
