<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Database connection settings
include('db_conn.php');

// Function to log debug information
function debugLog($message, $data = null) {
    error_log("Login Debug - " . $message . ": " . print_r($data, true));
}

// Get the form data
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    debugLog("Attempting login for user", $user);

    // Modified query to ensure we get all necessary information
    $sql = "
        SELECT 
            u.password_hash,
            COALESCE(w.campus, 'student') as campus,
            CASE 
                WHEN w.campus = 'staff' THEN 1
                ELSE 0
            END as is_staff
        FROM users u 
        LEFT JOIN workers w ON u.name = w.name 
        WHERE u.name = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        debugLog("Prepare statement failed", $conn->error);
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("s", $user);
    
    if (!$stmt->execute()) {
        debugLog("Execute failed", $stmt->error);
        die("Failed to execute query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        debugLog("Query result", $row);

        // Verify the password
        if (password_verify($pass, $row['password_hash'])) {
            // Set session variables
            $_SESSION['username'] = $user;
            $_SESSION['is_staff'] = (bool)$row['is_staff']; // Explicitly cast to boolean
            $_SESSION['campus'] = $row['campus'];

            debugLog("Session variables set", [
                'username' => $_SESSION['username'],
                'is_staff' => $_SESSION['is_staff'],
                'campus' => $_SESSION['campus']
            ]);

            // Send success response with user and is_staff info as JSON
            echo json_encode([
                'success' => true,
                'message' => "Login successful! Welcome, " . htmlspecialchars($user),
                'is_staff' => $_SESSION['is_staff'],
                'campus' => $_SESSION['campus']
            ]);
        } else {
            debugLog("Password verification failed for user", $user);
            echo json_encode([
                'success' => false,
                'error' => "Invalid password."
            ]);
        }
    } else {
        debugLog("No user found", $user);
        echo json_encode([
            'success' => false,
            'error' => "Username not found."
        ]);
    }

    // Close statement
    $stmt->close();
} else {
    debugLog("Invalid request - missing username or password");
    echo json_encode([
        'success' => false,
        'error' => "Invalid request. Username or password not set."
    ]);
}

// Close the connection
$conn->close();
?>
