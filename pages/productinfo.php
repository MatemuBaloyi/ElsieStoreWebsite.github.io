<?php

// Check if the user is logged in
// Fetch user details from session
$user_id = $_SESSION['user']['CustomerID'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/header&footer.css">
    <link rel="stylesheet" href="../css/productinfo.css">
    <title>Product</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <?php include 'popover.php';?>
        <section class="Product_information">
            <div class="Product_container">
                <div class="product_information_container">
                    <div class="product_gallery-info">
                        <div class="product_gallery">
                            <div class="images">


                            </div>
                            <button class="prev-btn">❮</button>
                            <button class="next-btn">❯</button>
                            <p><span id="total-images"></span></p>
                        </div>
                        <div class="product_info">
                            <h2></h2>
                            <h3></h3>
                            <p class="reviewheader">Review</p>
                            <p class="reviewsnumandstars">(<span> 10 </span>) <span id="star-rating">⭐⭐⭐⭐☆ </span></p>
                        </div>
                    </div>

                    <div class="Product_description">
                        <h1>Description</h1>
                        <p class="product_description_text">

                        </p>
                        <div class="product_description_button">
                            <button id="viewMoreButton" class="viewMoreButton">Read More</button>
                        </div>
                    </div>
                    <div class="product_reviews">
                        <h2>Reviews</h2>
                        <div class="write_review_section">

                        </div>

                        <div class="users_review">


                        </div>
                    </div>
                </div>

                <div class="related_products">
                    <h2>Related Products</h2>
                    <div class="related_products_container">

                    </div>


                </div>
            </div>

        </section>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const imagesContainer = document.querySelector('.images');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            const counter = document.querySelector('.product_gallery p span:first-child');
            const totalImages = document.querySelector('.product_gallery p span:last-child');

            let currentIndex = 0; // Start with the first image

            function initializeSlider() {
                const images = document.querySelectorAll('.images img');

                if (images.length === 0) {
                    console.error("No images available for the slider.");
                    return;
                }

                // Update total images count
                totalImages.textContent = images.length;

                // Function to update the image position
                function updateSliderPosition() {
                    imagesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
                    counter.textContent = currentIndex + 1; // Update the counter
                }

                // Event listener for "Next" button
                nextBtn.addEventListener("click", () => {
                    if (currentIndex < images.length - 1) {
                        currentIndex++;
                        updateSliderPosition();
                    }
                });

                // Event listener for "Previous" button
                prevBtn.addEventListener("click", () => {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateSliderPosition();
                    }
                });

                // Initial update
                updateSliderPosition();
            }

            // Run the slider setup as soon as the DOM is loaded and images are available
            initializeSlider();
        });


    </script>


    <script src="../js/index.js"></script>
    <script src="../js/productinfo.js"></script>
    <script src="../js/increaseandreducedescription.js"></script>
    <script src="../js/Logoutpopover.js"></script>
</body>

</html>