<?php
session_start(); // Start the session

// Database connection parameters
include('db_connection.php');

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

if(isset($_POST['email']) && isset($_POST['username'])  && !isset($_POST['password'])){
    $email = $_POST['email'];
    $user = $_POST['username'];

    if(!str_ends_with($email, '@jwu.edu')){
        echo json_encode(["success" => false, "message" => "Email invalid"]);
        exit();
    }
    else{

    $sqlEmail = "SELECT email FROM users WHERE username = ?;";
    $stmtEmail = $conn->prepare($sqlEmail);

    $stmtEmail->bind_param("s", $user);
    $stmtEmail->execute();
    $results=$stmtEmail->get_result();
    $row = $results->fetch_row();

    if ($row[0] === '' || is_null($row[0])) {
        $sqlAddEmail="UPDATE users SET email = ? WHERE username = ?";
        $stmtAddEmail = $conn->prepare($sqlAddEmail);

        $stmtAddEmail->bind_param("ss", $email, $user);
        $stmtAddEmail->execute();

        echo json_encode(["success" => true, "message" => "Email Successfully Added"]);

        $stmtAddEmail->close();
    }
}
}
else{

// Get the form data
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $email = $_POST['email'];

    if(!str_ends_with($email, '@jwu.edu')){
        echo 'Email is Invalid';
        exit();
    }
    else{
       
    
    // Check if the username exists in the campus_student_workers table and if they are active
    $sql = "SELECT student_name FROM campus_student_workers WHERE student_name = ? AND active = 1";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die(json_encode(["success" => false, "message" => "Statement preparation failed: " . $conn->error]));
    }

    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();  // Store the result

    // If a match is found in the campus_student_workers table and the user is active, proceed
    if ($stmt->num_rows > 0) {
        // Check if the user already exists in the users table
        $sqlCheck = "SELECT username FROM users WHERE username = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $user);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Username already exists."]);
        } else {
            // If not, insert the new user into the users table
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
            $sqlInsert = "INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("sss", $user, $hashedPassword, $email);

            if ($stmtInsert->execute()) {
                echo json_encode(["success" => true, "message" => "Registration successful!", "name" => $user]);
            } else {
                echo json_encode(["success" => false, "message" => "Registration failed: " . $stmtInsert->error]);
            }

            $stmtInsert->close();
        }

        $stmtCheck->close();
    } else {
        echo json_encode(["success" => false, "message" => "Username not found in campus student workers or inactive."]);
    }

    // Close statements
    $stmt->close();
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request. Username or password not set."]);
}
}

// Close the connection
$conn->close();
?>
