<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["count" => 0]);
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

if ($conn->connect_error) {
    echo json_encode(["count" => 0]);
    exit;
}

$stmt = $conn->prepare("SELECT SUM(quantity) AS total_quantity FROM shopping_cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$count = 0;
if ($row = $result->fetch_assoc()) {
    $count = (int)$row['total_quantity'];
}

echo json_encode(["count" => $count]);

$stmt->close();
$conn->close();
?>
