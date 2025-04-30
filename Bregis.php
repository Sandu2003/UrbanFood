<!-- register_buyer.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);

    // Connect to database
    $conn = new mysqli('localhost', 'root', '', 'urbanfood'); // Change credentials if needed

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Insert buyer
    $stmt = $conn->prepare("INSERT INTO Buyers (Name, Email, Password, Address, Contact) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $address, $contact);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='../Login/login.php';</script>";
    } else {
        echo "<script>alert('Registration failed. Try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Now the HTML form: -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Registration</title>
    <link rel="stylesheet" href="buyer_reg\style.css">
</head>
<body>
    <header>
        <a href="../Home/home_page.php" class="back-link">Home</a>
        <img src="../assets/logo.png" alt="UrbanFood Logo" style="height: 100px;">
        <h1>UrbanFood Buyer Registration</h1>
    </header>
    <main>
        <section id="register">
            <h2>Create a New Account</h2>
            <form id="register-form" method="POST" action="register_buyer.php">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="contact" placeholder="Contact" required>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="../Login/login.php">Login here</a></p>            
        </section>
    </main>
    <footer>
        <p>&copy; 2025 UrbanFood. All rights reserved.</p>
    </footer>
</body>
</html>
