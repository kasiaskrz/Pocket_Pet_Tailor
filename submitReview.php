<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to submit a review."]);
    exit();
}

$host = 'localhost';
$dbname = 'pocket_pet_tailor';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $rating = $_POST['rating'];
        $review_text = $_POST['review_text'];
        $product_id = $_POST['product_id'];

        if (empty($rating) || empty($review_text) || empty($product_id)) {
            echo json_encode(["success" => false, "message" => "All fields are required."]);
            exit();
        }

        $sql = "INSERT INTO reviews (product_id, user_id, rating, review_text, created_at)
                VALUES (:product_id, :user_id, :rating, :review_text, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':product_id' => $product_id,
            ':user_id' => $user_id,
            ':rating' => $rating,
            ':review_text' => $review_text,
        ]);

        echo json_encode(["success" => true, "message" => "Review submitted successfully."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
