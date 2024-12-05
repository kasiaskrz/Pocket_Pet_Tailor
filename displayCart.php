<!DOCTYPE html>
<html lang="en">

<head>
    <title>Your Cart</title>
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

<body>
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
        <h2>Your Cart<a href="displayCart.php" id="shopping-icon"><img src="images/shopping-bag.png" alt="shopping bag"
                    width="90" height="90"></a></h2>
    </div>
    <br>
    <?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        echo "Login to your account to place an order!";
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
    $stmt = $conn->prepare("SELECT p.product_id, p.name, p.price, c.quantity FROM shopping_cart c JOIN Products p ON c.product_id = p.product_id WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<a href='home.php' id='continue-shopping-link'>Continue Shopping</a>";

    
    // Check if cart is empty
    if ($result->num_rows === 0) {
        echo "<p style='margin: 30px; color: #c58d8e;'>Your cart is empty.</p>";
    } else {
        echo "<table>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

        $order_total = 0; // Variable to hold the total order cost
    
        while ($row = $result->fetch_assoc()) {
            // Check if product_id is set in the row
            if (isset($row['product_id'])) {
                $product_total = $row['price'] * $row['quantity']; // Calculate total for each product
                $order_total += $product_total; // Add product total to the overall order total

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($product_total) . "</td>";
                echo "<td>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='remove_product_id' value='" . $row['product_id'] . "'>
                        <button type='submit' class='remove-btn'>Remove</button>
                    </form>
                </td>";
                echo "</tr>";
            } else {
                // Handle case where product_id is missing in cart row
                echo "<tr><td colspan='5' style='color: red;'>Error: Product ID is missing for a cart item.</td></tr>";
            }
        }

        echo "</table>";
        echo "<br>";
        echo "<h3 style='margin-left: 20px;'>Total Order: €" . number_format($order_total, 2) . "</h3>";
        // Checkout Button
        echo "<form method='POST'>
<button type='submit' name='checkout' class='checkout-btn'>Checkout</button>
</form>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Insert the order into the `orders` table
    $order_date = date("Y-m-d H:i:s"); // Current date and time

    // Calculate the total amount of the order
    $totalAmount = 0; // to calculate the total order amount
    $cart_stmt = $conn->prepare("SELECT p.product_id, p.price, c.quantity 
                                 FROM shopping_cart c 
                                 JOIN Products p ON c.product_id = p.product_id 
                                 WHERE c.user_id = ?");
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    // Calculate total amount
    while ($cart_row = $cart_result->fetch_assoc()) {
        if (isset($cart_row['price'], $cart_row['quantity'])) {
            $totalAmount += $cart_row['price'] * $cart_row['quantity'];
        } else {
            echo "Error: Missing price or quantity for an item in the cart.";
            exit;
        }
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $order_stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, ?)");
        $order_stmt->bind_param("ids", $user_id, $totalAmount, $order_date);
        $order_stmt->execute();

        // Get the last inserted order ID
        $order_id = $conn->insert_id;

        // Insert into order_items table
        $cart_stmt->execute();  // Re-run the query since we already fetched the result above
        $cart_result = $cart_stmt->get_result();
        while ($cart_row = $cart_result->fetch_assoc()) {
                    if (isset($cart_row['product_id'], $cart_row['quantity'], $cart_row['price'])) {
                        $product_id = $cart_row['product_id'];
                        $quantity = $cart_row['quantity'];
                        $price = $cart_row['price'];

                        // Insert each cart item into order_items
                        $order_items_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                        $order_items_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                        $order_items_stmt->execute();
                    } else {
                        echo "Error: Missing data for order item.";
                        exit;
                    }
                }

        // Commit the transaction
        $conn->commit();

        // Clear the cart after checkout
        $clear_cart_stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
        $clear_cart_stmt->bind_param("i", $user_id);
        $clear_cart_stmt->execute();

        // Redirect to success page
        header("Location: order_success.php");
        exit;


    } catch (Exception $e) {
        $conn->rollback(); // Rollback the transaction in case of error
        echo "Error: " . $e->getMessage();
    }

}

    }
    $stmt->close();
    $conn->close();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/shop.js"></script>


</body>

</html>