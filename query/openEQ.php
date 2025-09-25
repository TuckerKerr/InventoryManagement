<?php
session_start(); // Start the session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
include('../db_conn.php');
try {
// Create a PDO instance
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// Set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Retrieve form data
$Name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User'; 
$action = $_POST['action'];

if($action == 'add'){
    // Get full name from session with fallback
    $Delivery_type = $_POST['type-of-delivery'];
    $Asset_Tag = $_POST['asset_tag'];
    $Model = $_POST['eq_model'];
    $located = $_POST['located'];
    $campus = $_POST['campus'];


    $sqlOpen = "INSERT INTO OpenEquipment (asset_tag, EQ_Type, EQ_Model, location, campus, staff) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtOpen = $conn->prepare($sqlOpen);

    $stmtOpen->bindParam(1, $Asset_Tag);
    $stmtOpen->bindParam(2, $Delivery_type);
    $stmtOpen->bindParam(3, $Model);
    $stmtOpen->bindParam(4, $located);
    $stmtOpen->bindParam(5, $campus);
    $stmtOpen->bindParam(6, $Name);

    // Execute the insert statement
    $stmtOpen->execute();

    // Redirect to the thank you page
    exit();
}
if($action=='remove'){
    $Asset_Tag = $_POST['asset_tags'];
    
    $sqlOpen = "DELETE FROM OpenEquipment WHERE asset_tag = ?";
    $stmtOpen = $conn->prepare($sqlOpen);

    $stmtOpen->bindParam(1, $Asset_Tag);
    
    // Execute the remove statement
    $stmtOpen->execute();

    // Redirect to the thank you page
    exit();
}

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>