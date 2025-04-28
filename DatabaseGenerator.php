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
        price DECIMAL(10, 2) NOT NULL,
        stock INT NOT NULL,
        animal_type_id INT,
        category_id INT,
        color VARCHAR(30),
        material VARCHAR(50),
        image VARCHAR(255) NOT NULL,
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
$productsSql = "INSERT INTO Products (name, price, stock, animal_type_id, category_id, color, material, image) VALUES 
('Whisker-Warming Rat Hoodie', 18.99, 30, 1, 1, 'Red', 'Cotton', 'images/products/1.jpg'),
('Hamster Fuzz Sweater', 15.49, 25, 2, 2, 'Pink', 'Wool', 'images/products/2.jpg'),
('Bunny Hop Coat', 28.99, 40, 3, 3, 'White', 'Polyester', 'images/products/3.jpg'),
('Guinea Pig Adventure Boots', 22.49, 50, 4, 4, 'Black', 'Leather', 'images/products/4.jpg'),
('Chinchilla Chic Beanie', 14.99, 35, 5, 5, 'Gray', 'Flannel', 'images/products/5.jpg'),
('Ferret Pajama Party Set', 21.99, 60, 6, 6, 'Purple', 'Cotton', 'images/products/6.jpg'),
('Sugar Glider Stormproof Raincoat', 24.99, 30, 7, 7, 'Blue', 'Polyester', 'images/products/7.jpg'),
('Ratty Rascal T-shirt', 13.99, 50, 1, 8, 'Green', 'Cotton', 'images/products/8.jpg'),
('Hammy the Hamster Scarf', 11.99, 40, 2, 9, 'Yellow', 'Wool', 'images/products/9.jpg'),
('Rabbit Hop & Run Socks', 9.99, 70, 3, 10, 'White', 'Flannel', 'images/products/10.jpg'),
('Guinea Pig Cozy Coat', 26.99, 45, 4, 3, 'Pink', 'Polyester', 'images/products/11.jpg'),
('Chinchilla Dream Pajamas', 19.99, 30, 5, 6, 'Pink', 'Cotton', 'images/products/12.jpg'),
('Ferret Explorer Hat', 13.49, 50, 6, 5, 'Green', 'Flannel', 'images/products/13.jpg'),
('Sugar Glider Featherlight Hoodie', 17.99, 45, 7, 1, 'Pink', 'Cotton', 'images/products/14.jpg'),
('Ratty Rocket Launch T-shirt', 15.49, 40, 1, 8, 'Blue', 'Cotton', 'images/products/15.jpg'),
('Hamster Happy Trails Sweater', 18.49, 55, 2, 2, 'Yellow', 'Wool', 'images/products/16.jpg'),
('Bunny Blossom Coat', 27.99, 30, 3, 3, 'Pink', 'Polyester', 'images/products/17.jpg'),
('Guinea Pig Power Boots', 23.99, 25, 4, 4, 'Red', 'Leather', 'images/products/18.jpg'),
('Chinchilla Sunshine Hat', 14.49, 40, 5, 5, 'Yellow', 'Flannel', 'images/products/19.jpg'),
('Ferret Funzone Pyjamas', 20.99, 35, 6, 6, 'Blue', 'Cotton', 'images/products/20.jpg'),
('Sugar Glider Speedster Raincoat', 25.99, 50, 7, 7, 'Blue', 'Polyester', 'images/products/21.jpg'),
('Ratty Daydream T-shirt', 16.99, 100, 1, 8, 'Black', 'Cotton', 'images/products/22.jpg'),
('Hammy Sweetheart Sweater', 16.49, 70, 2, 2, 'Pink', 'Wool', 'images/products/23.jpg'),
('Rabbit Lucky Hop Coat', 28.49, 40, 3, 3, 'Green', 'Polyester', 'images/products/24.jpg'),
('Guinea Pig Snuggle Boots', 22.49, 60, 4, 4, 'Black', 'Leather', 'images/products/25.jpg'),
('Chinchilla Wildflower Hat', 13.99, 50, 5, 5, 'Pink', 'Flannel', 'images/products/26.jpg'),
('Ferret Cuddle Pajamas', 21.49, 30, 6, 6, 'Purple', 'Cotton', 'images/products/27.jpg'),
('Sugar Glider Superfly Raincoat', 24.49, 45, 7, 7, 'Yellow', 'Polyester', 'images/products/28.jpg'),
('Ratty Rocket Scarf', 11.99, 80, 1, 9, 'Red', 'Wool', 'images/products/29.jpg'),
('Hammy the Cozy Sweater', 19.99, 60, 2, 2, 'Pink', 'Wool', 'images/products/30.jpg'),
('Rabbit Forest Explorer Coat', 30.99, 35, 3, 3, 'Green', 'Polyester', 'images/products/31.jpg'),
('Guinea Pig Trailblazer Boots', 26.49, 50, 4, 4, 'Red', 'Leather', 'images/products/32.jpg'),
('Chinchilla Moonlit Beanie', 13.49, 60, 5, 5, 'Gray', 'Flannel', 'images/products/33.jpg'),
('Ratty Racer Hoodie', 19.99, 40, 1, 1, 'Red', 'Cotton', 'images/products/34.jpg'),
('Hamster Snuggle Sweater', 17.49, 45, 2, 2, 'Pink', 'Wool', 'images/products/35.jpg'),
('Bunny Love Coat', 29.49, 30, 3, 3, 'Pink', 'Polyester', 'images/products/36.jpg'),
('Guinea Pig Winter Boots', 24.99, 60, 4, 4, 'Black', 'Leather', 'images/products/37.jpg'),
('Chinchilla Feather Hat', 16.99, 35, 5, 5, 'Blue', 'Flannel', 'images/products/38.jpg'),
('Ferret Midnight Pyjamas', 23.49, 50, 6, 6, 'Black', 'Cotton', 'images/products/39.jpg'),
('Sugar Glider Sky Raincoat', 27.99, 40, 7, 7, 'Blue', 'Polyester', 'images/products/40.jpg'),
('Ratty Racer T-shirt', 14.99, 75, 1, 8, 'Yellow', 'Cotton', 'images/products/41.jpg'),
('Hammy Winter Scarf', 13.49, 55, 2, 9, 'White', 'Wool', 'images/products/42.jpg'),
('Rabbit Summer Socks', 11.49, 80, 3, 10, 'Green', 'Flannel', 'images/products/43.jpg'),
('Guinea Pig Sunshine Coat', 26.99, 50, 4, 3, 'Yellow', 'Polyester', 'images/products/44.jpg'),
('Chinchilla Cloud Pajamas', 20.49, 45, 5, 6, 'Gray', 'Cotton', 'images/products/45.jpg'),
('Ferret Comfy Hat', 15.99, 50, 6, 5, 'Purple', 'Flannel', 'images/products/46.jpg'),
('Sugar Glider Swift Hoodie', 21.49, 60, 7, 1, 'Pink', 'Cotton', 'images/products/47.jpg'),
('Ratty Charm T-shirt', 13.49, 70, 1, 8, 'Blue', 'Cotton', 'images/products/48.jpg'),
('Hamster Woolen Sweater', 18.99, 55, 2, 2, 'Red', 'Wool', 'images/products/49.jpg'),
('Bunny Cozy Winter Coat', 30.49, 40, 3, 3, 'Gray', 'Polyester', 'images/products/50.jpg'),
('Guinea Pig Cozy Winter Boots', 25.49, 50, 4, 4, 'Black', 'Leather', 'images/products/51.jpg'),
('Chinchilla Sparkle Hat', 14.49, 60, 5, 5, 'Gray', 'Flannel', 'images/products/52.jpg'),
('Ferret Daydream Pajamas', 22.99, 30, 6, 6, 'Purple', 'Cotton', 'images/products/53.jpg');";


populateTableIfEmpty($conn, $checkProducts, $productsSql, 'Products');

$conn->close();
?>