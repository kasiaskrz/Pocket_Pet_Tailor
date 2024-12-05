<?php
session_start();

// Database connection
$conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Get input
$username = $_POST['username'];
$password = $_POST['password'];

// Validate credentials (example query; adapt as needed)
$sql = "SELECT user_id, username FROM users WHERE username=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Login successful, store user ID in session
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['username'] = $row['username'];

    echo json_encode(["success" => true, "message" => "Login successful!", "id" => $row['user_id']]);
} else {
    // Invalid login
    echo json_encode(["success" => false, "message" => "Invalid username or password."]);
}

$stmt->close();
$conn->close();
