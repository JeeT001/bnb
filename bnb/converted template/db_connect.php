<?php
// Define MySQL credentials
define("DBUSER", "root");
define("DBPASSWORD", "root");
define("DBDATABASE", "bnb");

// Database connection details
$servername = "localhost"; // or your server name

// Create connection
$conn = new mysqli($servername, DBUSER, DBPASSWORD, DBDATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
