<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_id = $_POST['supplier'];
    $customer_id = $_POST['customer'];
    $gst_rate = 18; // GST percentage

    $products = $_POST['products'];
    $quantities = $_POST['qty'];

    $subtotal = 0;
    $total_gst = 0;

    // Fetch existing customer balance
    $customerQuery = $conn->prepare("SELECT customer_balance FROM customer WHERE customer_id = ?");
    $customerQuery->bind_param("i", $customer_id);
    $customerQuery->execute();
    $result = $customerQuery->get_result();
    $customerRow = $result->fetch_assoc();
    $customer_balance = (float)$customerRow['customer_balance']; 
    $customerQuery->close();

    // Calculate totals
    foreach ($products as $key => $product_id) {
        $quantity = (int)$quantities[$key];

        $priceQuery = $conn->prepare("SELECT product_sprice FROM product WHERE product_id = ?");
        $priceQuery->bind_param("i", $product_id);
        $priceQuery->execute();
        $priceResult = $priceQuery->get_result();
        $priceRow = $priceResult->fetch_assoc();
        $price = (float)$priceRow['product_sprice'];
        $priceQuery->close();

        $gst_amount = ($price * $gst_rate / 100) * $quantity;
        $total_price = ($price * $quantity) + $gst_amount;

        $subtotal += ($price * $quantity);
        $total_gst += $gst_amount;
    }

    $final_total = $subtotal + $total_gst;
    $remaining_balance = $customer_balance + $final_total;

    // Insert invoice
    $stmt = $conn->prepare("INSERT INTO invoices (supplier_id, customer_id, total_amount, gst_amount, final_total, remaining_balance) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed (invoices): " . $conn->error);
    }
    $stmt->bind_param("iidddd", $supplier_id, $customer_id, $subtotal, $total_gst, $final_total, $remaining_balance);

    if ($stmt->execute()) {
        $invoice_id = $conn->insert_id; // ✅ Correct way

        // Insert invoice items
        $stmt_items = $conn->prepare("INSERT INTO invoice_items (invoice_id, product_id, quantity, unit_price, gst_amount, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt_items) {
            die("Prepare failed (invoice_items): " . $conn->error);
        }

        foreach ($products as $key => $product_id) {
            $quantity = (int)$quantities[$key];

            $priceQuery = $conn->prepare("SELECT product_sprice FROM product WHERE product_id = ?");
            $priceQuery->bind_param("i", $product_id);
            $priceQuery->execute();
            $priceResult = $priceQuery->get_result();
            $priceRow = $priceResult->fetch_assoc();
            $price = (float)$priceRow['product_sprice'];
            $priceQuery->close();

            $gst_amount = ($price * $gst_rate / 100) * $quantity;
            $total_price = ($price * $quantity) + $gst_amount;

            $stmt_items->bind_param("iiiddd", $invoice_id, $product_id, $quantity, $price, $gst_amount, $total_price);
            $stmt_items->execute();

            // ✅ Update stock
            $updateStockStmt = $conn->prepare("UPDATE stock SET available_stock = available_stock - ? WHERE product_id = ?");
            if ($updateStockStmt) {
                $updateStockStmt->bind_param("ii", $quantity, $product_id);
                $updateStockStmt->execute();
                $updateStockStmt->close();
            }
        }

        // Update customer balance
        $updateBalanceStmt = $conn->prepare("UPDATE customer SET customer_balance = ? WHERE customer_id = ?");
        $updateBalanceStmt->bind_param("di", $remaining_balance, $customer_id);
        $updateBalanceStmt->execute();
        $updateBalanceStmt->close();

        echo "<script>
                alert('Invoice Generated Successfully!');
                window.location.href = 'invoicelist.php';
              </script>";
    } else {
        echo "Error inserting invoice: " . $stmt->error;
    }

    $stmt->close();
    if (isset($stmt_items)) $stmt_items->close();
    $conn->close();
}
?>
