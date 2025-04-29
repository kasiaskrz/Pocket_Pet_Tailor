<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// Calculate total amount
$totalAmount = 0.0;
$stmt = $conn->prepare("SELECT p.price, c.quantity FROM shopping_cart c JOIN Products p ON c.product_id = p.product_id WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $totalAmount += $row['price'] * $row['quantity'];
}
$stmt->close();

// Insert into orders table
$order_date = date('Y-m-d H:i:s');
$insert = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, ?)");
$insert->bind_param("ids", $user_id, $totalAmount, $order_date);

if ($insert->execute()) {
    // Clear the cart after successful order
    $clear_cart = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
    $clear_cart->bind_param("i", $user_id);
    $clear_cart->execute();
    $clear_cart->close();

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to place order."]);
}

$insert->close();
$conn->close();
?>
