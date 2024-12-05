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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in; redirect to login page
    header("Location: loginPage.php");
    exit();
}

// User is logged in; retrieve their ID
$user_id = $_SESSION['user_id'];

// Optional: Access session data
?>


<body>
    <?php
    // Create connection
    $conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch filter options
    $sqlAnimalTypes = "SELECT DISTINCT id, type_name FROM animal_types ORDER BY type_name";
    $resultAnimalTypes = $conn->query($sqlAnimalTypes);

    $sqlColors = "SELECT DISTINCT color FROM Products ORDER BY color";
    $resultColors = $conn->query($sqlColors);

    $sqlMaterials = "SELECT DISTINCT material FROM Products ORDER BY material";
    $resultMaterials = $conn->query($sqlMaterials);

    $conn->close();
    ?>
    <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" aria-current="true" href="home.php">Shop Online</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Create Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="loginPage.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="reviewsPage.php">Reviews</a>
                </li>
                <li>
                    <button onclick="window.location.href='logout.php'" class="btn btn-danger">Logout</button>
                </li>
            </ul>
            <img src="images/Logo.png" width="400px">
        </div>
        <div class="card-body">


        </div>
        <h2>Leave a Review</h2>
    </div>
    <div class="container">

        <!-- Review Form -->
        <form id="review-form" method="POST" action="submit_review.php">
            <label for="name">Username</label>
            <input type="text" id="name" name="username" required>

            <label for="rating">Rating (1 to 5)</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>

            <label for="review">Review</label>
            <textarea id="review" name="review_text" required></textarea>

            <!-- Hidden product ID -->
            <input type="hidden" name="product_id" value="1">

            <button type="submit">Submit Review</button>
        </form>
        <!-- Reviews Display Section -->
        <div id="reviews">
            <div id="reviews-list">
                <!-- Reviews will be dynamically added here -->
            </div>
        </div>
    </div>


</body>
<script src="js/reviewsScript.js"></script>

</html>