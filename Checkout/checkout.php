<?php
session_start();

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
        // In real project: Save order into database here!

        // After placing order, clear cart
        $_SESSION['cart'] = [];

        // Redirect to a thank you page
        header('Location: thank_you.php');
        exit();
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <img src="../assets/logo.png" alt="UrbanFood Logo" style="height: 100px;">
        <h1>Checkout</h1>
        <div id="logo-navbar">
            <nav>
                <ul>
                    <li><a href="../Home/home_page.html">Home</a></li>
                    <li><a href="../Home/home_page.html#about">About Us</a></li>
                    <li><a href="../Home/home_page.html#services">Our Services</a></li>
                    <li><a href="../Home/home_page.html#contact">Contact Us</a></li>
                    <li class="dropdown">
                        <a href="#">Products ▼</a>
                        <ul class="dropdown-menu">
                            <li><a href="fruits.html">Fruits</a></li>
                            <li><a href="vegetables.html">Vegetables</a></li>
                            <li><a href="dairy.html">Dairy Products</a></li>
                            <li><a href="baked_goods.html">Baked Goods</a></li>
                            <li><a href="hand_made.html">Handmade Crafts</a></li>
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
