<?php
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart\styles.css">
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
            <form action="../Checkout/checkout.php" method="POST">
                <button id="checkout-button" type="submit">Checkout</button>
            </form>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>

</body>
</html>
