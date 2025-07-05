<?php
include 'config.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user']['CustomerID'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// Join customer table with the address table (using a LEFT JOIN so that even if no address exists, you still get customer details)
$sql = "SELECT 
            c.FirstName AS name, 
            c.Lastname AS surname, 
            c.Phone AS cellphone, 
            c.Email AS email,
            a.Province AS province, 
            a.City AS city, 
            a.Surburb AS suburb, 
            a.PostalCode AS postal_code,
            a.StreetAddress AS street,  
            a.Complex AS complex 
        FROM customer c
        LEFT JOIN customeraddress a ON c.CustomerID = a.CustomerID
        WHERE c.CustomerID = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare statement: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    echo json_encode($result->fetch_assoc(), JSON_UNESCAPED_SLASHES);
} else {
    echo json_encode(["error" => "User not found"]);
}

$stmt->close();
$conn->close();
?>

