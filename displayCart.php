<!DOCTYPE html>
<html lang="en">

<head>
    <title>Your Cart</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/displayCart.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php
    session_start();
    $loggedIn = isset($_SESSION['user_id']);
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

        <h2>Your Cart
            <a href="displayCart.php" id="shopping-icon" style="position: relative; display: inline-block;">
                <img src="images/shopping-bag.png" alt="shopping bag" width="90" height="90">
                <span id="cart-count" class="cart-badge">0</span>
            </a>
        </h2>
    </div>

    <br>

    <?php if (!$loggedIn): ?>
        <p style="margin: 30px; color: #c58d8e;">Login to your account to place an order!</p>
    <?php else: ?>

        <?php
        $conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_id = $_SESSION['user_id'];

        // Handle product removal
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
            $product_id = intval($_POST['remove_product_id']);
            if ($product_id > 0) {
                $remove_stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ? AND product_id = ?");
                $remove_stmt->bind_param("ii", $user_id, $product_id);
                $remove_stmt->execute();
                $remove_stmt->close();
            }
        }

        // Fetch cart details
        $stmt = $conn->prepare("SELECT p.product_id, p.name, p.price, p.image, c.quantity 
            FROM shopping_cart c 
            JOIN Products p ON c.product_id = p.product_id 
            WHERE c.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<a href='home.php' id='continue-shopping-link'>Continue Shopping</a>";

        if ($result->num_rows === 0) {
            echo "<p style='margin: 30px; color: #c58d8e;'>Your cart is empty.</p>";
        } else {
            echo "<table>";
            echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

            $order_total = 0;
            while ($row = $result->fetch_assoc()) {
                if (isset($row['product_id'])) {
                    $product_total = $row['price'] * $row['quantity'];
                    $order_total += $product_total;

                    echo "<tr>";
                    echo "<td>";
                    if (!empty($row['image'])) {
                        echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' class='product-image'><br>";
                    }
                    echo htmlspecialchars($row['name']);
                    echo "</td>";
                    echo "<td>€" . htmlspecialchars(number_format($row['price'], 2)) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>€" . htmlspecialchars(number_format($product_total, 2)) . "</td>";
                    echo "<td>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='remove_product_id' value='" . $row['product_id'] . "'>
                        <button type='submit' class='remove-btn'>Remove</button>
                    </form>
                </td>";
                    echo "</tr>";
                }
            }
            echo "</table><br>";

            $link_address1 = 'checkout.php';
            echo "<h3 class='total-order'><b>Total Amount:</b> €" . number_format($order_total, 2) . "</h3>";

            echo "<div class='checkout-wrapper'>
                    <form method='GET' action='$link_address1'>
                        <button type='submit' class='checkout-btn'>Checkout</button>
                    </form>
                </div>";
        }

        $stmt->close();
        $conn->close();
        ?>

    <?php endif; ?>

    <?php include 'loginRegisterModal.php'; ?>

    <script src="js/home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/shop.js"></script>
</body>

</html>
