<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn) {
   // echo "Connected to the database successfully!";
} else {
    echo "Failed to connect to the database.";
}
?>