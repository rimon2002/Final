<?php
session_start(); // Start session to access session variables

// Include database connection
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Check if ID parameter exists in URL
if (!isset($_GET['id'])) {
    // Redirect to dashboard or error page if ID is not provided
    header("Location: dashboard.php");
    exit();
}

// Fetch user details based on ID from database
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch user data
    $row = $result->fetch_assoc();
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $email = $row['email'];
} else {
    // Redirect to dashboard or error page if user with given ID is not found
    header("Location: dashboard.php");
    exit();
}

// Handle form submission for updating user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Retrieve form data and sanitize input
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Validate input (you should add more validation as per your application requirements)

    // Example of basic validation
    if (empty($firstname) || empty($lastname) || empty($email)) {
        echo "All fields are required.";
    } else {
        // Prepare SQL statement to update data
        $updateSql = "UPDATE users SET firstname=?, lastname=?, email=? WHERE id=?";
        
        // Using prepared statements to prevent SQL injection
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssi", $firstname, $lastname, $email, $id);

        // Execute the statement
        if ($updateStmt->execute()) {
            echo "Record updated successfully.";
            // Optionally, redirect to dashboard or show confirmation message
            // header("Location: dashboard.php");
            // exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close statement
        $updateStmt->close();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .edit-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>">
            <div class="form-group">
                <label for="firstname">Firstname:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
            </div>
            <div class="form-group">
                <label for="lastname">Lastname:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" name="update" class="btn" value="Update">
            </div>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
