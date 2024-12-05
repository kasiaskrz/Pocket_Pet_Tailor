<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Login to Pocket Pet Tailor!</title>
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


    <script>
        function loginUser() {
            event.preventDefault();
            const formData = new FormData(document.getElementById('login'));

            fetch('login.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('login_response').innerHTML = data.message;
                        alert("Login successfull!");
                        window.location.href = "home.php"; // Redirect to shop page
                    } else {
                        document.getElementById('login_response').innerHTML = data.message;
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    </script>

    <?php
    // Create connection
    $conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
                    <a class="nav-link active" href="#">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviewsPage.php">Reviews</a>
                </li>
                
            </ul>
            <img src="images/Logo.png" width="400px">
        </div>
        <div class="card-body">


        </div>
        <h2>Log In</h2>
    </div>

    <div class="container">
        <div class="box" id="login_box">
            <form id="login" onsubmit="loginUser()">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required><br>
                <div id="buttons">
                    <label>&nbsp;</label>
                    <input type="submit" value="Login"><br>
                </div>
            </form>
            <div class="box" id="login_response"><br>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="box" id="sock_response">
        </div>
        
    </div>

    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    </html>