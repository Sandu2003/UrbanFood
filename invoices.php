<?php
// Oracle DB connection (adjust credentials as needed)
$conn = oci_connect('system', '1111', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    die("❌ Oracle Connection failed: " . $e['message']);
}

// Query to get orders
$sql = "SELECT * FROM ORDERS ORDER BY ORDER_DATE DESC";
$statement = oci_parse($conn, $sql);
oci_execute($statement);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices - UrbanFood</title>
    <link rel="stylesheet" href="Home/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            margin-top: 40px;
            color: #333;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: center;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Invoices - Orders Placed</h1>

<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total Price (LKR)</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = oci_fetch_assoc($statement)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ORDER_ID']) ?></td>
                <td><?= htmlspecialchars($row['CUSTOMER_NAME']) ?></td>
                <td><?= number_format($row['TOTAL_AMOUNT'], 2) ?></td>
                <td><?= htmlspecialchars($row['ORDER_DATE']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
oci_free_statement($statement);
oci_close($conn);
?>

</body>
</html>
