<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $emailExists = 0; // check for if email exists already;
    $usernameExists = 0; // check for if username exists already;

    $conn = new mysqli('katarzynaproject', 'root', '', 'pocket_pet_tailor');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT username FROM Users WHERE username='$username'";
    $result_username = $conn->query($sql);

    if ($result_username->num_rows > 0) {
        $usernameExists = 1;
    }

    $sql = "SELECT email FROM Users WHERE email='$email'";
    $result_email = $conn->query($sql);

    if ($result_email->num_rows > 0) {
        $emailExists = 1;
    }

    if($usernameExists != 1 && $emailExists != 1) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    // Execute the statement
    if ($stmt->execute()) {
        echo "User registered successfully!<br>";
        echo "Username : {$username}<br>";
        echo "Email : {$email}<br>";
    } else {
        echo "Error: " . $stmt->error;
    }
  
    // Close connections
    $stmt->close();
    }
    else {
        if($usernameExists == 1) {
        echo "The username already exists<br>";
    }
        if($emailExists == 1){
        echo "The email address has already been used<br>";
    }
    }
    $conn->close();
}
?>
