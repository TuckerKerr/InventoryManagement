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
// Get full name from session with fallback
$Delivery_type = "Toner";
$Receive_date = $_POST['date-received'];


// Initialize additional fields
$Toner_ID = $Toner_model = $Shipment_color = $EQ_type = $freeInSystem ='N/A';

    $Toner_ID = $_POST['toner-id'];
    $Toner_model = $_POST['toner-model'];
    $Shipment_color = $_POST['color'];
    $location = $_POST['toner-location'];
    $Num_units = "1";


    $sqlCall = "CALL smallestfreeSticker();";
    $stmtCall = $conn->prepare($sqlCall);
    $stmtCall->execute();

    $freeInSystem = $stmtCall->fetch(PDO::FETCH_ASSOC);
    $stmtCall->closeCursor();


    $freeStickerValue = $freeInSystem['sticker_id']; 


    // Update statement
    $sqlUpdateDelivery = "UPDATE sticker_table SET isinUse = 1, Located = ? WHERE sticker_id = ?";
    $stmtUpdateDelivery = $conn->prepare($sqlUpdateDelivery);

    // Bind parameters for update
    $stmtUpdateDelivery->bindParam(1, $Delivery_type);
    $stmtUpdateDelivery->bindParam(2, $freeStickerValue);

    // Execute the update statement
    $stmtUpdateDelivery->execute();

    $sqlToner = "INSERT INTO Toner (sticker_id, Name, Receive_date, Delivery_type, Num_units, Toner_ID, Toner_model, Shipment_color, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtToner = $conn->prepare($sqlToner);

    $stmtToner->bindParam(1, $freeStickerValue);
    $stmtToner->bindParam(2, $Name);
    $stmtToner->bindParam(3, $Receive_date);
    $stmtToner->bindParam(4, $Delivery_type);
    $stmtToner->bindParam(5, $Num_units);
    $stmtToner->bindParam(6, $Toner_ID);
    $stmtToner->bindParam(7, $Toner_model);
    $stmtToner->bindParam(8, $Shipment_color);
    $stmtToner->bindParam(9, $location);

    // Execute the insert statement
    $stmtToner->execute();

    // Redirect to the thank you page
    exit();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>