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

// Initialize query components
$conditions = [];
$params = [];
$types = '';

// Build the conditions array dynamically based on the filters
if (!empty($animalType)) {
    $conditions[] = "animal_types.type_name = ?";
    $params[] = $animalType;
    $types .= 's'; // string
}
if (!empty($color)) {
    $conditions[] = "Products.color = ?";
    $params[] = $color;
    $types .= 's';
}
if (!empty($material)) {
    $conditions[] = "Products.material = ?";
    $params[] = $material;
    $types .= 's';
}
if (!empty($priceFrom)) {
    $conditions[] = "Products.price >= ?";
    $params[] = (float)$priceFrom;
    $types .= 'd'; // double
}
if (!empty($priceTo)) {
    $conditions[] = "Products.price <= ?";
    $params[] = (float)$priceTo;
    $types .= 'd';
}

// Build the SQL query
$sql = "SELECT product_id, name, price, color, material, type_name, image
        FROM Products
        JOIN animal_types ON Products.animal_type_id = animal_types.id";

// Add conditions to the SQL query
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters to the statement if they exist
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Generate product HTML response
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product-card'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>€" . number_format($row['price'], 2) . "</p>";
        echo "<p>Color: " . htmlspecialchars($row['color']) . "</p>";
        echo "<p>Material: " . htmlspecialchars($row['material']) . "</p>";

        if (!empty($row['image'])) {
            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' class='product-image' width='150px'><br>";
        } else {
            echo "<img src='images/products/1.jpg' alt='No image available' class='product-image' width='150px'>";
        }

        echo "<button class='add-to-cart' onclick='addtoCart(" . (int)$row['product_id'] . ")'>Add to Cart</button>";
        echo "</div>";
    }
} else {
    echo "<p>No products found.</p>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
