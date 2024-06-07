<?php
session_start(); // Start session to store user's session data

// Include database connection
include('db.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Retrieve form data and sanitize input
    $id = $_POST['user_id'];
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Validate input (you should add more validation as per your application requirements)

    // Example of basic validation
    if (empty($id) || empty($firstname) || empty($lastname) || empty($email)) {
        echo "All fields are required.";
    } else {
        // Prepare SQL statement to update data
        $sql = "UPDATE users SET firstname=?, lastname=?, email=? WHERE id=?";
        
        // Using prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $firstname, $lastname, $email, $id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Record updated successfully.";
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
