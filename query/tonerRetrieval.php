<?php
session_start(); // Start the session

// Database connection parameters
include('../db_conn.php');
try {

ob_clean();
flush();
// Create a PDO instance
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// Set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Retrieve form data
$Name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User'; // Get full name from session with fallback

$sticker_id = $_POST['sticker_id'];
$Receive_date = date('Y-m-d H:i:s');

        // Delete statement
        $sqlDelete = "DELETE FROM Toner WHERE sticker_id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);

        // Bind parameters for delete
        $stmtDelete->bindParam(1, $sticker_id);

        // Execute the delete statement
        $stmtDelete->execute();

        // Update statement
        $sqlUpdate = "UPDATE sticker_table SET isinUse = 0, Located = 'not in use' WHERE sticker_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);

        // Bind parameters for update
        $stmtUpdate->bindParam(1, $sticker_id);

        // Execute the update statement
        $stmtUpdate->execute();
        
    exit();
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
