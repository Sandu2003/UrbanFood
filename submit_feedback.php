<?php
require 'mongodb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $insertResult = $collection->insertOne([
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'timestamp' => new MongoDB\BSON\UTCDateTime()
    ]);

    if ($insertResult->getInsertedCount() === 1) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='home_page.php';</script>";
    } else {
        echo "<script>alert('Failed to submit feedback.'); window.history.back();</script>";
    }
} else {
    echo "Invalid request.";
}
?>
