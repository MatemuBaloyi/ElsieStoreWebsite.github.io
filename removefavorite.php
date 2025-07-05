<?php

include 'config.php'; // Ensure your database connection is included

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$userID = $_SESSION['user']['CustomerID']; // Get user ID from session

// Check if product ID is provided
if (!isset($_POST['product_id'])) {
    echo json_encode(["status" => "error", "message" => "Product ID is missing", "received_data" => $_POST]);
    exit;
}

$productID = intval($_POST['product_id']); // Convert to integer for safety

// Remove the product from favorites
$sql = "DELETE FROM favoriteproducts WHERE CustomerID = ? AND ProductID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userID, $productID);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Product removed from favorites"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to remove product"]);
}

$stmt->close();
$conn->close();
?>
