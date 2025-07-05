<?php
require 'config.php';
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Something went wrong"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['Names']);
    $surname = trim($_POST['Surname']);
    $cellphone = trim($_POST['Cellphone']);
    $email = trim($_POST['Email_address']);
    $password = trim($_POST['Password']);
    $confirm_password = trim($_POST['Confirm_password']);
    $tsandCs = isset($_POST['TermsandConditions']) ? 'Yes' : 'No';
    $promotional_news = isset($_POST['Prom_News']) ? 'Yes' : 'No';

    //Validate Names and Surname
    if (!preg_match('/^[a-zA-Z\s]+$/', $first_name) || !preg_match('/^[a-zA-Z\s]+$/', $surname)) {
        echo json_encode(["status" => "error", "message" => "Invalid name format."]);
        exit();
    }

    // Validate phone number
    if (!preg_match('/^\d{10}$/', $cellphone)) {
        echo json_encode(["status" => "error", "message" => "Invalid phone number."]);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format."]);
        exit();
    }

    // Validate Password
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters long and include uppercase, lowercase letters, and numbers."]);
        exit();
    }

    // Validate terms and conditions
    if (!$tsandCs) {
        echo json_encode(["status" => "error", "message" => "You must agree to the Terms and Conditions."]);
        exit();
    }

    // Validate password matching
    if ($password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email exists
    $check_email = $conn->prepare("SELECT * FROM customer WHERE Email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email is already registered."]);
    } else {
        // Insert user data
        $stmt = $conn->prepare("INSERT INTO customer (FirstName, LastName, Phone, Email, Password, TermsAndConditions, promotional_news) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $first_name, $surname, $cellphone, $email, $hashed_password, $tsandCs, $promotional_news);

        if ($stmt->execute()) {
            // Set session with new user data
         $_SESSION["user"] = [
        "CustomerID" => $stmt->insert_id,
        "FirstName"  => $firstName,
        "LastName"   => $lastName,
        "Email"      => $email
        ];
            echo json_encode(["status" => "success", "message" => "Registration successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error registering user."]);
        }

        $stmt->close();
    }

    $check_email->close();
}

$conn->close();
?>
