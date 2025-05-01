<?php
session_start();

// Include the connection file to establish the database connection
include('connection.php');  // Path to connection.php file

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: ../Cart/cart.php'); // redirect back to cart if empty
    exit();
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['quantity'] * $item['price'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_method = $_POST['delivery-method'] ?? '';
    $payment_method = $_POST['payment-method'] ?? '';

    if ($delivery_method && $payment_method) {
        // Insert order into the database
        $user_id = 1; // Use the logged-in user's ID (this is a placeholder)

        // Insert the order record
        $sql = "INSERT INTO orders (user_id, delivery_method, payment_method, total_amount) 
                VALUES (:user_id, :delivery_method, :payment_method, :total_amount)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $user_id);
        oci_bind_by_name($stmt, ':delivery_method', $delivery_method);
        oci_bind_by_name($stmt, ':payment_method', $payment_method);
        oci_bind_by_name($stmt, ':total_amount', $total);

        if (oci_execute($stmt)) {
            // Get the last inserted order ID using Oracle's sequence or IDENTITY column
            $order_id = oci_insert_id($conn);  // Oracle doesn't support LAST_INSERT_ID(), but we use oci_insert_id for IDENTITY columns

            // Insert the order items into the order_items table
            foreach ($_SESSION['cart'] as $item) {
                $sql = "INSERT INTO order_items (order_id, product, quantity, price) 
                        VALUES (:order_id, :product, :quantity, :price)";
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':order_id', $order_id);
                oci_bind_by_name($stmt, ':product', $item['product']);
                oci_bind_by_name($stmt, ':quantity', $item['quantity']);
                oci_bind_by_name($stmt, ':price', $item['price']);
                oci_execute($stmt);
            }

            // After placing order, clear cart
            $_SESSION['cart'] = [];

            // Redirect to a thank you page
            header('Location: thank_you.php');
            exit();
        } else {
            $error = "Error placing order. Please try again.";
        }
    } else {
        $error = "Please select delivery and payment methods.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="Checkout/styles.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <img src="assets/logo.png" alt="UrbanFood Logo" style="height: 50px;">
        <h1>Checkout</h1>
        <div id="logo-navbar">
            <nav>
                <ul>
                    <li><a href="home_page.php">Home</a></li>
                    <li><a href="home_page.php#about">About Us</a></li>
                    <li><a href="home_page.php#services">Our Services</a></li>
                    <li><a href="home_page.php#contact">Contact Us</a></li>
                    <li class="dropdown">
                        <a href="#">Products ▼</a>
                        <ul class="dropdown-menu">
                            <li><a href="fruits.php">Fruits</a></li>
                            <li><a href="vegetables.php">Vegetables</a></li>
                            <li><a href="dairy.php">Dairy Products</a></li>
                            <li><a href="baked_goods.php">Baked Goods</a></li>
                            <li><a href="hand_made.php">Handmade Crafts</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Checkout Form Section -->
    <main>
        <section id="checkout">
            <h2>Complete Your Order</h2>

            <?php if (!empty($error)): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form id="checkout-form" method="POST" action="checkout.php">
                <!-- Delivery Options -->
                <label for="delivery-method">Choose Delivery Method:</label>
                <select id="delivery-method" name="delivery-method" required>
                    <option value="" disabled selected>Select a method</option>
                    <option value="standard">Standard Delivery</option>
                    <option value="express">Express Delivery</option>
                </select>

                <!-- Payment Options -->
                <label for="payment-method">Choose Payment Method:</label>
                <select id="payment-method" name="payment-method" required>
                    <option value="" disabled selected>Select a method</option>
                    <option value="credit-card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="cod">Cash on Delivery</option>
                </select>

                <!-- Order Summary -->
                <h3>Order Summary</h3>
                <div id="order-details">
                    <ul>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li>
                                <?= htmlspecialchars($item['product']) ?> x <?= intval($item['quantity']) ?> — 
                                $<?= number_format($item['quantity'] * $item['price'], 2) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <p><strong>Total Amount:</strong> $<span id="total-amount"><?= number_format($total, 2) ?></span></p>

                <button type="submit">Place Order</button>
            </form>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>

</body>
</html>
