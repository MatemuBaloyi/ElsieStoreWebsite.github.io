document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("productId");

  if (productId) {
    fetchProductDetails(productId);
    console.log("Product ID:", productId);
  } else {
    console.error("Product ID not found in URL.");
  }
});

function fetchProductDetails(productId) {
  // Fetch product details from the server
  fetch(`../fetchproduct.php?productId=${productId}`)
    .then((response) => response.json())
    .then((categories) => {
      console.log("Fetched categories:", categories);

      let product = null;
      let categoryId = null;

      // Loop through categories to find the product
      for (const category of categories) {
        const foundProduct = category.products.find(
          (prod) => prod.id === parseInt(productId, 10)
        );
        if (foundProduct) {
          product = foundProduct;
          categoryId = category.id;
          break;
        }
      }

      if (product) {
        product.categoryId = categoryId;
        populateProductDetails(product);
        fetchRelatedProducts(categoryId);
      } else {
        console.error("Product not found in any category.");
        document.querySelector(".Product_container").innerHTML =
          "<p>Product not found.</p>";
      }
    })
    .catch((error) => console.error("Error fetching product details:", error));
}

function populateProductDetails(product) {
  // Ensure product.image is an array
  if (typeof product.image === "string") {
    product.image = product.image.includes(',')
      ? product.image.split(',').map(img => img.trim())
      : [product.image.trim()];
  }

  if (!Array.isArray(product.image) || product.image.length === 0) {
    product.image = ["../images/placeholder-image.jpg"];
  }

  window.productImages = product.image;

  // Calculate the average rating
  const averageRating = calculateAverageRating(product.reviews);

  // Populate image gallery
  const galleryContainer = document.querySelector(".product_gallery .images");
  galleryContainer.innerHTML = product.image
    .map((imgUrl) => `<img src="${imgUrl}" alt="${product.name}" style="max-width:100%; height:auto;">`)
    .join("");

  document.querySelector("#total-images").textContent = product.image.length;

  console.log("Product image array:", product.image);

  // Populate product information
  document.querySelector(".product_info h2").textContent = product.name;
  document.querySelector(".product_info h3").textContent = `R${product.price}`;
  document.querySelector(".product_info p:nth-of-type(2) span").textContent =
    product.review_count || 0;
  document.querySelector(".product_info  #star-rating" ).innerHTML =
    generateStars(averageRating);

  document.querySelector(".Product_description .product_description_text").textContent =
    product.description || "No description available.";

  // Add "Write a Review" button
  const reviewButtonContainer = document.querySelector(".write_review_section");
  if (reviewButtonContainer) {
    console.log("Found .write_review_section, adding button...");
    reviewButtonContainer.innerHTML = `
      <h3>Write a new review</h3>
      <button id="writeReviewButton" class="write_review_btn">
        Write a Review
      </button>
    `;

    document.querySelector("#writeReviewButton").addEventListener("click", () => {
      window.location.href = `review.php?productId=${product.id}`;
    });
  } else {
    console.error("write_review_section not found in the DOM.");
  }

  // Populate reviews
  const reviewsContainer = document.querySelector(".users_review");
  if (product.reviews && product.reviews.length > 0) {
    reviewsContainer.innerHTML = product.reviews
      .map(
        (review) => `
          <div class="user_comment_container">
            <div class="user_comment">
              <div class="user_name_and_star">
                <h3 style="margin-left: 12px;">${review.customer.customer_names}</h3>
                <p><span>${generateStars(review.rating)}</span></p>
              </div>
              <p>${review.text}</p>
              <p>Reviewed at (${review.date})</p>
            </div>
            <div class="helpful_review_button">
              <button class="helpful-button" data-review-id="${review.id}">Helpful <span><i class='bx bx-like'></i></span></button>
            </div>
          </div>
        `
      )
      .join("");

      // Add event listeners to "Helpful" buttons
    document.querySelectorAll(".helpful-button").forEach(button => {
      button.addEventListener("click", (event) => {
        const reviewId = event.target.getAttribute("data-review-id");
        markReviewAsHelpful(reviewId);
      });
    });

  } else {
    reviewsContainer.innerHTML = "<p>No reviews available.</p>";
  }

  setupImageSlider();
}

function calculateAverageRating(reviews) {
  if (!reviews || reviews.length === 0) return 0;
  const totalRating = reviews.reduce((sum, review) => sum + review.rating, 0);
  return totalRating / reviews.length;
}

function markReviewAsHelpful(reviewId) {
  fetch("../markedHelpfulreview.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ reviewId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert("Marked as helpful!");
      // Optionally update the UI to reflect the change
    } else {
      alert("Failed to mark as helpful: " + (data.error || "Unknown error"));
    }
  })
  .catch(error => {
    console.error("Error marking review as helpful:", error);
    alert("An error occurred: " + error.message);
  });
}

function fetchRelatedProducts(categoryId) {
  const urlParams = new URLSearchParams(window.location.search);
  const CurrentproductId = urlParams.get("productId");
  fetch(`../fetchproduct.php?categoryId=${categoryId}`)
    .then((response) => response.json())
    .then((categories) => {
      const category = categories.find((cat) => cat.id === parseInt(categoryId, 10));

      if (category && category.products && category.products.length > 0) {
        populateRelatedProducts(category.products, CurrentproductId);
      } else {
        console.error("No related products found.");
        document.querySelector(".related_products_container").innerHTML =
          "<p>No related products available.</p>";
      }
    })
    .catch((error) =>
      console.error("Error fetching related products:", error)
    );
}

function populateRelatedProducts(products, CurrentproductId) {
  const relatedContainer = document.querySelector(".related_products_container");

  // Filter out the current product
  const filteredProducts = products.filter(
    (product) => String(product.id) !== String(CurrentproductId)
  );

  if (!filteredProducts || filteredProducts.length === 0) {
    relatedContainer.innerHTML = "<p>No related products available.</p>";
    return;
  }

  relatedContainer.innerHTML = filteredProducts
    .map((product) => {
      let imageUrl = "";

      if (typeof product.image === "string") {
        // Convert comma-separated string into an array
        product.image = product.image.includes(',')
          ? product.image.split(',').map(img => img.trim())
          : [product.image.trim()];
      }
      
      if (Array.isArray(product.image) && product.image.length > 0) {
        imageUrl = product.image[0]; // Use the first image
      } else {
        imageUrl = "../images/placeholder-image.jpg"; // Fallback
      }
      

      // Calculate the average rating
      const averageRating = calculateAverageRating(product.reviews);

      return `
        <div class="CL-card" data-id="${product.id}">
          <img src="${imageUrl}"  >
          <h3>${product.name}</h3>
          <p>R${product.price}</p>
          <h4>(${product.review_count || 0}) <span>${generateStars(averageRating)}</span></h4>
          <div class="card_btns">
            <button class="addtocart" data-id="${product.id}" data-image="${imageUrl}">Add to cart</button>
            <button class="heart"><i class='bx bx-heart'></i></button>
          </div>
        </div>
      `;
    })
    .join("");

    // Add event listener to the related products container
  relatedContainer.addEventListener("click", (event) => {
    const card = event.target.closest(".CL-card");
    if (card) {
      const productId = card.getAttribute("data-id"); 
      console.log("Clicked on product ID:", productId);
      if (!event.target.closest("button")) {
        // Redirect to product info page if a button is not clicked
        window.location.href = `productinfo.php?productId=${productId}`;
      }
    }
  });

  // Prevent buttons from redirecting
  relatedContainer.querySelectorAll(".addtocart, .heart").forEach((button) => {
    button.addEventListener("click", (event) => {
      event.stopPropagation();
      // Add your button-specific logic here
    });
  });
}

function generateStars(rating) {
  const fullStar = "<i class='bx bxs-star star-orange'></i>";
  const emptyStar = "<i class='bx bx-star'></i>";
  const roundedRating = Math.round(rating);
  return fullStar.repeat(roundedRating) + emptyStar.repeat(5 - roundedRating);
}

function setupImageSlider() {
  const images = document.querySelectorAll(".product_gallery .images img");
  let currentIndex = 0;

  // Function to show the current image
  function showImage(index) {
    images.forEach((img, i) => {
      img.style.display = i === index ? "block" : "none";
    });

    // Update the image count display after changing the index
    document.querySelector("#total-images").textContent = `${index + 1} of ${images.length}`;
  }

  // Show the first image initially
  showImage(currentIndex);

  const prevBtn = document.querySelector(".prev-btn");
  const nextBtn = document.querySelector(".next-btn");

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", () => {
      // Update the current index for the previous button
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      showImage(currentIndex);  // Pass the updated currentIndex to the showImage function
    });

    nextBtn.addEventListener("click", () => {
      // Update the current index for the next button
      currentIndex = (currentIndex + 1) % images.length;
      showImage(currentIndex);  // Pass the updated currentIndex to the showImage function
    });
  } else {
    console.error("Prev or Next button not found in the DOM.");
  }
}
