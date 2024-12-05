<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit a review.";
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
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; 
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    $product_id = $_POST['product_id'];
    $username = $_POST['username']; 


    // Validate inputs
    if (empty($rating) || empty($review_text) || empty($product_id)) {
        echo "All fields are required.";
        exit();
    }

    try {
        // Insert review into the database
        $sql = "INSERT INTO reviews (product_id, user_id, rating, review_text, created_at)
                VALUES (:product_id, :user_id, :rating, :review_text, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':product_id' => $product_id,
            ':user_id' => $user_id,
            ':rating' => $rating,
            ':review_text' => $review_text,
        ]);

        // Retrieve the last inserted review to send back as a response
        $lastInsertId = $pdo->lastInsertId();
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $lastInsertId]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        // Send success response
        echo "Review submitted successfully.";
    } catch (PDOException $e) {
        // Handle potential errors
        echo "Error: " . $e->getMessage();
    }
}
?>
