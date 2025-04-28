<?php  
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Login to your account to place an order!";
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id <= 0) {
    echo "Invalid product ID.";
    exit;
}

$conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the product is already in the cart
$stmt = $conn->prepare("SELECT quantity FROM shopping_cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update quantity if the product is already in the cart
    $stmt->bind_result($quantity);
    $stmt->fetch();
    $quantity++;

    $update_stmt = $conn->prepare("UPDATE shopping_cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    // Insert new item into the cart
    $insert_stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $quantity = 1;
    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $insert_stmt->execute();
    $insert_stmt->close();
}

$stmt->close();
$conn->close();

echo "Product added to cart successfully.";
?>