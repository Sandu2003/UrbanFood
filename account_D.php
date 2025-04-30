<?php
session_start();

// 1. Check if buyer is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'buyer') {
    header("Location: ../Login/login.php");
    exit();
}

// 2. Connect to your Database
$host = 'localhost'; // Change if needed
$dbname = 'urbanfood';
$username = 'root'; // Your DB username
$password = '';     // Your DB password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// 3. Fetch Buyer Details
$email = $_SESSION['user_email'];
$accountDetails = null;
$orderList = [];

try {
    // Fetch account details
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND role = 'buyer'");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $accountDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch orders
    $stmtOrders = $conn->prepare("SELECT * FROM orders WHERE buyer_email = :email ORDER BY order_date DESC");
    $stmtOrders->bindParam(':email', $email);
    $stmtOrders->execute();
    $orderList = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Query Failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
    <link rel="stylesheet" href="Buyer_dashboard\style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body> 
    <header>
        <div class="logo">
            <img src="../assets/logo.png" alt="UrbanFood Logo">
        </div>
        <nav class="top-nav">
            <a href="../Home/home_page.html" class="nav-link">Home</a>
            <select id="dashboardSelect" class="dashboard-select" onchange="navigateDashboard()">
                <option value="buyer" selected>Buyer</option>
                <option value="seller">Seller</option>
            </select>
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
                <?php if ($accountDetails): ?>
                    <p><strong>Name:</strong> <?= htmlspecialchars($accountDetails['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($accountDetails['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($accountDetails['phone']) ?></p>
                <?php else: ?>
                    <p>No account details found.</p>
                <?php endif; ?>
            </div>
        </section>

        <section id="orders" class="dashboard-content">
            <h2>Your Orders</h2>
            <div id="orderList" class="order-list">
                <?php if (!empty($orderList)): ?>
                    <?php foreach ($orderList as $order): ?>
                        <div class="order-item">
                            <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
                            <p><strong>Product:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
                            <p><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']) ?></p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; 2025 UrbanFood. All Rights Reserved.</p>
    </footer>

    <script src="Buyer_dashboard\script.js"></script>
</body>
</html>
