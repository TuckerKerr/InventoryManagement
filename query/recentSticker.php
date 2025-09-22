<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: text/plain'); // Change to text/plain to serve plain text
include('../db_conn.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT sticker_id FROM delivery ORDER BY id DESC LIMIT 1;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $freeInSystem = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($freeInSystem) {
        foreach ($freeInSystem as $freeInSystems) {
            foreach ($freeInSystems as $key => $value) {
                echo $value . "\n"; // Return each value on a new line
            }
        }
    } else {
        echo "No data available.";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>