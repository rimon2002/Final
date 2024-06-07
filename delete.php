<?php
session_start(); // Start session to access session variables

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Include database connection
include('db.php');

// Check if ID parameter exists in URL
if (!isset($_GET['id'])) {
    // Redirect to dashboard or error page if ID is not provided
    header("Location: dashboard.php");
    exit();
}

// Get user ID from URL parameter
$id = $_GET['id'];

// Prepare SQL statement to delete data
$sql = "DELETE FROM users WHERE id=?";

// Using prepared statements to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// Execute the statement
if ($stmt->execute()) {
    // Redirect to dashboard or display success message
    header("Location: dashboard.php");
    exit();
} else {
    // Handle error if deletion fails
    echo "Error deleting user: " . $conn->error;
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>
