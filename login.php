<?php
session_start();

// --- Connection Functions ---

function get_supplier_connection() {
    // Ideally, use a dedicated user, not SYSTEM in production
    $conn = oci_connect('system', '1111', 'localhost/XE');
    if (!$conn) {
        $e = oci_error();
        die("❌ Supplier connection failed: " . $e['message']);
    }
    return $conn;
}

function get_buyer_connection() {
    $conn = oci_connect('C##urbanfood_user', 'password123', 'localhost/XE');
    if (!$conn) {
        $e = oci_error();
        die("❌ Buyer connection failed: " . $e['message']);
    }
    return $conn;
}

// --- Handle Login Form Submission ---

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password_input = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? '';

    if (!$email || !$password_input || !$role) {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
        exit;
    }

    if ($role === 'buyer') {
        $conn = get_buyer_connection();
        $table = 'C##urbanfood_user.BUYERS';
    } elseif ($role === 'seller') {
        $conn = get_supplier_connection();
        $table = 'SUPPLIERS'; // Assumes table exists in SYSTEM or default schema
    } else {
        echo "<script>alert('Invalid role selected.'); window.history.back();</script>";
        exit;
    }

    // --- Fetch User by Email ---
    $sql = "SELECT * FROM $table WHERE email = :email";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':email', $email);
    oci_execute($stmt);

    $user = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
    oci_free_statement($stmt);

    // --- Verify Password and Login ---
    if ($user && password_verify($password_input, $user['PASSWORD'])) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $role;

        if ($role === 'buyer') {
            header("Location: home_page.php");
        } else {
            header("Location: add_product.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid credentials'); window.history.back();</script>";
    }

    oci_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>UrbanFood Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="login/styles.css">
</head>
<body>

<header>
  <img src="assets/logo.png" alt="UrbanFood Logo" style="height:100px;">
  <h1>UrbanFood Login</h1>
</header>

<main>
  <section id="login">
    <h2>Login to Your Account</h2>
    <form method="POST" action="login.php">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <label for="role">Login as:</label>
      <select id="role" name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="buyer">Buyer</option>
        <option value="seller">Seller</option>
      </select>

      <button type="submit">Login</button>
    </form>
  </section>
</main>

<footer>
  <p>&copy; 2025 UrbanFood. All rights reserved.</p>
</footer>

</body>
</html>
