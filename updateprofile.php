<?php
include 'config.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
ob_clean(); // Clear previous output

$user_id = $_SESSION['user']['CustomerID'] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "Invalid data"]);
    exit;
}

$conn->begin_transaction();

try {
    // ✅ 1. Update customer personal details
    $sql_customer = "UPDATE customer SET FirstName = ?, LastName = ?, Phone = ?, Email = ? WHERE CustomerID = ?";
    $stmt_customer = $conn->prepare($sql_customer);
    $stmt_customer->bind_param("ssssi", $data["name"], $data["surname"], $data["cellphone"], $data["email"], $user_id);
    
    // ✅ 2. Check if user has an existing address
    $sql_check = "SELECT CustomerID FROM customeraddress WHERE CustomerID = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $stmt_check->store_result();
    
    if ($stmt_check->num_rows > 0) {
        // ✅ 3. If address exists, update it
        $sql_address = "UPDATE customeraddress SET Province = ?, City = ?, Surburb = ?, StreetAddress = ?, PostalCode = ?, Complex = ? WHERE CustomerID = ?";
        $stmt_address = $conn->prepare($sql_address);
        $stmt_address->bind_param("ssssisi", $data["province"], $data["city"], $data["suburb"], $data["street"], $data["postal_code"], $data["complex"], $user_id);
    } else {
        // ✅ 4. If no address exists, insert a new one
        $sql_address = "INSERT INTO customeraddress (CustomerID, Province, City, Surburb, StreetAddress, PostalCode, Complex) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_address = $conn->prepare($sql_address);
        $stmt_address->bind_param("issssis", $user_id, $data["province"], $data["city"], $data["suburb"], $data["street"], $data["postal_code"], $data["complex"]);
    }

    // ✅ 5. Execute both queries
    if ($stmt_customer->execute() && $stmt_address->execute()) {
        $conn->commit();
        echo json_encode(["success" => "Profile updated successfully"]);
    } else {
        throw new Exception("Failed to update profile");
    }
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["error" => $e->getMessage()]);
}

?>
