<?php
include('subdash.php');
include 'database.php'; // Database connection file

// Handle Form Submission
if (isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    $category_status = $_POST['category_status'];
    $date = $_POST['date']; // Now includes time

    // Check if category already exists
    $check_query = mysqli_query($conn, "SELECT * FROM category WHERE category_name='$category_name'");
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Category name already exists!";
    } elseif (!preg_match('/^[a-zA-Z][a-zA-Z0-9 ]*$/', $category_name)) {
        $error = "Invalid category name! The first letter must be a letter, and only letters, numbers, and spaces are allowed.";
    } else {
        $query = "INSERT INTO category (category_name, category_status, category_date) VALUES ('$category_name', '$category_status', '$date')";
        mysqli_query($conn, $query);
        echo "<script>alert('Category added successfully!'); window.location.href='categorylist.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure consistency with dashboard styles -->
</head>
<body>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Category</h1>
    </div>
    <div class="card shadow p-4">
        <h2 class="text-center">Add Category</h2>
        <?php if (isset($error)) { echo "<p class='invalid text-center' style='color:red;'>$error</p>"; } ?>
        <form method="POST" onsubmit="return validateCategory()">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Category Name</label>
                    <input type="text" name="category_name" id="category_name" class="form-control" required>
                    <small id="category_name-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="category_status" id="category_status" class="form-control" required>
                        <option value="">Select</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <small id="category_status-message"></small>
                </div>
                <div class="col-md-4">
                    <label>Date & Time</label>
                    <input type="text" id="currentDateTime" name="date" class="form-control" readonly>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDateTime() {
        let now = new Date();
        let formattedDateTime = now.getFullYear() + "-" +
            String(now.getMonth() + 1).padStart(2, '0') + "-" +
            String(now.getDate()).padStart(2, '0') + " " +
            String(now.getHours()).padStart(2, '0') + ":" +
            String(now.getMinutes()).padStart(2, '0') + ":" +
            String(now.getSeconds()).padStart(2, '0');

        document.getElementById('currentDateTime').value = formattedDateTime;
    }

    setInterval(updateDateTime, 1000); // Update every second
    updateDateTime(); // Initialize on page load

    function validateCategoryName() {
        let categoryName = document.getElementById("category_name").value.trim();
        let message = document.getElementById("category_name-message");
        let regex = /^[a-zA-Z][a-zA-Z0-9 ]*$/;

        if (categoryName === "") {
            message.innerText = "Category name is required!";
            message.style.color = "red";
            return false;
        } else if (!regex.test(categoryName)) {
            message.innerText = "Invalid category name! The first letter must be a letter, and only letters, numbers, and spaces are allowed.";
            message.style.color = "red";
            return false;
        } else {
            message.innerText = "Valid category name!";
            message.style.color = "green";
            return true;
        }
    }

    function validateCategoryStatus() {
        let categoryStatus = document.getElementById("category_status").value;
        let message = document.getElementById("category_status-message");

        if (categoryStatus === "") {
            message.innerText = "Category status is required!";
            message.style.color = "red";
            return false;
        } else {
            message.innerText = "Valid!";
            message.style.color = "green";
            return true;
        }
    }

    document.getElementById("category_name").addEventListener("input", validateCategoryName);
    document.getElementById("category_status").addEventListener("change", validateCategoryStatus);

    function validateCategory() {
        return validateCategoryName() && validateCategoryStatus();
    }
</script>

</body>
</html>
