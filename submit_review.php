<?php
require 'config.php';  

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    // Store the current page URL for redirection after login
    $_SESSION["redirect_after_login"] = $_SERVER["REQUEST_URI"];
    
    // Redirect to login page
    header("Location: ../pages/loginpage.php");
    exit;
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow cross-origin requests


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["productId"] ?? null;
    $customerId = $_POST["customerId"] ?? null;
    $rating = $_POST["rating"] ?? null;
    $comment = trim($_POST["comment"] ?? "");

    if (!$productId || !$customerId || !$rating || empty($comment)) {
        error_log("Missing fields: productId=$productId, customerId=$customerId, rating=$rating, comment=$comment");
        echo json_encode(["success" => false, "error" => "All fields are required."]);
        exit;
    }

    if (!filter_var($productId, FILTER_VALIDATE_INT) || !filter_var($customerId, FILTER_VALIDATE_INT) || !filter_var($rating, FILTER_VALIDATE_INT)) {
        error_log("Invalid data provided: productId=$productId, customerId=$customerId, rating=$rating");
        echo json_encode(["success" => false, "error" => "Invalid data provided."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reviews (CustomerID, ProductID, Rating, Comment) VALUES (?, ?, ?, ?)");
   
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        echo json_encode(["success" => false, "error" => "Database error."]);
        exit;
    }

    $stmt->bind_param("iiis", $customerId, $productId, $rating, $comment);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Review submitted successfully!"]);
    } else {
        echo json_encode(["success" => false, "error" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}
?>

