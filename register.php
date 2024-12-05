<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Account</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">

</head>

<body>
    <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" aria-current="true" href="home.php">Shop Online</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Create Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="loginPage.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reviewsPage.php">Reviews</a>
                </li>
            </ul>
            <img src="images/Logo.png" width="400px">
        </div>
        <div class="card-body">


        </div>
        <h2>Register Your Account</h2>
    </div>
    <div class="form-container">
        <form id="userForm">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required><br>

            <label for="email">Email</label>
            <input type="text" id="email" name="email" required><br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required><br>

            <div id="buttons">
                <label>&nbsp;</label>
                <input type="submit" value="Register"><br>
            </div>
            <div id="response"></div>

    </div>
    </form>


    <script>
        $(document).ready(function () {
            $('#userForm').on('submit', function (event) {
                event.preventDefault(); // Prevent the form from submitting the traditional way

                $.ajax({
                    type: 'POST',
                    url: 'createUser.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#response').html(response); // Update the response div with the result
                    },
                    error: function (xhr, status, error) {
                        $('#response').html('Error: ' + error); // Display error message
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>