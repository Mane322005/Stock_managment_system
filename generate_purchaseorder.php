<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_id = $_POST['supplier'];
    $order_date = date('Y-m-d H:i:s');

    // Fetch Supplier Details
    $supplierQuery = $conn->query("SELECT * FROM supplier WHERE supplier_id = $supplier_id");
    $supplier = $supplierQuery->fetch_assoc();

    if (!$supplier) {
        echo "<script>
                alert('Supplier not found!');
                window.history.back();
              </script>";
        exit;
    }

    // Variables to hold total amounts
    $total_amount = 0;
    $total_gst = 0;

    // Insert Products into Order Items
    $product_ids = $_POST['products'];
    $quantities = $_POST['qty'];

    // First calculate total amount including GST
    for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = $product_ids[$i];
        $quantity = $quantities[$i];

        // Fetch product details (including price and GST)
        $productQuery = $conn->query("SELECT product_sprice, product_gst FROM product WHERE product_id = $product_id");
        $product = $productQuery->fetch_assoc();

        if (!$product) {
            continue; // Skip if product not found
        }

        $product_price = $quantity * $product['product_sprice']; // Total price without GST
        $gst_amount = ($product_price * $product['product_gst']) / 100; // GST Amount
        $final_price = $product_price + $gst_amount; // Total price including GST

        // Add to totals
        $total_amount += $product_price; // ✅ Total price without GST
        $total_gst += $gst_amount; // ✅ Total GST

        // ✅ Debug Log:
        // echo "Product: $product_id | Qty: $quantity | Price: $product_price | GST: $gst_amount | Final Price: $final_price<br>";
    }

    // ✅ Final Grand Total (Price + GST)
    $grand_total = $total_amount + $total_gst;

    // ✅ Insert into Purchase Order with calculated total
    $stmt = $conn->prepare("INSERT INTO purchase_orders (supplier_id, suplier_name, suplier_email, suplier_adress, suplier_mobileno, po_total_price, po_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("issssds", 
        $supplier_id,
        $supplier['supplier_name'], 
        $supplier['supplier_email'], 
        $supplier['supplier_address'], 
        $supplier['supplier_mobileno'], 
        $grand_total, // ✅ Grand total including GST
        $order_date
    );

    if ($stmt->execute()) {
        $purchase_id = $conn->insert_id;

        // ✅ Insert into Order Items table
        $stmt = $conn->prepare("INSERT INTO order_items (purchase_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = $product_ids[$i];
            $quantity = $quantities[$i];

            // Fetch product details again
            $productQuery = $conn->query("SELECT product_sprice, product_gst FROM product WHERE product_id = $product_id");
            $product = $productQuery->fetch_assoc();

            if (!$product) {
                continue;
            }

            $product_price = $quantity * $product['product_sprice'];
            $gst_amount = ($product_price * $product['product_gst']) / 100;
            $final_price = $product_price + $gst_amount;

            $stmt->bind_param("iiid", $purchase_id, $product_id, $quantity, $final_price);
            $stmt->execute();
        }

        // ✅ Success Message
        echo "<script>
                alert('Purchase Order Created Successfully!');
                window.location.href = 'polist.php';
              </script>";
    } else {
        // ❌ Error Message
        echo "<script>
                alert('Error Creating Purchase Order!');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
            alert('Invalid Request');
            window.history.back();
          </script>";
}
?>
