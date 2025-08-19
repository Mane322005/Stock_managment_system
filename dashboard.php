<?php
        
         // Start the session
         session_start();
         
         // Include the database connection
         include('database.php');  // Assuming you have a db_config.php file for database connection
         
         // Check if the user is logged in (assuming user ID is stored in session)
         if (isset($_SESSION['user_id'])) {
             // Fetch user details from the database
             $user_id = $_SESSION['user_id'];
             
             // Prepare the SQL statement to select the full_name
             $sql = "SELECT full_name, profile_pic FROM users WHERE user_id = ?";
             
             // Create a prepared statement
             $stmt = $conn->prepare($sql);
             
             // Bind the user_id parameter to the SQL query
             $stmt->bind_param("i", $user_id);  // 'i' stands for integer type
             
             // Execute the query
             $stmt->execute();
             
             // Get the result of the query
             $result = $stmt->get_result();
             
             // Check if the user exists
             if ($result->num_rows > 0) {
                 // Fetch the user's data (full_name and profile_pic)
                 $user = $result->fetch_assoc();
                 $full_name = $user['full_name'];
                 $profile_pic = $user['profile_pic'];
             } else {
                 // If no user found, redirect to login page
                 header("Location: login.php");
                 exit();
             }
         } else {
             // If not logged in, redirect to login page
             header("Location: login.php");
             exit();
         }
         ?>
         
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>STOCK MANAGEMNT - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="boo/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="boo/css/sb-admin-2.min.css" rel="stylesheet">
  
    



</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                
                <div class="sidebar-brand-text mx-3">STOCK MANAGEMENT <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span style="font-size: 20px; font-weight: bold;">Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            
            <!-- Nav Item - Pages Collapse Menu -->
             
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecategory"
                    aria-expanded="true" aria-controls="collapsecategory">
                    
                    <span style="font-size: 20px; font-weight: bold;">Category</span>
                </a>
                <div id="collapsecategory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">CATEGOARY DASHBOARD</h6>
                        <a class="collapse-item" href="categoryadd.php">Add Category</a>
                        <a class="collapse-item" href="categoryedit.php">Edit Category</a>
                        <a class="collapse-item" href="categorydelete.php">Delete Category</a>
                        <a class="collapse-item" href="categorylist.php">List All Category</a>
                    </div>
                </div>
                
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecustomer"
                    aria-expanded="true" aria-controls="collapsecustomer">
                    
                    <span style="font-size: 20px; font-weight: bold;">Customer</span>
                </a>
                <div id="collapsecustomer" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Customer Dashboard</h6>
                        <a class="collapse-item" href="customeradd.php">Add Customer</a>
                        <a class="collapse-item" href="customeredit.php">Edit Customer</a>
                        <a class="collapse-item" href="cutomerdelete.php">Delete Customer</a>
                        <a class="collapse-item" href="customerlist.php">List All Customer</a>
                    </div>
                </div>
                <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesuplier"
                    aria-expanded="true" aria-controls="collapsesuplier">
                    
                    <span style="font-size: 20px; font-weight: bold;">Suplier</span>
                </a>
                <div id="collapsesuplier" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Suplier Dashboard</h6>
                        <a class="collapse-item" href="supplieradd.php">Add Suplier</a>
                        <a class="collapse-item" href="supplieredit.php">Edit Suplier</a>
                        <a class="collapse-item" href="supplierdelete.php">Delete Suplier</a>
                        <a class="collapse-item" href="supplierlist.php">List All Suplier</a>
                    </div>
                </div>
                
            </li>


                <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseproduct"
                    aria-expanded="true" aria-controls="collapseproduct">
                    
                    <span style="font-size: 20px; font-weight: bold;">Product</span>
                </a>
                <div id="collapseproduct" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Product Dashboard</h6>
                        <a class="collapse-item" href="productadd.php">Add product</a>
                        <a class="collapse-item" href="productedit.php">Edit product</a>
                        <a class="collapse-item" href="productdelet.php">Delete product</a>
                        <a class="collapse-item" href="productlist.php">List All product</a>
                    </div>
                </div>
                
            </li>
            
                
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseinvoice"
                    aria-expanded="true" aria-controls="collapseinvoice">
                    
                    <span style="font-size: 20px; font-weight: bold;">Invoice</span>
                </a>
                <div id="collapseinvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Invoice Dashboard</h6>
                        <a class="collapse-item" href="invoiceadd.php">Add Invoice</a>
                        
                        <a class="collapse-item" href="invoicelist.php">List All Invoice</a>
                    </div>
                </div>
                
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseinward"
                    aria-expanded="true" aria-controls="collapseinward">
                    
                    <span style="font-size: 20px; font-weight: bold;">Inward Sells</span>
                </a>
                <div id="collapseinward" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Inward Dashboard</h6>
                        <a class="collapse-item" href="inwardadd.php">Add Stock</a>
                        <a class="collapse-item" href="inwardedit.php">Edit Stock</a>
                        <a class="collapse-item" href="inwarddelete.php">Delete Stock</a>
                        <a class="collapse-item" href="inwardlist.php">List All Stock</a>
                    </div>
                </div>
                
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseoutward"
                    aria-expanded="true" aria-controls="collapseoutward">
                    
                    <span style="font-size: 20px; font-weight: bold;">Outward Stock</span>
                </a>
                <div id="collapseoutward" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Outward Dashboard</h6>
                        <a class="collapse-item" href="outwardadd.php">Add Stock</a>
                        <a class="collapse-item" href="outwardedit.php">Edit Stock</a>
                        <a class="collapse-item" href="outwarddelete.php">Delete Stock</a>
                        <a class="collapse-item" href="outwardlist.php">List All Stock</a>                    </div>
                </div>
                
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsepo"
                    aria-expanded="true" aria-controls="collapsepo">
                    
                    <span style="font-size: 20px; font-weight: bold;">Purchase Order</span>
                </a>
                <div id="collapsepo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Order Dashboard</h6>
                        <a class="collapse-item" href="poadd.php">Add Order</a>
                        
                        <a class="collapse-item" href="podelete.php">Delete Order</a>
                        <a class="collapse-item" href="polist.php">List All Order</a>
                    </div>
                </div>
                
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsereport"
                    aria-expanded="true" aria-controls="collapsereport">
                    
                    <span style="font-size: 20px; font-weight: bold;">Report</span>
                </a>
                <div id="collapsereport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Report Dashboard</h6>
                        <a class="collapse-item" href="stockreport.php">Stock Report</a>
                        <a class="collapse-item" href="salesreport.php">Sales Report </a>
                        <a class="collapse-item" href="purchasereport.php">Purchase Report </a>
                        <a class="collapse-item" href="profitlossreport.php">Profit & Loss Report</a>
                    </div>
                </div>
                
            </li>
            
            
            <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesettings"
        aria-expanded="true" aria-controls="collapsesettings">
        <span style="font-size: 20px; font-weight: bold;">Settings</span>
    </a>
    <div id="collapsesettings" class="collapse" aria-labelledby="headingThree" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">System Settings</h6>
           
            <a class="collapse-item" href="backup.php">Database Backup</a>
            <a class="collapse-item" href="logs.php">Activity Logs</a>
        </div>
    </div>
</li>
            
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        
                            

                       
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <!-- Dynamically display the user's name -->
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
            <?php echo $full_name; ?> <!-- Display the full name -->
        </span>
        <!-- Dynamically display the user's profile picture -->
        <img class="img-profile rounded-circle" 
            src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture">
    </a>
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="profile.php">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            Profile
        </a>
        <a class="dropdown-item" href="logs.php">
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            Settings
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="logout.php">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
        </a>
    </div>
</li>


                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div class="dropdown">
        <button class="btn btn-sm btn-primary shadow-sm dropdown-toggle" type="button" id="reportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </button>
        <div class="dropdown-menu" aria-labelledby="reportDropdown">
            <a class="dropdown-item" href="#" onclick="printReport('stockreport.php')">Stock Report</a>
            <a class="dropdown-item" href="#" onclick="printReport('salesreport.php')">Sales Report</a>
            <a class="dropdown-item" href="#" onclick="printReport('purchasereport.php')">Purchase Report</a>
            <a class="dropdown-item" href="#" onclick="printReport('profitlossreport.php')">Profit & Loss Report</a>
        </div>
    </div>
</div>

<script>
    function printReport(reportPage) {
        var reportWindow = window.open(reportPage, '_blank');
        reportWindow.onload = function () {
            reportWindow.print();
        };
    }
</script>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <?php

// Fetch data counts from respective tables
$category_count = $conn->query("SELECT COUNT(*) as total FROM category")->fetch_assoc()['total'];
$sells_records = $conn->query("SELECT COUNT(*) as total FROM invoices")->fetch_assoc()['total'];
$invoice_records = $conn->query("SELECT COUNT(*) as total FROM invoices")->fetch_assoc()['total'];
$purchase_orders = $conn->query("SELECT COUNT(*) as total FROM purchase_orders")->fetch_assoc()['total'];
$supplier_count = $conn->query("SELECT COUNT(*) as total FROM supplier")->fetch_assoc()['total'];

// Weekly, Monthly, and Yearly Sales (sum of final_total in invoices)
$weekly_sells = $conn->query("SELECT SUM(final_total) as total FROM invoices WHERE invoice_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_assoc()['total'] ?? 0;
$monthly_sells = $conn->query("SELECT SUM(final_total) as total FROM invoices WHERE invoice_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)")->fetch_assoc()['total'] ?? 0;
$yearly_sells = $conn->query("SELECT SUM(final_total) as total FROM invoices WHERE invoice_date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)")->fetch_assoc()['total'] ?? 0;
?>

<div class="row">
    <!-- Category Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">CATEGORIES</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $category_count; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-folder fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sells Records -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">SELLS RECORDS</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $sells_records; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Records -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">INVOICE RECORDS</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $invoice_records; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase Orders -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">PURCHASE ORDERS</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $purchase_orders; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Count -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">SUPPLIERS</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $supplier_count; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Sales -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">WEEKLY SALES</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($weekly_sells, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Sales -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">MONTHLY SALES</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($monthly_sells, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Yearly Sales -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">YEARLY SALES</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($yearly_sells, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php

$sql = "SELECT DATE_FORMAT(invoice_date, '%b') AS month, SUM(total_amount) AS earnings 
        FROM invoices 
        GROUP BY MONTH(invoice_date) 
        ORDER BY MIN(invoice_date) ASC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}


$conn->close();
?>


                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-12"> <!-- Ensure full width -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="chart-area">
                <canvas id="myAreaChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch_data.php")
        .then(response => response.json())
        .then(data => {
            const labels = data.map(row => row.month); // Extract months
            const earnings = data.map(row => row.earnings); // Extract earnings

            const ctx = document.getElementById("myAreaChart").getContext("2d");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Earnings",
                        data: earnings,
                        borderColor: "rgba(78, 115, 223, 1)",
                        backgroundColor: "rgba(78, 115, 223, 0.2)",
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { title: { display: true, text: "Month" } },
                        y: { title: { display: true, text: "Earnings ($)" } }
                    }
                }
            });
        })
        .catch(error => console.error("Error fetching data:", error));
});
</script>


                        <!-- Pie Chart -->
                        <div class="col-xl-12 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                               
                                

                   
                           

                            
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Stock Management System</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    
    

    <!-- Bootstrap core JavaScript-->
    <script src="boo/vendor/jquery/jquery.min.js"></script>
    <script src="boo/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="boo/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="boo/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="boo/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="boo/js/demo/chart-area-demo.js"></script>
    <script src="boo/js/demo/chart-pie-demo.js"></script>

</body>

</html>