<?php

require 'config.php'; // Include database connection

if (isset($_GET['logout']) && isset($_SESSION['user'])) {
    // Get the logged-in user's ID securely
    $userId = $_SESSION['user']['CustomerID']; // Make sure this matches your session structure

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM loggedincustomer WHERE CustomerID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect the user to the login page
    header("Location: ./pages/index.php");
    exit();
}
?>
