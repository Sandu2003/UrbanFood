<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'connection.php'; // Oracle DB connection

// Fetch total suppliers
$query_suppliers = "SELECT COUNT(*) AS total_suppliers FROM SUPPLIERS";
$stmt_suppliers = oci_parse($conn, $query_suppliers);
oci_execute($stmt_suppliers);
$row_suppliers = oci_fetch_assoc($stmt_suppliers);
$total_suppliers = $row_suppliers['TOTAL_SUPPLIERS'] ?? 0;

// Fetch total orders
$query_orders = "SELECT COUNT(*) AS total_orders FROM ORDERS";
$stmt_orders = oci_parse($conn, $query_orders);
oci_execute($stmt_orders);
$row_orders = oci_fetch_assoc($stmt_orders);
$total_orders = $row_orders['TOTAL_ORDERS'] ?? 0;

// Fetch total income via PL/SQL function
$query_income = "BEGIN :income := get_total_income; END;";
$stmt_income = oci_parse($conn, $query_income);
oci_bind_by_name($stmt_income, ":income", $total_income, 32);
oci_execute($stmt_income);

// Free statements
oci_free_statement($stmt_suppliers);
oci_free_statement($stmt_orders);
oci_free_statement($stmt_income);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | UrbanFood</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Base */
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            background: #f9f9f9;
            color: #333;
        }

        /* Header */
        header {
            background: #8ecebb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0d1e44;
            text-decoration: none;
        }
        .logo i {
            color: #ff8800;
            margin-right: 8px;
        }
        nav a {
            color: #000;
            margin: 0 10px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #ff8800;
        }
        .icons a div {
            background: #eee;
            width: 3rem; height: 3rem;
            border-radius: .5rem;
            line-height: 3rem;
            text-align: center;
            font-size: 1.5rem;
            margin-left: .5rem;
            transition: 0.3s;
        }
        .icons a div:hover {
            background: #e6450a;
            color: #fff;
        }

        /* Dashboard */
        .main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .main h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 30px;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .card {
            background: #6c9ecf;
            padding: 20px;
            border-radius: 10px;
            width: 220px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .card:hover {
            background: #e2e6ea;
            transform: scale(1.05);
        }
        .card .icon-box {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .card-content div:first-child {
            font-size: 26px;
            font-weight: bold;
        }
        .card-content div:last-child {
            font-size: 16px;
        }

        /* Welcome Section */
        .welcome {
            margin-top: 50px;
            text-align: center;
        }
        .welcome img {
            width: 400px;
            height: auto;
            margin-top: 20px;
        }
        .welcome h2 {
            font-size: 28px;
            margin-top: 20px;
            color: #555;
        }

        @media (max-width: 768px) {
            .cards {
                flex-direction: column;
                align-items: center;
            }
            .welcome img {
                width: 90%;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="#" class="logo"><i class="fas fa-shopping-basket"></i>UrbanFood</a>

    <nav>
        <a href="admin_products.php">Products</a>
        <a href="admin_suppliers.php">Suppliers</a>
        <a href="admin_view_feedbacks.php">Feedbacks</a>
        <a href="admin_orders.php">Orders</a>
    </nav>

    <div class="icons">
        <a href="logout.php"><div class="fas fa-sign-out-alt"></div></a>
    </div>
</header>

<div class="main">
    <h1>Welcome Admin!</h1>

    <div class="cards">
        <div class="card">
            <div class="icon-box"><i class="fas fa-calendar-day"></i></div>
            <div class="card-content">
                <div id="currentDate">--</div>
                <div>Date</div>
            </div>
        </div>

        <div class="card">
            <div class="icon-box"><i class="fas fa-clock"></i></div>
            <div class="card-content">
                <div id="currentTime">--</div>
                <div>Time</div>
            </div>
        </div>

        <div class="card">
            <div class="icon-box"><i class="fas fa-users"></i></div>
            <div class="card-content">
                <div><?= $total_suppliers ?></div>
                <div>Suppliers</div>
            </div>
        </div>

        <div class="card">
            <div class="icon-box"><i class="fas fa-box-open"></i></div>
            <div class="card-content">
                <div><?= $total_orders ?></div>
                <div>Orders</div>
            </div>
        </div>

        <div class="card">
            <div class="icon-box"><i class="fas fa-wallet"></i></div>
            <div class="card-content">
                <div><?= "LKR " . number_format($total_income, 2) ?></div>
                <div>Monthly Income</div>
            </div>
        </div>
    </div>

    <div class="welcome">
        <img src="../Images/Dashboard.png" alt="Dashboard Image">
        <h2>Have a great day, Admin!</h2>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = now.toLocaleDateString(undefined, dateOptions);
        document.getElementById('currentTime').textContent = now.toLocaleTimeString(undefined, { hour12: true });
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>

</body>
</html>
