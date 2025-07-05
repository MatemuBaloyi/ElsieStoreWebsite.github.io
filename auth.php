<?php

require 'config.php';


$response = ["status" => "active"]; // Default response

if (isset($_SESSION["user"])) {
    $userId = $_SESSION["user"]["CustomerID"];
    
    // Check if session is still active
    $checkSessionQuery = "SELECT Status FROM customer_sessions WHERE CustomerID = ? AND Status = 'active'";
    $stmt = $conn->prepare($checkSessionQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        session_destroy();
        $response = ["status" => "expired"]; // Send expiration status
    }

    $stmt->close();
} 

echo json_encode($response);
?>

