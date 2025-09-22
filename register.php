<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
include('db_conn.php');

// Get the form data
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Check if the username exists in the workers table and if they are active
    $sql = "SELECT name FROM workers WHERE name = ? AND active = 1";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();  // Store the result

    // If a match is found in the worker table and the user is active, proceed
    if ($stmt->num_rows > 0) {
        // Check if the user already exists in the users table
        $sqlCheck = "SELECT name FROM users WHERE name = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $user);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            echo "Username already exists.";
        } else {
            // If not, insert the new user into the users table
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
            $sqlInsert = "INSERT INTO users (name, password_hash) VALUES (?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("ss", $user, $hashedPassword);

            if ($stmtInsert->execute()) {
                echo "Registration successful! Welcome, " . htmlspecialchars($user) . ".";
            } else {
                echo "Registration failed: " . $stmtInsert->error;
            }

            $stmtInsert->close();
        }

        $stmtCheck->close();
    } else {
        echo "Username not found in campus student workers or inactive.";
    }

    // Close statements
    $stmt->close();
} else {
    echo "Invalid request. Username or password not set.";
}

// Close the connection
$conn->close();
?>