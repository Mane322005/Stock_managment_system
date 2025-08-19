<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Fetch all categories for dropdown
$categories = mysqli_query($conn, "SELECT * FROM category");

$category_name = '';
$category_status = '';
$category_date = date('Y-m-d H:i:s'); // Set current date & time

// Fetch category details when a category is selected
if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    
    $query = mysqli_query($conn, "SELECT * FROM category WHERE category_id='$category_id'");
    $category = mysqli_fetch_assoc($query);
    
    if ($category) {
        $category_name = $category['category_name'];
        $category_status = $category['category_status'];
        $category_date = $category['category_date'];
    }
}

// Handle Form Submission
if (isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = trim($_POST['category_name']);
    $category_status = $_POST['category_status'];
    $category_date = $_POST['date'];

    // Check if category name already exists (excluding the current category)
    $check_query = mysqli_query($conn, "SELECT * FROM category WHERE category_name='$category_name' AND category_id != '$category_id'");
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Category name already exists!";
    } elseif (!preg_match('/^[a-zA-Z][a-zA-Z0-9 ]*$/', $category_name)) {
        $error = "Invalid category name! The first letter must be a letter, and only letters, numbers, and spaces are allowed.";
    } else {
        $query = "UPDATE category SET category_name='$category_name', category_status='$category_status', category_date='$category_date' WHERE category_id='$category_id'";
        mysqli_query($conn, $query);
        echo "<script>alert('Category updated successfully!'); window.location.href='categorylist.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure consistency with dashboard styles -->
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Category</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Edit Category</h2>
        <?php if (isset($error)) { echo "<p class='invalid text-center' style='color:red;'>$error</p>"; } ?>
        <form method="POST" action="">
            <div class="row g-3">
                <!-- Dropdown to Select Category -->
                <div class="col-md-4">
                    <label>Select Category</label>
                    <select id="category_id" name="category_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">Select Category</option>
                        <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                            <option value="<?php echo $row['category_id']; ?>" 
                                <?php if (isset($_POST['category_id']) && $_POST['category_id'] == $row['category_id']) echo 'selected'; ?>>
                                <?php echo $row['category_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Category Name -->
                <div class="col-md-4">
                    <label>Category Name</label>
                    <input type="text" name="category_name" id="category_name" class="form-control" value="<?php echo htmlspecialchars($category_name); ?>" required>
                    <small id="category_name-message"></small>
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="category_status" id="category_status" class="form-control" required>
                        <option value="Active" <?php if ($category_status == 'Active') echo 'selected'; ?>>Active</option>
                        <option value="Inactive" <?php if ($category_status == 'Inactive') echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>

                <!-- Date & Time -->
                <div class="col-md-4">
                <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" name="edit_category" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDateTime() {
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');
        let seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('currentDateTime').value = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    // Update time every second
    document.addEventListener("DOMContentLoaded", function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });
    
    function validateField(id, pattern, message) {
        let field = document.getElementById(id);
        let feedback = document.getElementById(id + "-message");
        field.addEventListener("input", function () {
            if (!pattern.test(field.value.trim())) {
                feedback.innerText = message;
                feedback.className = "invalid";
                feedback.style.color = "red";
            } else {
                feedback.innerText = "Valid!";
                feedback.className = "valid";
                feedback.style.color = "green";
            }
        });
    }

    document.getElementById("category_name").addEventListener("input", function() {
        validateField("category_name", /^[a-zA-Z][a-zA-Z0-9 ]*$/, "Invalid category name! The first letter must be a letter, and only letters, numbers, and spaces are allowed.");
    });

</script>

</body>
</html>
