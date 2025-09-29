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

$model = $Toner_ID = $Toner_model = $Shipment_color  = $location = 'N/A';

$action = $_POST['action'];
$model = $_POST['model'];
$date = date('Y-m-d');
$Delivery_type = $_POST['delivery'];
$quantity = intval($_POST['quantityinput']);


      if ($action == 'add') {
        $sqlAdd = "UPDATE $Delivery_type SET Num_units = Num_units + ? WHERE EQ_type LIKE ?";
        $stmtAdd = $conn->prepare($sqlAdd);

        $stmtAdd->bindParam(1, $quantity); 
        $stmtAdd->bindParam(2, $model);

        $stmtAdd->execute();  

        echo "You clicked 'Add' button.";
      } elseif ($action == 'remove') {
        $sqlCount = "SELECT Num_units FROM $Delivery_type WHERE EQ_Type LIKE ?";
        $stmtCount = $conn->prepare($sqlCount);

        $stmtCount->bindParam(1, $model);
        $stmtCount->execute();

        $results = $stmtCount->fetchAll(PDO::FETCH_COLUMN, 0);

        if (intval($results[0]) < $quantity) {
          echo json_encode(['success' => false, 'message'=>"Cannot Pull out that many"]);
        }
        else{
        $sqlReduce = "UPDATE $Delivery_type SET Num_units = Num_units - ? WHERE EQ_type LIKE ?";
        $stmtReduce = $conn->prepare($sqlReduce);

        $stmtReduce->bindParam(1, $quantity); 
        $stmtReduce->bindParam(2, $model);

        $stmtReduce->execute();  

        $sqlRetrieval = "INSERT INTO retrieved (sticker_id, Name, Receive_date, Delivery_type, Num_units,
        EQ_Model, Toner_ID, Toner_model, Shipment_color, transfer_date, location)
        SELECT sticker_id, Name, Receive_date, Delivery_type, ? , EQ_type, ?, ?, ?, ?, campus FROM $Delivery_type where EQ_type like ?";
        $stmtRetrieval = $conn->prepare($sqlRetrieval);

        $stmtRetrieval->bindParam(1, $quantity); 
        $stmtRetrieval->bindParam(2, $Toner_ID); 
        $stmtRetrieval->bindParam(3, $Toner_model);
        $stmtRetrieval->bindParam(4, $Shipment_color); 
        $stmtRetrieval->bindParam(5, $date);
        $stmtRetrieval->bindParam(6, $model); 

  
        $stmtRetrieval->execute();  

      
        echo json_encode(['success' => true, 'message'=>"Item(s) taken out"]);
        }
      }

    // Redirect to the thank you page
    exit();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
