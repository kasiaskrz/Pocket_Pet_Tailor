<?php
$servername = "katarzynaproject";
$username = "root"; 
$password = ""; 
$dbname = "pocket_pet_tailor";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);


// Create tables
$tables = [
    "Users" => "CREATE TABLE IF NOT EXISTS Users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "Animal_Types" => "CREATE TABLE IF NOT EXISTS animal_types (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type_name VARCHAR(50) NOT NULL
    )",

    "Clothes_categories" => "CREATE TABLE IF NOT EXISTS Clothes_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(50) NOT NULL
    )",

    "Products" => "CREATE TABLE IF NOT EXISTS Products (
        product_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        stock INT NOT NULL,
        animal_type_id INT,
        category_id INT,
        color VARCHAR(30),
        material VARCHAR(50),
        FOREIGN KEY (animal_type_id) REFERENCES Animal_Types(id),
        FOREIGN KEY (category_id) REFERENCES Categories(id)
    )",

    "Orders" => "CREATE TABLE IF NOT EXISTS Orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('completed', 'pending', 'cancelled') DEFAULT 'pending',
        FOREIGN KEY (user_id) REFERENCES Users(id)
    )",

    "Order_Items" => "CREATE TABLE IF NOT EXISTS Order_Items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES Orders(id),
        FOREIGN KEY (product_id) REFERENCES Products(id)
    )",

    "Reviews" => "CREATE TABLE IF NOT EXISTS Reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        user_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
        review_text TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES Products(id),
        FOREIGN KEY (user_id) REFERENCES Users(id)
    )",

    "Shopping_cart" => "CREATE TABLE IF NOT EXISTS Shopping_cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (product_id) REFERENCES Products(id)
    )"
];

// Execute table creation queries
foreach ($tables as $tableName => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table $tableName created successfully.<br>";
    } else {
        echo "Error creating table $tableName: " . $conn->error . "<br>";
    }
}

function populateTableIfEmpty($conn, $checkSql, $insertSql, $tableName) {
    $result = $conn->query($checkSql);
    if ($result && $result->num_rows > 0) {
        echo "$tableName table already populated.<br>";
    } else {
        if ($conn->query($insertSql) === TRUE) {
            echo "Data inserted into $tableName successfully.<br>";
        } else {
            echo "Error inserting into $tableName: " . $conn->error . "<br>";
        }
    }
}


// Populate Animal_Types table
$checkAnimalTypes = "SELECT * FROM animal_types LIMIT 1;";
$animalTypesSql = "INSERT INTO animal_types (type_name) VALUES
('Rat'),
('Hamster'),
('Rabbit'),
('Guinea Pig'),
('Chinchilla'),
('Ferret'),
('Sugar Glider');";

populateTableIfEmpty($conn, $checkAnimalTypes, $animalTypesSql, 'Animal_Types');

// Populate Clothes_Categories table
$checkCategories = "SELECT * FROM clothes_categories LIMIT 1;";
$categoriesSql = "INSERT INTO clothes_categories (category_name) VALUES
('Hoodie'),
('Sweater'),
('Coat'),
('Boots'),
('Hat'),
('Pyjama'),
('Raincoat'),
('T-shirt'),
('Scarves'),
('Socks');";
populateTableIfEmpty($conn, $checkCategories, $categoriesSql, 'Clothes_Categories');

// Populate Products table
$checkProducts = "SELECT * FROM Products LIMIT 1;";
$productsSql = "INSERT INTO Products (name, price, stock, animal_type_id, category_id, color, material) VALUES 
('Whisker-Warming Rat Hoodie', 18.99, 30, 1, 1, 'Red', 'Cotton'),
('Hamster Fuzz Sweater', 15.49, 25, 2, 2, 'Pink', 'Wool'),
('Bunny Hop Coat', 28.99, 40, 3, 3, 'White', 'Polyester'),
('Guinea Pig Adventure Boots', 22.49, 50, 4, 4, 'Black', 'Leather'),
('Chinchilla Chic Beanie', 14.99, 35, 5, 5, 'Gray', 'Flannel'),
('Ferret Pajama Party Set', 21.99, 60, 6, 6, 'Purple', 'Cotton'),
('Sugar Glider Stormproof Raincoat', 24.99, 30, 7, 7, 'Blue', 'Polyester'),
('Ratty Rascal T-shirt', 13.99, 50, 1, 8, 'Green', 'Cotton'),
('Hammy the Hamster Scarf', 11.99, 40, 2, 9, 'Yellow', 'Wool'),
('Rabbit Hop & Run Socks', 9.99, 70, 3, 10, 'White', 'Flannel'),
('Guinea Pig Cozy Coat', 26.99, 45, 4, 3, 'Pink', 'Polyester'),
('Chinchilla Dream Pajamas', 19.99, 30, 5, 6, 'Pink', 'Cotton'),
('Ferret Explorer Hat', 13.49, 50, 6, 5, 'Green', 'Flannel'),
('Sugar Glider Featherlight Hoodie', 17.99, 45, 7, 1, 'Pink', 'Cotton'),
('Ratty Rocket Launch T-shirt', 15.49, 40, 1, 8, 'Blue', 'Cotton'),
('Hamster Happy Trails Sweater', 18.49, 55, 2, 2, 'Yellow', 'Wool'),
('Bunny Blossom Coat', 27.99, 30, 3, 3, 'Pink', 'Polyester'),
('Guinea Pig Power Boots', 23.99, 25, 4, 4, 'Red', 'Leather'),
('Chinchilla Sunshine Hat', 14.49, 40, 5, 5, 'Yellow', 'Flannel'),
('Ferret Funzone Pyjamas', 20.99, 35, 6, 6, 'Blue', 'Cotton'),
('Sugar Glider Speedster Raincoat', 25.99, 50, 7, 7, 'Blue', 'Polyester'),
('Ratty Daydream T-shirt', 16.99, 100, 1, 8, 'Black', 'Cotton'),
('Hammy Sweetheart Sweater', 16.49, 70, 2, 2, 'Pink', 'Wool'),
('Rabbit Lucky Hop Coat', 28.49, 40, 3, 3, 'Green', 'Polyester'),
('Guinea Pig Snuggle Boots', 22.49, 60, 4, 4, 'Black', 'Leather'),
('Chinchilla Wildflower Hat', 13.99, 50, 5, 5, 'Pink', 'Flannel'),
('Ferret Cuddle Pajamas', 21.49, 30, 6, 6, 'Purple', 'Cotton'),
('Sugar Glider Superfly Raincoat', 24.49, 45, 7, 7, 'Yellow', 'Polyester'),
('Ratty Rocket Scarf', 11.99, 80, 1, 9, 'Red', 'Wool'),
('Hammy the Cozy Sweater', 19.99, 60, 2, 2, 'Pink', 'Wool'),
('Rabbit Forest Explorer Coat', 30.99, 35, 3, 3, 'Green', 'Polyester'),
('Guinea Pig Trailblazer Boots', 26.49, 50, 4, 4, 'Red', 'Leather'),
('Chinchilla Moonlit Beanie', 13.49, 60, 5, 5, 'Gray', 'Flannel'),
('Ratty Racer Hoodie', 19.99, 40, 1, 1, 'Red', 'Cotton'),
('Hamster Snuggle Sweater', 17.49, 45, 2, 2, 'Pink', 'Wool'),
('Bunny Love Coat', 29.49, 30, 3, 3, 'Pink', 'Polyester'),
('Guinea Pig Winter Boots', 24.99, 60, 4, 4, 'Black', 'Leather'),
('Chinchilla Feather Hat', 16.99, 35, 5, 5, 'Blue', 'Flannel'),
('Ferret Midnight Pyjamas', 23.49, 50, 6, 6, 'Black', 'Cotton'),
('Sugar Glider Sky Raincoat', 27.99, 40, 7, 7, 'Blue', 'Polyester'),
('Ratty Racer T-shirt', 14.99, 75, 1, 8, 'Yellow', 'Cotton'),
('Hammy Winter Scarf', 13.49, 55, 2, 9, 'White', 'Wool'),
('Rabbit Summer Socks', 11.49, 80, 3, 10, 'Green', 'Flannel'),
('Guinea Pig Sunshine Coat', 26.99, 50, 4, 3, 'Yellow', 'Polyester'),
('Chinchilla Cloud Pajamas', 20.49, 45, 5, 6, 'Gray', 'Cotton'),
('Ferret Comfy Hat', 15.99, 50, 6, 5, 'Purple', 'Flannel'),
('Sugar Glider Swift Hoodie', 21.49, 60, 7, 1, 'Pink', 'Cotton'),
('Ratty Charm T-shirt', 13.49, 70, 1, 8, 'Blue', 'Cotton'),
('Hamster Woolen Sweater', 18.99, 55, 2, 2, 'Red', 'Wool'),
('Bunny Cozy Winter Coat', 30.49, 40, 3, 3, 'Gray', 'Polyester'),
('Guinea Pig Cozy Winter Boots', 25.49, 50, 4, 4, 'Black', 'Leather'),
('Chinchilla Sparkle Hat', 14.49, 60, 5, 5, 'Gray', 'Flannel'),
('Ferret Daydream Pajamas', 22.99, 30, 6, 6, 'Purple', 'Cotton');";



populateTableIfEmpty($conn, $checkProducts, $productsSql, 'Products');

$conn->close();
?>