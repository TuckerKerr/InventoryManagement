<?php
$servername = "10.9.5.21";
$username = "webpage";
$password = "BorkBork22!";
$dbname = "it_front_desk";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
