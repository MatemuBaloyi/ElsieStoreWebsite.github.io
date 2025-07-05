
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/header&footer.css">
    <title>Profile - ElsieStore</title>
</head>
<body>
  <?php include 'header.php'; ?>

    <main>
        <?php include 'popover.php'; ?>
        <section class="profile-container">
            <h2>My Profile</h2>
            <ul class="profile-menu">
                <li><a href="registerpage.php">Register</a></li>
                <li><a href="loginpage.php">Login</a></li>
                <li><a href="account.php">Account Details</a></li>
                <li><a href="favouritepage.php">Favorites</a></li>
                <li><a href="change-password.php">Change Password</a></li>
                <li><a href="../logout.php?logout=true">Logout</a></li>
            </ul>
        </section>
    </main>

    <footer style="text-align: center;">
        <p>&copy; 2025 ElsieStore. All Rights Reserved.</p>
    </footer>
    <script src="../js/Logoutpopover.js"></script>

</body>
</html>
