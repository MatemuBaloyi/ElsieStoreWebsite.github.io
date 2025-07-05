<?php


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
    <link rel="stylesheet" href="../css/header&footer.css">
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/indexstyle.css">
    <link rel="stylesheet" href="../css/moreproduct.css">
    <title>Home</title>
    <style>
        /* Loader styles */
        #loader {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: #fff;
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 9999;
        }
        .spinner {
          border: 8px solid #f3f3f3; /* Light grey */
          border-top: 8px solid orange; /* Orange */
          border-radius: 50%;
          width: 60px;
          height: 60px;
          animation: spin 1s linear infinite;
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        /* Hide the main content until the page is fully loaded */
        #content {
          display: none;
        }
      </style>
</head>
<body>
    <!-- Loader -->
  <div id="loader">
    <div class="spinner"></div>
  </div>

  <div id="content">
   <?php
   include 'header.php';
   ?>
    <main >
        <section class="hero">
            <div class="hero_container">
                <h2>Get our affordable products from your local vendor</h2>
                <p>Explore a world of traditional treasure, Stylish clothing, and beautifully crafted portraits pots. Perfect for your home, wardrobe, or gifting needs.</p>
                <button>Contact Us</button>
            </div>
        </section>
        <section class="category_content">
           
        </section>

        <?php include 'popover.php'; ?>
            
        </div>
    </main>
   <?php include 'footer.php';?>
    <div class="mobile_narbar">
        <a href="./index.php" class="mobile_navbar_link"><i class='bx bx-home'></i></a>
        <a href="./favouritepage.php" class="mobile_navbar_link"><i class='bx bx-heart'></i></a>
        <a href="./account.php" class="mobile_navbar_link"><i class='bx bx-user'></i></a>
    </div>

    <script src="../js/index.js"></script>
    <script src="../js/fetchproducts.js"></script>
    <script src="../js/cart_to_database.js"></script>
    <script src="../js/Logoutpopover.js"></script>
    
  </div>
    

    
      <!-- Hide loader and display content when page fully loads -->
  <script>
    window.addEventListener("load", function() {
      const loader = document.getElementById("loader");
      const content = document.getElementById("content");
      loader.style.display = "none";
      content.style.display = "block";
    });
  </script>
      


</body>
</html>