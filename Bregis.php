<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash password for security
    $business_name = htmlspecialchars(trim($_POST['business-name']));
    $business_type = htmlspecialchars(trim($_POST['business-type']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $address = htmlspecialchars(trim($_POST['address']));

    if (!$email) {
        echo "<script>alert('Invalid email format'); window.history.back();</script>";
        exit;
    }

    // Include connection file
    require_once('connection.php');

    // Connect to Oracle Database
    $conn = oci_connect(DB_USERNAME, DB_PASSWORD, DB_CONNECTION_STRING);

    if (!$conn) {
        $e = oci_error();
        die('Connection failed: ' . $e['message']);
    }

    // Check if the email already exists
    $checkEmailQuery = "SELECT COUNT(*) FROM suppliers WHERE email = :email";
    $stmtCheckEmail = oci_parse($conn, $checkEmailQuery);
    oci_bind_by_name($stmtCheckEmail, ':email', $email);
    oci_execute($stmtCheckEmail);
    $row = oci_fetch_array($stmtCheckEmail, OCI_ASSOC);

    if ($row['COUNT(*)'] > 0) {
        echo "<script>alert('Email already exists. Please choose a different email.'); window.history.back();</script>";
        oci_free_statement($stmtCheckEmail);
        oci_close($conn);
        exit;
    }

    // Check if the contact already exists
    $checkContactQuery = "SELECT COUNT(*) FROM suppliers WHERE contact = :contact";
    $stmtCheckContact = oci_parse($conn, $checkContactQuery);
    oci_bind_by_name($stmtCheckContact, ':contact', $contact);
    oci_execute($stmtCheckContact);
    $row = oci_fetch_array($stmtCheckContact, OCI_ASSOC);

    if ($row['COUNT(*)'] > 0) {
        echo "<script>alert('Contact already exists. Please choose a different contact number.'); window.history.back();</script>";
        oci_free_statement($stmtCheckContact);
        oci_close($conn);
        exit;
    }

    // Prepare and execute the stored procedure to insert the data
    $sql = "BEGIN register_supplier(:name, :email, :password, :business_name, :business_type, :contact, :address); END;";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':password', $password);
    oci_bind_by_name($stmt, ':business_name', $business_name);
    oci_bind_by_name($stmt, ':business_type', $business_type);
    oci_bind_by_name($stmt, ':contact', $contact);
    oci_bind_by_name($stmt, ':address', $address);

    if (oci_execute($stmt)) {
        // Registration successful, redirect to login page
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
        exit;
    } else {
        $e = oci_error($stmt);
        echo "<script>alert('Registration failed: " . htmlspecialchars($e['message']) . "'); window.history.back();</script>";
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration</title>
    <link rel="stylesheet" href="Seller_reg/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <a href="home_page.php" class="back-link">Home</a>
        <img src="assets/logo.png" alt="UrbanFood Logo" style="height: 100px;">
        <h1>UrbanFood Seller Registration</h1>
    </header>
    <main>
        <section id="register">
            <h2>Create a New Seller Account</h2>
            <form id="register-form" method="POST" action="seller_register.php">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="business-name" placeholder="Business Name" required>
                <select name="business-type" required>
                    <option value="fruits">Fruits</option>
                    <option value="vegetables">Vegetables</option>
                    <option value="dairy">Dairy Products</option>
                    <option value="baked-goods">Baked Goods</option>
                    <option value="handmade-crafts">Handmade Crafts</option>
                </select>
                <input type="text" name="contact" placeholder="Contact Number" required>
                <textarea name="address" placeholder="Business Address" required></textarea>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>
</body>
</html>
