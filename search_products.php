<?php
require 'config.php';

header('Content-Type: application/json');

$search = $_GET['query'] ?? '';

if (empty($search)) {
    echo json_encode([]);
    exit;
}

$searchTerm = "%" . $conn->real_escape_string($search) . "%";

$stmt = $conn->prepare("SELECT ProductID, ProductName, Price, images FROM products WHERE ProductName LIKE ?");
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
$stmt->close();
$conn->close();
?>
