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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>
<?php
session_start();

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
                    <a class="nav-link active" aria-current="true" href="#">Shop Online</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Create Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="loginPage.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviewsPage.php">Reviews</a>
                </li>
                <li>
                    <button onclick="window.location.href='logout.php'" class="btn btn-danger">Logout</button>
                </li>
            </ul>
            <img src="images/Logo.png" width="400px">
        </div>
        <div class="card-body">


        </div>
        <h2>Shop Online<a href="displayCart.php" id="shopping-icon"><img src="images/shopping-bag.png" alt="shopping bag" width="90" height="90"></a></h2>
    </div>

    <form id="product_filter_form" action="filterProducts.php" method="POST">
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
        <input type="number" name="price_from" id="price_from" placeholder="Price from" oninput="submitFilterForm()">
        <input type="number" name="price_to" id="price_to" placeholder="Price to" oninput="submitFilterForm()">
    </form>
    </div>
    </div>

    <div id="product-container" class="product-grid">
        <!-- Products are displayed here -->
    </div>

    <script>
        function submitFilterForm() {
            const form = document.getElementById('product_filter_form');
            const formData = new FormData(form);

            fetch('filterProducts.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('product-container').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
        function addtoCart(productId) {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${productId}`
        })
        .then(response => response.text())
        .then(data => alert(data))
        .catch(error => console.error('Error:', error));
    }


    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/shop.js"></script>


</body>

</html>