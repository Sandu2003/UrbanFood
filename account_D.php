<?php
session_start();

// ✅ Simulate login session (for testing only)
$_SESSION['user_logged_in'] = true;
$_SESSION['user_role'] = 'buyer';
$_SESSION['user_email'] = 'jane.doe@example.com';

// ✅ Hardcoded test buyer details
$accountDetails = [
    'NAME'    => 'Jane Doe',
    'EMAIL'   => 'jane.doe@example.com',
    'CONTACT' => '+1 555 123 4567',
    'ADDRESS' => '123 Elm Street, Springfield, USA'
];

// ✅ Hardcoded test order list
$orderList = [
    [
        'ORDER_ID'     => 'ORD1002',
        'PRODUCT_NAME' => 'Gluten-Free Bread',
        'QUANTITY'     => '1',
        'STATUS'       => 'Shipped',
        'ORDER_DATE'   => '2025-04-28'
    ],
    [
        'ORDER_ID'     => 'ORD1001',
        'PRODUCT_NAME' => 'Organic Avocados (Pack of 6)',
        'QUANTITY'     => '2',
        'STATUS'       => 'Delivered',
        'ORDER_DATE'   => '2025-04-10'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buyer Dashboard - UrbanFood</title>
    <link rel="stylesheet" href="Buyer_dashboard/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

</head>
<body>

<header>
    <div class="logo">
        <img src="assets/logo.png" alt="UrbanFood Logo">
    </div>
    <nav class="top-nav">
        <a href="home_page.php" class="nav-link">Home</a>
        <select id="dashboardSelect" class="dashboard-select" onchange="navigateDashboard()">
            <option value="buyer" selected>Buyer</option>
            <option value="seller">Seller</option>
        </select>
        <a href="logout.php" class="nav-link">Logout</a>
    </nav>
</header>

<main>
    <aside class="side-nav">
        <ul>
            <li><a href="#orders">View Orders</a></li>
            <li><a href="#account-details">Account Details</a></li>
        </ul>
    </aside>

    <section id="account-details" class="dashboard-content">
        <h2>Account Details</h2>
        <div id="accountDetails">
            <p><strong>Name:</strong> <?= htmlspecialchars($accountDetails['NAME']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($accountDetails['EMAIL']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($accountDetails['CONTACT']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($accountDetails['ADDRESS']) ?></p>
        </div>
    </section>

    <section id="orders" class="dashboard-content">
        <h2>Your Orders</h2>
        <div id="orderList" class="order-list">
            <?php foreach ($orderList as $order): ?>
                <div class="order-item">
                    <p><strong>Order ID:</strong> <?= htmlspecialchars($order['ORDER_ID']) ?></p>
                    <p><strong>Product:</strong> <?= htmlspecialchars($order['PRODUCT_NAME']) ?></p>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($order['QUANTITY']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($order['STATUS']) ?></p>
                    <p><strong>Order Date:</strong> <?= htmlspecialchars($order['ORDER_DATE']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 UrbanFood. All Rights Reserved.</p>
</footer>

<script>
    function navigateDashboard() {
        const selected = document.getElementById('dashboardSelect').value;
        if (selected === 'seller') {
            window.location.href = 'add_product.php';
        }
    }
</script>

</body>
</html>
