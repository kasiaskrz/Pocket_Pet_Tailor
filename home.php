<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pocket Pet Tailor</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Londrina+Sketch&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/loginRegisterModal.css">

</head>

<?php
session_start();
?>

<body>
    <div id="modalOverlay"></div>
    <?php

    $conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch(retrieve) filter options
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
                    <a class="nav-link active" aria-current="true" href="home.php">Shop Online</a>
                </li>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="openModal">Login</a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="reviewsPage.php">Reviews</a>
                    </li>
                    <li class="nav-item">
                        <button onclick="window.location.href='logout.php'" class="btn btn-danger">Logout</button>
                    </li>
                <?php endif; ?>
            </ul>
            <img src="images/Logo.png" width="400px">
        </div>

        <div class="card-body"></div>

        <h2>Shop Online
            <a href="displayCart.php" id="shopping-icon" style="position: relative; display: inline-block;">
                <img src="images/shopping-bag.png" alt="shopping bag" width="90" height="90">
                <span id="cart-count" class="cart-badge">0</span>
            </a>
        </h2>
    </div>

    <div class="container">
        <div class="filtering">
            <form id="product_filter_form" method="POST">
                <!-- Select Animal Type -->
                <select name="animal_type" id="animal_type" onchange="submitFilterForm()">
                    <option value="" disabled selected>Select Animal</option>
                    <option value="">All</option>
                    <?php while ($row = $resultAnimalTypes->fetch_assoc()) {
                        echo "<option value=\"" . htmlspecialchars($row["type_name"]) . "\">" . htmlspecialchars($row["type_name"]) . "</option>";
                    } ?>
                </select>

                <!-- Select Color -->
                <select name="color" id="color" onchange="submitFilterForm()">
                    <option value="" disabled selected>Select Color</option>
                    <option value="">All</option>
                    <?php while ($row = $resultColors->fetch_assoc()) {
                        echo "<option value=\"" . htmlspecialchars($row["color"]) . "\">" . htmlspecialchars($row["color"]) . "</option>";
                    } ?>
                </select>

                <!-- Select Material -->
                <select name="material" id="material" onchange="submitFilterForm()">
                    <option value="" disabled selected>Select Material</option>
                    <option value="">All</option>
                    <?php while ($row = $resultMaterials->fetch_assoc()) {
                        echo "<option value=\"" . htmlspecialchars($row["material"]) . "\">" . htmlspecialchars($row["material"]) . "</option>";
                    } ?>
                </select>

                <!-- Price Range -->
                <input type="number" name="price_from" id="price_from" placeholder="Price from"
                    oninput="submitFilterForm()">
                <input type="number" name="price_to" id="price_to" placeholder="Price to" oninput="submitFilterForm()">
            </form>
        </div>
    </div>
    </div>

    <div id="product-container" class="product-grid">
        <!-- Products are displayed here -->
    </div>


    <?php include 'loginRegisterModal.php'; ?>
    <script src="js/home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/shop.js"></script>


</body>

</html>