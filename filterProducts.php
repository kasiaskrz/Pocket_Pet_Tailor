<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Pocket Pet Tailor</title>
</head>
<body>

<?php
$conn = new mysqli("katarzynaproject", "root", "", "pocket_pet_tailor");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch filters from POST request
$animalType = $_POST['animal_type'] ?? '';
$color = $_POST['color'] ?? '';
$material = $_POST['material'] ?? '';
$priceFrom = $_POST['price_from'] ?? '';
$priceTo = $_POST['price_to'] ?? '';

// Base query
$sql = "SELECT product_id, name, price, color, material, type_name 
        FROM Products 
        JOIN animal_types ON Products.animal_type_id = animal_types.id 
        WHERE 1=1";

// Apply filters only if provided
if (!empty($animalType)) {
    $sql .= " AND animal_types.type_name = '$animalType'";
}
if (!empty($color)) {
    $sql .= " AND Products.color = '$color'";
}
if (!empty($material)) {
    $sql .= " AND Products.material = '$material'";
}
if (!empty($priceFrom)) {
    $sql .= " AND Products.price >= $priceFrom";
}
if (!empty($priceTo)) {
    $sql .= " AND Products.price <= $priceTo";
}

// Execute query
$result = $conn->query($sql);

// Generate HTML for products
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product-card'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>€" . number_format($row['price'], 2) . "</p>";
        echo "<p>Color: " . $row['color'] . "</p>";
        echo "<p>Material: " . $row['material'] . "</p>";
        echo "<button class='add-to-cart' onclick='addtoCart(" . $row['product_id'] . ")'>Add to Cart</button>";
        echo "</div>";
    }
} else {
    echo "<p>No products found.</p>";
}

$conn->close();

?>

</body>
</html>
