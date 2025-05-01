<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $quantity = 1;

    // Insert into cart
    $query = "INSERT INTO cart (cart_id, product_id, quantity) VALUES (cart_seq.NEXTVAL, :product_id, :quantity)";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":product_id", $product_id);
    oci_bind_by_name($stmt, ":quantity", $quantity);

    if (oci_execute($stmt)) {
        echo "success";
    } else {
        $error = oci_error($stmt);
        echo "error: " . $error['message'];
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>
