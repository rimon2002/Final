<?php

include('db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $firstname = htmlspecialchars(trim($firstname));
    $lastname = htmlspecialchars(trim($lastname));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($password));

    
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        
        echo "All fields are required.";
    } else {
        
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

       
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        
        $stmt->close();
    }
} else {
    
    header("Location: index.html");
    exit();
}


$conn->close();
?>
