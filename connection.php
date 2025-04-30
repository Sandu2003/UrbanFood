<?php

putenv("PATH=C:\\oracle\\instantclient_23_7;" . getenv("PATH"));

// Oracle DB credentials
$username = 'system';
$password = '1111'; 

// Correct connection string using SERVICE NAME (not SID)
$connection_string = '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=XE)))';

// Connect using oci_connect
$conn = oci_connect($username, $password, $connection_string);

// Error handling
if (!$conn) {
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
}

//echo "✅ Connected successfully!";
?>
