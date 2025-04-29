<?php
session_start();

// Ensure the user is logged in before submitting a review
if (!isset($_SESSION['user_id'])) {
    header("Location: loginPage.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "pocket_pet_tailor");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reviews with username via JOIN
$stmt = $conn->prepare("
    SELECT u.username, r.rating, r.review_text, r.created_at
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    ORDER BY r.created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Products Reviews</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Londrina+Sketch&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/reviews.css">
</head>

<body>
    <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="true" href="home.php">Shop Online</a>
                </li>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="openModal">Login</a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="reviewsPage.php">Reviews</a>
                    </li>
                    <li class="nav-item">
                        <button onclick="window.location.href='logout.php'" class="btn btn-danger">Logout</button>
                    </li>
                <?php endif; ?>
            </ul>
            <img src="images/Logo.png" width="400px">
        </div>

        <div class="card-body">
            <h2>Leave a Review</h2>
        </div>
    </div>
    <div class="container full-reviews-page">

        <!-- Review Form Section -->
        <div class="form-wrapper">
            <form id="review-form" method="POST" action="submit_review.php">
                <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>

                <label for="rating">Rating (1 to 5)</label>
                <input type="number" id="rating" name="rating" min="1" max="5" required>

                <label for="review">Review</label>
                <textarea id="review" name="review_text" required></textarea>

                <input type="hidden" name="product_id" value="1">

                <button type="submit">Submit Review</button>
            </form>
        </div>

        <!-- Customer Reviews Heading -->
        <h2 class="reviews-heading">Customer Reviews</h2>

        <!-- Review List -->
        <div class="reviews-list">
            <?php if (empty($reviews)): ?>
                <p>No reviews yet. Be the first to leave one!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <h5><?php echo htmlspecialchars($review['username']); ?></h5>
                        <div class="rating">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <?php echo $i < $review['rating'] ? '⭐' : '☆'; ?>
                            <?php endfor; ?>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        <small>Posted on: <?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/reviewsScript.js"></script>
</body>

</html>