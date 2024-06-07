<?php
session_start(); // Start session to store user's session data

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include('db.php'); // Make sure this file has your database connection setup

    // Initialize variables for error handling and success message
    $error = "";
    $success = "";

    // Retrieve form data and sanitize input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Both username/email and password are required.";
    } else {
        // Prepare SQL statement to fetch user from database
        $sql = "SELECT * FROM users WHERE (email = ? OR firstname = ?) AND password = ?";
        
        // Using prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $username, $password);

        // Execute the statement
        $stmt->execute();

        // Store result
        $result = $stmt->get_result();

        // Check if user exists in database
        if ($result->num_rows == 1) {
            // User found, set session variables
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id']; // Store user ID in session (example)
            $_SESSION['username'] = $row['firstname']; // Store username in session

            // Redirect to dashboard or some other page on successful login
            header("Location: dashboard.php");
            exit();
        } else {
            // User not found or credentials incorrect
            $error = "Invalid email or password.";
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .login-container {
            max-width: 400px;
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
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php if (!empty($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
