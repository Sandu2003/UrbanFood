<?php
session_start();

// Include the database connection file
include('connection.php');  // This will include the connection.php file

// Dummy data if session cart not yet created
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        ['product' => 'Apple', 'quantity' => 2, 'price' => 1.5],
        ['product' => 'Milk', 'quantity' => 1, 'price' => 2.0]
    ];
}

// Remove item from cart if requested
if (isset($_GET['remove'])) {
    $index = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex array
    }
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['quantity'] * $item['price'];
}

// Handle form submission (Checkout)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_method = $_POST['delivery-method'] ?? '';
    $payment_method = $_POST['payment-method'] ?? '';

    if ($delivery_method && $payment_method) {
        // Insert order into the database
        $user_id = 1; // Use the logged-in user's ID (this is a placeholder)

        // Insert the order record
        $sql = "INSERT INTO orders (delivery_method, payment_method, total_amount) 
                VALUES (:delivery_method, :payment_method, :total_amount)";
        $stmt = oci_parse($conn, $sql);
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
    <title>Shopping Cart - UrbanFood</title>
    <link rel="stylesheet" href="cart/styles.css">
</head>
<body>

    <!-- Header Section -->
    <header id="top-header">
        <h1>Welcome to UrbanFood</h1>
        <div id="logo-wrapper">
            <img src="assets/logo.png" alt="UrbanFood Logo" id="logo">
        </div>
    </header>

    <!-- Navigation Bar -->
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

    <!-- Cart Items Section -->
    <main id="cart-main">
        <section id="cart-items">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product']) ?></td>
                            <td><?= intval($item['quantity']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                            <td><a href="?remove=<?= $index ?>"><button>Remove</button></a></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($_SESSION['cart'])): ?>
                        <tr>
                            <td colspan="5">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Order Summary Section -->
        <section id="order-summary">
            <h2>Order Summary</h2>
            <p><strong>Total:</strong> $<span id="total-price"><?= number_format($total, 2) ?></span></p>
            <form action="checkout.php" method="POST">
                <label for="delivery-method">Choose Delivery Method:</label>
                <select id="delivery-method" name="delivery-method" required>
                    <option value="" disabled selected>Select a method</option>
                    <option value="standard">Standard Delivery</option>
                    <option value="express">Express Delivery</option>
                </select>

                <label for="payment-method">Choose Payment Method:</label>
                <select id="payment-method" name="payment-method" required>
                    <option value="" disabled selected>Select a method</option>
                    <option value="credit-card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="cod">Cash on Delivery</option>
                </select>

                <button type="submit" id="checkout-button">Proceed to Checkout</button>
            </form>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>

</body>
</html>
