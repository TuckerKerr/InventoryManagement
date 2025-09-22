<?php
$servername = "10.9.5.21";
$username = "Tucker";
$password = "Tuck3r!$1914";
$dbname = "inventorymgt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
