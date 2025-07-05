<?php
require 'config.php';
header('Content-Type: application/json');


// Check if the user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user']['CustomerID'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

$userId = $_SESSION['user']['CustomerID'];

// Query the favourite products for the logged-in user.
// We assume a table "favourites" with columns: CustomerID, ProductID.
$sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, p.images 
        FROM favoriteproducts f 
        INNER JOIN products p ON f.ProductID = p.ProductID 
        WHERE f.CustomerID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$favorites = [];
while ($row = $result->fetch_assoc()) {
    // Optionally: if the images field is stored as a comma-separated string, you may
    // later split it in JavaScript. Here we just pass the string.
    $favorites[] = $row;
}

echo json_encode(['success' => true, 'favorites' => $favorites], JSON_UNESCAPED_SLASHES);

$stmt->close();
$conn->close();
?>
