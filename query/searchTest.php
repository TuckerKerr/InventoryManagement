<?php
session_start(); // Start the session

// Database connection parameters
include('db_conn.php');
try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Retrieve form data
$Name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User'; 

$searchterm = $_GET['EQSearch'];
$searchWildcard = '%' . $searchterm . '%';
$viewId = $_GET['viewInfo'];
    // Prepare SQL statement to search data from the MySQL table
    // search statement
    switch($viewId){
      case 'TonerInSystem':
        $sqlTS = "SELECT * FROM TonerInSystem WHERE `Toner_ID` LIKE ? OR `Printer_model` LIKE ? OR `Color` LIKE ?  OR `sticker_id` LIKE ? ORDER BY `sticker_id` ASC";
          $stmtTS = $conn->prepare($sqlTS);

          $stmtTS->bindParam(1, $searchWildcard);
          $stmtTS->bindParam(2, $searchWildcard);
          $stmtTS->bindParam(3, $searchWildcard);
          $stmtTS->bindParam(4, $searchWildcard);
          // Execute the search statement
          $stmtTS->execute();

          // Check if there are any results
          if ($stmtTS->rowCount() > 0) {
              // Display table header
              echo "<table>";
              
              // Fetch data and display in table rows
              while ($row = $stmtTS->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . $row["sticker_id"] . 
                    '<button class="action-btn" id="'.$row["sticker_id"] .'"'.
                    'onclick="showButtonToner(this)" name="operation" value="' . $row["sticker_id"] .
                    '"><i class="fa-solid fa-minus"></i></button>'.
                    "</td>";
                  echo "<td>" . $row["Toner_ID"] . "</td>";
                  echo "<td>" . $row["Printer_model"] . "</td>";
                  echo "<td>" . $row["Color"] . "</td>";
                  echo "<td>" . $row["Located"] . "</td>";
                  echo "</tr>";
              }
              echo "</table>";
          } else {
              echo "<br>No results found for <strong> Toner ID: " . $searchterm . "</strong> in system<br>";
          }
          break;
        case 'OpenEQ':
        $sqlTS = "SELECT * FROM OpenEQ WHERE `asset_tag` LIKE ? OR `EQ_Type` LIKE ? OR `Model` LIKE ?  OR `located` LIKE ? ORDER BY `campus` ASC";
          $stmtTS = $conn->prepare($sqlTS);

          $stmtTS->bindParam(1, $searchWildcard);
          $stmtTS->bindParam(2, $searchWildcard);
          $stmtTS->bindParam(3, $searchWildcard);
          $stmtTS->bindParam(4, $searchWildcard);
          // Execute the search statement
          $stmtTS->execute();

          // Check if there are any results
          if ($stmtTS->rowCount() > 0) {
              // Display table header
              echo "<table>";
              
              // Fetch data and display in table rows
              while ($row = $stmtTS->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . $row["asset_tag"] . 
                    '<button class="action-btn" id="'.$row["asset_tag"] .'"'.
                    'onclick="showButtonToner(this)" name="operation" value="' . $row["asset_tag"] .
                    '"><i class="fa-solid fa-minus"></i></button>'.
                    "</td>";
                  echo "<td>" . $row["EQ_Type"] . "</td>";
                  echo "<td>" . $row["Model"] . "</td>";
                  echo "<td>" . $row["located"] . "</td>";
                  echo "<td>" . $row["campus"] . "</td>";
                  echo "</tr>";
              }
              echo "</table>";
          } else {
              echo "<br>No results found for <strong> Toner ID: " . $searchterm . "</strong> in system<br>";
          }
          break;
        case 'LonerLaptopsView':
            $sqlLoS = "SELECT * FROM LonerLaptopsView WHERE `asset_tag` LIKE ? OR `model_number` LIKE ? ORDER BY `asset_tag` ASC";
                $stmtLoS = $conn->prepare($sqlLoS);
    
                $stmtLoS->bindParam(1, $searchWildcard);
                $stmtLoS->bindParam(2, $searchWildcard);
                // Execute the search statement
                $stmtLoS->execute();
    
                // Check if there are any results
                if ($stmtLoS->rowCount() > 0) {
                    // Display table header
                    echo "<table>";
                    echo "<tr><th>Asset Tag</th><th>Model</th><th>Is it Loned?</th><th>Who has it?</th><th>Time In/Out</th></tr>";
                    
                    // Fetch data and display in table rows
                    while ($row = $stmtLoS->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row["asset_tag"] . "</td>";
                        echo "<td>" . $row["model_number"] . "</td>";
                        echo "<td>" . $row["is_Loaned"] . "</td>";
                        echo "<td>" . $row["loaned_to"] . "</td>";
                        echo "<td>" . $row["Last_toggle_time"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<br>No results found for <strong> Item with: " . $searchterm . "</strong> in system<br>";
                }
                break;
    }
    
  }catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null; // Close connection
?>
