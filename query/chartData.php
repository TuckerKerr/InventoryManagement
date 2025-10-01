<?php
include('db_conn.php');
header('Content-Type: application/json');

try {
// Check if the 'type-of-delivery' parameter is set
    $Delivery_types = ['Laptops','Monitors','Desktops','Printers','Peripherals'];
    $campus = $_GET['campus'];

    // Database connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize data variables
    $data = [];
    $labels = [];  // This will store model names (or "located" values)
    $values = [];  // This will store the corresponding count for each model

    foreach($Delivery_types as $Delivery_type){
    $DataType = $Delivery_type . "InSystem";
    // Prepare the query to get model data based on the selected delivery type
    $queryDesk = "CALL GetDowncityModelInfo(:delivery_type, :campus)";  // Stored procedure to get model info
    $stmtDesk = $pdo->prepare($queryDesk);
    // Bind the parameter for the query
    $stmtDesk->bindParam(':delivery_type', $Delivery_type, PDO::PARAM_STR);
    $stmtDesk->bindParam(':campus', $campus, PDO::PARAM_STR);
    
    // Execute the query and fetch the data
    $stmtDesk->execute();
    $data = $stmtDesk->fetchAll(PDO::FETCH_ASSOC);

    // Close the cursor for the first query
    $stmtDesk->closeCursor();

    // Loop through each row in the data to query the count for each model
    foreach ($data as $row) {
        $model = $row['model'];  // Get the model name (or 'located' value)
        $hardware[] = $Delivery_type;


        // Now, query the MonitorsInSystem table to get the count for this model
        $searchSQL = "SELECT Num_units AS count FROM $DataType WHERE Model LIKE :model AND campus LIKE :campus";
        $stmtD = $pdo->prepare($searchSQL);
        $stmtD->bindParam(':model', $model, PDO::PARAM_STR);  // Bind the model value
        $stmtD->bindParam(':campus', $campus, PDO::PARAM_STR);
        $stmtD->execute();
        
        while($data1 = $stmtD->fetch(PDO::FETCH_ASSOC)){
             if ($data1 && isset($data1['count'])) {
                $values[] = (int)$data1['count'];
            }
        }
        // Close the cursor for the second query
        $stmtD->closeCursor();

        // Store the results for the chart
        $labels[] = $model;  // Store the model name in the labels array
    }
   }
        // Return the data as JSON (labels and values for the chart)
        echo json_encode([
            'type' => $hardware,
            'labels' => $labels,
            'values' => $values
        ]);
 
} catch (PDOException $e) {
    // Return any connection errors as JSON
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}
?>
