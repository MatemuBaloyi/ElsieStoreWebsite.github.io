<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/header&footer.css">
</head>
<body>
    <?php include 'header.php';?>

    <main class="cart-page">
        <h2>Your Shopping Cart</h2>
        <div class="cart-items">
            <!-- Cart items will be dynamically added here -->
        </div>
        <div class="cart-summary">
            <p>Total Items: <span id="total-items">0</span></p>
            <p>Total Price: R<span id="total-price">0.00</span></p>
            <button id="checkout-btn" class="checkout-button">Proceed to Checkout</button>
        </div>

    </main>

    <?php include 'footer.php';?>
    <?php include 'popover.php';?>
    
    <script src="../js/cart.js" type="module"></script>
    <script src="../js/cart_to_database.js" type="module"></script>
    
      
</body>
</html>
