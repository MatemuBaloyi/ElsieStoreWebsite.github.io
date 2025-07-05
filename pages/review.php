<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    $_SESSION["redirect_after_login"] = $_SERVER["REQUEST_URI"]; // Save current page
    header("Location: ../pages/login.html");
    exit;
}

// Fetch user details from session
$user_id = $_SESSION['user']['CustomerID'] ?? null;
?>
<?php

$productId = $_GET['productId'] ?? null;
if (!$productId) {
    echo "Invalid product.";
    error_log("Invalid product ID: $productId");
    echo "Invalid product.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/header&footer.css">
    <link rel="stylesheet" href="../css/review.css"> <!-- New CSS file -->
    <title>Write a Review</title>
</head>
<body>
    <?php include 'header.php';?>

    <div class="review-container">
        <h2>Write a Review</h2>
        <p class="product-id">Reviewing Product ID: <span><?php echo htmlspecialchars($productId); ?></span></p>

        <form id="reviewForm" method="post" class="review-form">
            <input type="hidden" name="productId" value="<?php echo htmlspecialchars($productId); ?>">
            <input type="hidden" name="customerId" id="customerId" value="<?php echo htmlspecialchars($user_id); ?>">

            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="">Select a rating...</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>

            <label for="reviewComment">Comment:</label>
            <textarea name="comment" id="reviewComment" required placeholder="Write your review here..."></textarea>

            <button type="submit" class="submit">Submit Review</button>
        </form>
    </div>
    <?php include 'popover.php'; ?>

    <script src="../js/submitreview.js"></script>
    <script>
        const customerId = "<?php echo htmlspecialchars($user_id); ?>";
        console.log(customerId);
    </script>
</body>
</html>
