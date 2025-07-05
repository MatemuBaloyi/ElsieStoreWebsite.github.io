<?php
require 'config.php';
header('Content-Type: application/json');


if (!isset($_SESSION['user']['CustomerID'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$customerId = $_SESSION['user']['CustomerID'];
$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['productId'];
$action = $data['action'];

if (!$productId || !in_array($action, ['add', 'remove'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

if ($action === "add") {
    $stmt = $conn->prepare("INSERT INTO favoriteproducts (CustomerID, ProductID) VALUES (?, ?) ON DUPLICATE KEY UPDATE ProductID=ProductID");
    $stmt->bind_param("ii", $customerId, $productId);
} else {
    $stmt = $conn->prepare("DELETE FROM favoriteproducts WHERE CustomerID = ? AND ProductID = ?");
    $stmt->bind_param("ii", $customerId, $productId);
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Favorite updated"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database update failed"]);
}

$stmt->close();
$conn->close();
?>
