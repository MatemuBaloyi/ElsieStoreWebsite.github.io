<?php

require 'config.php'; // Ensure config.php has correct DB settings



// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Debugging: Check if JSON is received
if ($data === null) {
    echo json_encode(["success" => false, "error" => "Invalid JSON data"]);
    exit;
}

// Check if session user is set, else assign default ID
$user_id = $_SESSION['user']['CustomerID'] ?? 1; // Temporary default for testing

foreach ($data as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];

    // Check if item already exists in the cart
    $checkQuery = "SELECT Quantity FROM cart WHERE CustomerID = ? AND ProductID = ? ";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item exists, update quantity
        $row = $result->fetch_assoc();
        $newQuantity = $row['Quantity'] + $quantity;

        $updateQuery = "UPDATE cart SET Quantity = ? WHERE CustomerID = ? AND ProductID = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("iii", $newQuantity, $user_id, $product_id);
        $updateStmt->execute();
    } else {
        // Insert new item
        $insertQuery = "INSERT INTO cart (CustomerID, ProductID, Quantity) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insertStmt->execute();
    }
}

// Send success response
echo json_encode(["success" => true]);
?>

