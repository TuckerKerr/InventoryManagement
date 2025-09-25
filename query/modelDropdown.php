<?php
// Database connection parameters
include('db_conn.php');

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve the input value from the form
    $typeDelivery = $_POST['type-of-delivery'];

    // Prepare and execute the stored procedure call
    $stmt = $conn->prepare("CALL GetModelInfo(:type_of_delivery)");
    $stmt->bindParam(':type_of_delivery', $typeDelivery, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo'<option value="" disabled selected>Select the model</option>';
    // Iterate over the results and call getCount immediately for each model
    foreach ($results as $row) {
        echo '
            <option value="'. htmlspecialchars($row['model'], ENT_QUOTES, 'UTF-8') . '"> ' . htmlspecialchars($row['model'], ENT_QUOTES, 'UTF-8') . '
            </option>
        ';
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Close the PDO connection
$conn = null;
?>
