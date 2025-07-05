<?php

require 'config.php';
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Something went wrong."];

// Retrieve and sanitize inputs
$email = trim($_POST["email"] ?? '');
$password = trim($_POST["password"] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}

// Retrieve the user
$stmt = $conn->prepare("SELECT CustomerID, FirstName, LastName, Email, Password FROM customer WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user["Password"])) {
        $_SESSION["user"] = $user;
        $response = ["status" => "success", "message" => "Login successful! Redirecting..."];
    } else {
        $response = ["status" => "error", "message" => "Invalid password."];
    }
} else {
    $response = ["status" => "error", "message" => "User not found."];
}
$stmt->close();
$conn->close();
echo json_encode($response);
?>
