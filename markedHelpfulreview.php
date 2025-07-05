<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

require 'config.php';

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }

    $reviewId = $data['reviewId'];

    // Update the database to mark the review as helpful
    $stmt = $pdo->prepare("UPDATE reviews SET Helpful_Count = Helpful_Count + 1 WHERE ReviewID = :reviewId");
    $stmt->bindParam(':reviewId', $reviewId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>