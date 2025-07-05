<?php
session_start();
// Fetch user details from session
// Check if the user session exists
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$userID = $user ? $user['CustomerID'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/header&footer.css">
    <link rel="stylesheet" href="../css/account.css">
    <title>Profile</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="profile_container">
            <div class="profile_container_content">
                <h2>Personal Information</h2>
                <form action="" id="profileForm">
                <label for="">Name</label>
                <input type="text" name="" id="name">
                <label for="">Surname</label>
                <input type="text" name="" id="surname">
                <label for="">Cellphone Number</label>
                <input type="text" name="" id="cellphone">
                <label for="">Email</label>
                <input type="email" name="" id="email">
                <button type="submit">Update</button>
                </form>
            </div>

            <div class="address_container_content" id="addressC">
                <h2>Address Information</h2>
                <form action="" id="addressForm">
                <label for="">Province</label>
                <input type="text" name="" id="province">
                <label for="">City</label>
                <input type="text" name="" id="city">
                <label for="">Suburb</label>
                <input type="text" name="" id="suburb">
                <label for="">Street Address</label>
                <input type="text" name="" id="street">
                <label for="">Postal Address</label>
                <input type="text" name="" id="postal_code">
                <label for="">Complex Building</label>
                <input type="text" name="" id="complex">
                <button type="submit">Update</button>
                </form>
            </div>
        </div>
        <?php include 'popover.php'; ?>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        const accountSelector = document.getElementById('account_selector');
      
        accountSelector.addEventListener('change', () => {
          const selectedValue = accountSelector.value;
          if (selectedValue) {
            window.location.href = selectedValue; // Redirect to the selected URL
          }
        });
      </script>
      <script src="../js/profile.js"></script>
</body>
</html>