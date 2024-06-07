<?php
session_start(); // Start session to store user's session data

// Include database connection
include('db.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve form data and sanitize input
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate input (you should add more validation as per your application requirements)

    // Example of basic validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } else {
        // Prepare SQL statement to insert data
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        
        // Using prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Record inserted successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
