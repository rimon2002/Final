<?php
// Include database connection
include('db.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate and sanitize input (example: you should validate and sanitize more thoroughly in a real application)
    $firstname = htmlspecialchars(trim($firstname));
    $lastname = htmlspecialchars(trim($lastname));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($password));

    // Example of basic validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        // Handle empty fields error (you can customize this part)
        echo "All fields are required.";
    } else {
        // Prepare SQL statement to insert data
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        
        // Using prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close statement
        $stmt->close();
    }
} else {
    // Redirect to homepage or handle the case when the form is not submitted
    header("Location: index.html");
    exit();
}

// Close connection
$conn->close();
?>
