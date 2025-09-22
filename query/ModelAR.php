<?php
session_start(); // Start the session

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

$Toner_ID = $Toner_model = $Shipment_color = $EQ_type = $freeInSystem = $location = 'N/A';


$Delivery_type = $_POST['type-of-delivery'];
$model = $_POST['model-tag'];
$date = date('Y-m-d');
$quantity = '0';
$location = $_POST['campus'];
$action = $_POST['action'];


$tablename = $Delivery_type . 'Model';

      if ($action == 'add') {
        $sqlAdd = "INSERT INTO $tablename (model) VALUES (?)";
        $stmtAdd = $conn->prepare($sqlAdd);

        // Bind parameters for insert
        $stmtAdd->bindParam(1, $model);
      
        $stmtAdd->execute();

        $sqlCall = "CALL smallestfreeSticker();";
        $stmtCall = $conn->prepare($sqlCall);
        $stmtCall->execute();

        $freeInSystem = $stmtCall->fetch(PDO::FETCH_ASSOC);
        $stmtCall->closeCursor();


        $freeStickerValue = $freeInSystem['sticker_id']; 

        $sqlUpdate = "INSERT INTO $Delivery_type (sticker_id, Name, Receive_date, Delivery_type, Num_units, EQ_type, Campus) VALUES (?,?,?,?,?,?,?)";

        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(1, $freeStickerValue);
        $stmtUpdate->bindParam(2, $Name);
        $stmtUpdate->bindParam(3, $date);
        $stmtUpdate->bindParam(4, $Delivery_type);
        $stmtUpdate->bindParam(5, $quantity);
        $stmtUpdate->bindParam(6, $model);
        $stmtUpdate->bindParam(7, $location);

        $stmtUpdate->execute();

         // Update statement
        $sqlUpdateDelivery = "UPDATE sticker_table SET isinUse = 1, Located = ? WHERE sticker_id = ?";
        $stmtUpdateDelivery = $conn->prepare($sqlUpdateDelivery);

        // Bind parameters for update
        $stmtUpdateDelivery->bindParam(1, $Delivery_type);
        $stmtUpdateDelivery->bindParam(2, $freeStickerValue);

        // Execute the update statement
        $stmtUpdateDelivery->execute();



          echo "You clicked 'Add' button.";

      } elseif ($action == 'remove') {
          // Code to handle remove action
        $sqlRemove = "DELETE FROM $tablename WHERE model LIKE ?";
        $stmtRemove = $conn->prepare($sqlRemove);

        // Bind parameters for insert
        $stmtRemove->bindParam(1, $model);
      
        $stmtRemove->execute();

        $sqlInfo = "SELECT sticker_id FROM $Delivery_type WHERE EQ_type = ?"; 
        $stmtInfo = $conn->prepare($sqlInfo);

        $stmtInfo->bindParam(1, $model);
        $stmtInfo->execute();

        $freeInSystem = $stmtInfo->fetch(PDO::FETCH_ASSOC);
        $stmtInfo->closeCursor();

        $sticker_id = $freeInSystem['sticker_id']; 

        $sqlUpdate = "DELETE FROM $Delivery_type WHERE EQ_type LIKE ?";
        $stmtUpdate= $conn->prepare($sqlUpdate);

        $stmtUpdate->bindParam(1, $model);
        $stmtUpdate->execute();

        // Update statement
        $sqlUpdateDelivery = "UPDATE sticker_table SET isinUse = 0, Located = 'not in use' WHERE sticker_id = ?";
        $stmtUpdateDelivery = $conn->prepare($sqlUpdateDelivery);

        // Bind parameters for update
        $stmtUpdateDelivery->bindParam(1, $sticker_id);

        // Execute the update statement
        $stmtUpdateDelivery->execute();

      }
  

    // Redirect to the thank you page
    exit();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
