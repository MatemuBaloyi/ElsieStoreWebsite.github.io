document.addEventListener("DOMContentLoaded", () => {
  fetch("../fetchproduct.php") // Update with your PHP script path
    .then((response) => response.json())
    .then((categories) => {
      renderCategories(categories);
      setupAddToCartListeners();
    })
    .catch((error) => console.error("Error fetching categories and products:", error));
});

function renderCategories(categories) {
  console.log(categories);
  const categoryContainer = document.querySelector(".category_content");

  categories.forEach((category) => {
    const section = document.createElement("div");
    section.classList.add(`${category.name}_content`);

    section.innerHTML = `
      <div class="Clothes_headers">
        <h2>${category.name}</h2>
        <a href="moreproduct.php?category=${category.name}" class="viewmorebtn">View More</a>
      </div>
      <div class="clothes_cards"></div>
    `;

    const cardsContainer = section.querySelector(".clothes_cards");

    const maxProductsToShow = 6;
    category.products.slice(0, maxProductsToShow).forEach((product) => {
      const productCard = document.createElement("div");
      productCard.classList.add("CL-card");

      productCard.addEventListener("click", () => {
        window.location.href = `productinfo.php?productId=${product.id}`;
      });

      let imageUrl = Array.isArray(product.image) ? product.image[0] : product.image;
      
      const placeholderimage = "../images/placeholder-image.jpg";
      if (!imageUrl || imageUrl.trim() === "") {
        imageUrl = placeholderimage;
      }
      // Calculate the average rating
      const averageRating = calculateAverageRating(product.reviews);

      productCard.innerHTML = `
        <img src="${imageUrl}" >
        <h3>${product.name}</h3>
        <p>R${product.price}</p>
        <h4>(${product.review_count || 0}) <span>${generateStars(averageRating)}</span></h4>
        <div class="card_btns">
          <button class="addtocart" data-id="${product.id}" data-name="${product.name}" 
                  data-price="${product.price}" data-image="${imageUrl}">
            Add to cart
          </button>
          <button class="heart" data-id="${product.id}"><i class='bx bx-heart'></i></button>
        </div>
      `;

      const addToCartButton = productCard.querySelector(".addtocart");
      addToCartButton.addEventListener("click", (event) => {
        event.stopPropagation();
        console.log(`Added to cart: ${product.name}`);
      });

      const heartButton = productCard.querySelector(".heart i");
      toggleHeartColor(heartButton, product.id); // Check liked status on load

      heartButton.addEventListener("click", (event) => {
        event.stopPropagation();
        toggleLike(product.id, heartButton);
      });

      cardsContainer.appendChild(productCard);
    });

    categoryContainer.appendChild(section);
  });
  setupAddToCartListeners();
}

function calculateAverageRating(reviews) {
  if (!reviews || reviews.length === 0) return 0;
  const totalRating = reviews.reduce((sum, review) => sum + review.rating, 0);
  return totalRating / reviews.length;
}

function generateStars(rating) {
  let stars = "";
  for (let i = 0; i < 5; i++) {
    stars += i < rating ? "<i class='bx bxs-star star-orange'></i>" : "<i class='bx bx-star'></i>";
  }
  return stars;
}

// Toggle product like status (LocalStorage + Database)
function toggleLike(productId, heartButton) {
  let favorites = JSON.parse(localStorage.getItem("favorites")) || [];

  if (favorites.includes(productId)) {
    favorites = favorites.filter(id => id !== productId);
    updateDatabaseFavorite(productId, "remove");
  } else {
    favorites.push(productId);
    updateDatabaseFavorite(productId, "add");
  }

  localStorage.setItem("favorites", JSON.stringify(favorites));
  toggleHeartColor(heartButton, productId);
}

// Change heart color based on like status
function toggleHeartColor(heartButton, productId) {
  let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
  if (favorites.includes(productId)) {
    heartButton.style.color = "red";
    heartButton.classList.remove("bx-heart");
    heartButton.classList.add("bxs-heart");
  } else {
    heartButton.style.color = "black";
    heartButton.classList.remove("bxs-heart");
    heartButton.classList.add("bx-heart");
  }
}

// Update favorites in database
function updateDatabaseFavorite(productId, action) {
  fetch("../updatefavorite.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ productId, action }),
  })
    .then(response => response.json())
    .then(data => console.log(data.message))
    .catch(error => console.error("Error updating favorites:", error));
}

// Setup Add to Cart Listeners
function setupAddToCartListeners() {
  const addToCartButtons = document.querySelectorAll(".addtocart");

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      const productId = event.target.getAttribute("data-id");
      const productName = event.target.getAttribute("data-name");
      const productPrice = parseFloat(event.target.getAttribute("data-price"));
      const productImage = event.target.getAttribute("data-image");

      const product = { id: productId, name: productName, price: productPrice, image: productImage, quantity: 1 };
      addToCart(product);
    });
  });
}

function addToCart(product) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  const existingProduct = cart.find((item) => item.id === product.id);
  if (!existingProduct) {
    cart.push(product); // Only add the product if it is not already in the cart
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  
  alert(`${product.name} has been added to your cart!`);
}


function sendCartToServer() {
  let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

  if (cartItems.length === 0) {
      console.log("Cart is empty.");
      return;
  }

  console.log("Sending cart data:", cartItems); // Debugging

  fetch("../cart_to_database_Server.php", {
      method: "POST",
      headers: {
          "Content-Type": "application/json"
      },
      body: JSON.stringify(cartItems)
  })
  .then(response => {
      console.log("Raw response:", response);
      return response.json(); // This might fail if the response is not valid JSON
  })
  .then(data => {
      console.log("Server response:", data);
      if (data.success) {
          alert("Cart saved successfully!");
          localStorage.removeItem("cart"); // Clear cart after saving
      } else {
          alert("Failed to save cart: " + (data.error || "Unknown error"));
      }
  })
  .catch(error => {
      console.error("Fetch error:", error);
      alert("An error occurred: " + error.message);
  });
  
}
