<?php
session_start();
include('connection.php');

// Dummy cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        ['product' => 'Apple', 'quantity' => 2, 'price' => 1.5],
        ['product' => 'Milk', 'quantity' => 1, 'price' => 2.0]
    ];
}

// Remove item
if (isset($_GET['remove'])) {
    $index = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['quantity'] * $item['price'];
}

// Checkout form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_method = $_POST['delivery-method'] ?? '';
    $payment_method = $_POST['payment-method'] ?? '';

    if ($delivery_method && $payment_method) {
        // Insert order into orders table
        $order_id = 0;
        $sql = "INSERT INTO orders (ID, DELIVERY_METHOD, PAYMENT_METHOD, TOTAL_AMOUNT, ORDER_DATE)
                VALUES (orders_seq.NEXTVAL, :delivery_method, :payment_method, :total_amount, SYSTIMESTAMP)
                RETURNING ID INTO :order_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':delivery_method', $delivery_method);
        oci_bind_by_name($stmt, ':payment_method', $payment_method);
        oci_bind_by_name($stmt, ':total_amount', $total);
        oci_bind_by_name($stmt, ':order_id', $order_id, 10); // Get generated order ID

        if (oci_execute($stmt)) {
            // Insert order items
            foreach ($_SESSION['cart'] as $item) {
                $sql_item = "INSERT INTO order_items (order_id, product, quantity, price)
                             VALUES (:order_id, :product, :quantity, :price)";
                $stmt_item = oci_parse($conn, $sql_item);
                oci_bind_by_name($stmt_item, ':order_id', $order_id);
                oci_bind_by_name($stmt_item, ':product', $item['product']);
                oci_bind_by_name($stmt_item, ':quantity', $item['quantity']);
                oci_bind_by_name($stmt_item, ':price', $item['price']);
                oci_execute($stmt_item);
            }

            $_SESSION['cart'] = [];
            header('Location: thank_you.php');
            exit();
        } else {
            $e = oci_error($stmt);
            $error = "Order insert failed: " . $e['message'];
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
    <title>Cart - UrbanFood</title>
    <link rel="stylesheet" href="cart/styles.css">
</head>
<body>

<header id="top-header">
    <h1>Welcome to UrbanFood</h1>
    <div id="logo-wrapper">
        <img src="assets/logo.png" alt="UrbanFood Logo" id="logo">
    </div>
</header>

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

<main id="cart-main">
    <section id="cart-items">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($cart_items)): ?>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['PRODUCT_NAME']) ?></td>
                        <td><?= (int)$item['QUANTITY'] ?></td>
                        <td>$<?= number_format($item['PRICE'], 2) ?></td>
                        <td>$<?= number_format($item['QUANTITY'] * $item['PRICE'], 2) ?></td>
                        <td><a href="?remove=<?= $item['CART_ID'] ?>"><button>Remove</button></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Your cart is empty.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section id="order-summary">
        <h2>Order Summary</h2>
        <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
        <form method="POST">
            <label for="delivery-method">Delivery Method:</label>
            <select name="delivery-method" required>
                <option value="" disabled selected>Select</option>
                <option value="standard">Standard</option>
                <option value="express">Express</option>
            </select>

            <label for="payment-method">Payment Method:</label>
            <select name="payment-method" required>
                <option value="" disabled selected>Select</option>
                <option value="credit-card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="cod">Cash on Delivery</option>
            </select>

            <button type="submit" id="checkout-button">Proceed to Checkout</button>
        </form>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 UrbanFood. All rights reserved.</p>
</footer>

</body>
</html>
