<?php
$conn = oci_connect('system', '1111', '//localhost/XE');
if (!$conn) {
    $e = oci_error();
    echo "❌ Connection failed: " . $e['message'];
} else {
    echo "✅ Connected successfully!";
}
?>
<?php
echo "It works!";
?>
