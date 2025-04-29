<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pocket Pet Tailor</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/checkout.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_checkout'])) {
    $user_id = $_SESSION['user_id'];

    $conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Clear the user's shopping cart
    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirect to order success page
    header("Location: order_success.php");
    exit();
}
?>

<body>
    <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="home.php">Shop Online</a>
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
            <img src="images/Logo.png" width="400px" >
        </div>

        <h2>Checkout</h2>
    </div>

    <form id="checkout-order-form" method="POST" action="">
        <h2>Your Details</h2>
        <div id="checkout-container">
            <fieldset id="fieldset-billing">
                <legend>Billing</legend>
                <div>
                    <label for="nname">Name</label>
                    <input type="text" id="nname" name="nname" minlength="2" required>
                    <small class="error-message" id="error-nname"></small>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    <small class="error-message" id="error-email"></small>
                </div>
                <div>
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" minlength="2" required>
                    <small class="error-message" id="error-city"></small>
                </div>
                <div>
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" minlength="5" required>
                    <small class="error-message" id="error-address"></small>
                </div>
                <div>
                    <label for="zip">ZIP Code</label>
                    <input type="text" id="zip" name="zip" pattern="[A-Za-z0-9 ]{3,10}" required>
                    <small class="error-message" id="error-zip"></small>
                </div>
                <div>
                    <label for="country">Country</label>
                    <select name="country" id="country" required></select>
                    <small class="error-message" id="error-country"></small>
                </div>
            </fieldset>

            <fieldset id="fieldset-shipping">
                <div id="shipping-same">Same as Billing
                    <input type="checkbox" id="same-as-billing">
                </div>
                <legend>Shipping</legend>
                <div>
                    <label for="sname">Name</label>
                    <input type="text" id="sname" name="sname" minlength="2" required>
                    <small class="error-message" id="error-sname"></small>
                </div>
                <div>
                    <label for="semail">Email</label>
                    <input type="email" id="semail" name="semail" required>
                    <small class="error-message" id="error-semail"></small>
                </div>
                <div>
                    <label for="scity">City</label>
                    <input type="text" id="scity" name="scity" minlength="2" required>
                    <small class="error-message" id="error-scity"></small>
                </div>
                <div>
                    <label for="saddress">Address</label>
                    <input type="text" id="saddress" name="saddress" minlength="5" required>
                    <small class="error-message" id="error-saddress"></small>
                </div>
                <div>
                    <label for="szip">ZIP Code</label>
                    <input type="text" id="szip" name="szip" pattern="[A-Za-z0-9 ]{3,10}" required>
                    <small class="error-message" id="error-szip"></small>
                </div>
                <div>
                    <label for="scountry">Country</label>
                    <select name="scountry" id="scountry" required></select>
                    <small class="error-message" id="error-scountry"></small>
                </div>
            </fieldset>
        </div>

        <fieldset id="payment">
            <legend>Payment</legend>
            <div class="col-50">
                <div class="icon-container">
                    <i class="fa fa-cc-visa" style="color:navy;"></i>
                    <i class="fa fa-cc-amex" style="color:blue;"></i>
                    <i class="fa fa-cc-mastercard" style="color:red;"></i>
                    <i class="fa fa-cc-discover" style="color:orange;"></i>
                </div>
                <label for="cname">Name on Card</label>
                <input type="text" id="cname" name="cname" minlength="2" required>
                <small class="error-message" id="error-cname"></small>

                <label for="ccnum">Credit Card Number</label>
                <input type="text" id="ccnum" name="ccnum" pattern="^\d{16}$" required>
                <small class="error-message" id="error-ccnum"></small>

                <label for="expmonth">Exp Month</label>
                <select name="expmonth" id="expmonth" required></select>
                <small class="error-message" id="error-expmonth"></small>

                <div class="row">
                    <div class="col-50">
                        <label for="expyear">Exp Year</label>
                        <select name="expyear" id="expyear" required></select>
                        <small class="error-message" id="error-expyear"></small>
                    </div>
                    <div class="col-50">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" pattern="^\d{3,4}$" required>
                        <small class="error-message" id="error-cvv"></small>
                    </div>
                </div>
            </div>
        </fieldset>

        <p>
            <button type="submit" id="pay-now-btn" class="checkout" disabled>Pay Now</button>
            <button type="button" onclick="clearCheckoutForms()" class="removeAll">Clear Form</button>
        </p>
    </form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/shop.js"></script>
    <script src="js/checkout.js"></script>

</body>

</html>