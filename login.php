<?php
require 'config.php';
header('Content-Type: application/json');

// Start session


// Retrieve and sanitize inputs
$email = trim($_POST["signinEmail"] ?? '');
$password = trim($_POST["signinPassword"] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Invalid email format.']);
    exit;
}

// Retrieve the user
$stmt = $conn->prepare("SELECT CustomerID, FirstName, LastName, Email, Password, CustomerStatus FROM customer WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if ($user["CustomerStatus"] == "1") {
        echo json_encode(['success' => false, 'error' => 'Your account is currently blocked. Please contact support.']);
        exit;
    }
    
    // Verify password
    if (password_verify($password, $user["Password"])) {
        $_SESSION["user"] = $user;
        $userId = $user['CustomerID'];

        // Insert user into logged-in table OR update last_activity if they exist
        $insertLoggedinUserQuery = "
            INSERT INTO customer_sessions (CustomerID, last_activity, Status) 
            VALUES ($userId, NOW(), 'active') 
            ON DUPLICATE KEY UPDATE last_activity = NOW(), status = 'active'";
        mysqli_query($conn, $insertLoggedinUserQuery);

        // Redirect after login
        $redirect_url = $_SESSION['redirect_after_login'] ?? 'index.php'; // Default to home page if no previous URL
        unset($_SESSION['redirect_after_login']); // Remove after use

        echo json_encode(['success' => true, 'message' => 'Login successful! Redirecting...', 'redirect' => $redirect_url]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid password.']);
    }
} else {
        echo json_encode(['success' => false, 'error' => 'User not found.']);
}

$stmt->close();
$conn->close();

?>
