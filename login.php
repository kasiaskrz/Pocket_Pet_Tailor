<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Get input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$rememberMe = isset($_POST['remember_me']) ? (int)$_POST['remember_me'] : 0;

if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
    exit;
}

// Prepare and execute query
$sql = "SELECT user_id, username, password FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // User found, verify password
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];

        // Set or clear cookie based on "Remember Me"
        if ($rememberMe === 1) {
            // Set cookie for 30 days
            setcookie('remember_username', $username, time() + (86400 * 30), "/");
        } else {
            // Clear cookie if exists
            if (isset($_COOKIE['remember_username'])) {
                setcookie('remember_username', '', time() - 3600, "/");
            }
        }

        echo json_encode(["success" => true, "message" => "Login successful!", "id" => $row['user_id']]);
    } else {
        // Password incorrect
        echo json_encode(["success" => false, "message" => "Incorrect password."]);
    }
} else {
    // Username not found
    echo json_encode(["success" => false, "message" => "User not found."]);
}

$stmt->close();
$conn->close();
?>
