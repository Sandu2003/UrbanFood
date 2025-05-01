<?php
session_start();

// Include the connection.php file to establish the database connection
include('connection.php');  // Adjust the path if necessary

// Assuming that the order ID is passed as a session or URL parameter, or get the last inserted order
$order_id = $_SESSION['order_id'] ?? '';  // Or retrieve from URL like $_GET['order_id']

if (!$order_id) {
    die("Order not found.");
}

// Fetch order details
$order_sql = "SELECT o.order_id, o.delivery_method, o.payment_method, o.total_amount, o.order_date
              FROM orders o
              WHERE o.order_id = :order_id";
$order_stmt = oci_parse($conn, $order_sql);
oci_bind_by_name($order_stmt, ':order_id', $order_id);
oci_execute($order_stmt);

// Check if the order exists
$order = oci_fetch_assoc($order_stmt);
if (!$order) {
    die("Order details not found.");
}

// Fetch order items details
$items_sql = "SELECT oi.product, oi.quantity, oi.price
              FROM order_items oi
              WHERE oi.order_id = :order_id";
$items_stmt = oci_parse($conn, $items_sql);
oci_bind_by_name($items_stmt, ':order_id', $order_id);
oci_execute($items_stmt);

// Prepare order items
$order_items = [];
while ($item = oci_fetch_assoc($items_stmt)) {
    $order_items[] = $item;
}

// Close the connection
oci_free_statement($order_stmt);
oci_free_statement($items_stmt);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="stylesheet" href="Checkout/stylesC.css">
</head>
<body>
    <header>
        <h1>Thank You!</h1>
    </header>
    <main>
        <p>Your order has been placed successfully. We'll deliver it soon!</p>

        <h2>Order Summary</h2>

        <!-- Order details -->
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
        <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
        <p><strong>Delivery Method:</strong> <?= htmlspecialchars($order['delivery_method']) ?></p>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2) ?></p>

        <h3>Items Ordered</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product']) ?></td>
                        <td><?= intval($item['quantity']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../Home/home_page.html"><button>Go Back to Home</button></a>
    </main>
    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>
</body>
</html>
