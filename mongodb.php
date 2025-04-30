<?php
require 'vendor/autoload.php'; // Composer's autoload file

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");

    // Select the database and collection
    $collection = $client->UrbanFood->feedback;
    // Check if the collection exists
    if (!$collection) {
        die("Collection not found.");
    }

} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

?>
