<?php
require 'config.php';
session_start();

if (isset($_SESSION["user"])) {
    $userId = $_SESSION["user"]["CustomerID"];
    $query = "UPDATE loggedincustomer SET last_activity = NOW() WHERE CustomerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    echo json_encode(["status" => "success"]);
}
?>
