document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const categoryName = urlParams.get("category");

  if (categoryName) {
    // Update the page header dynamically with the category name
    document.querySelector(".categoryname h2").textContent = categoryName;
    // Fetch and render products for this category
    fetchProductsByCategory(categoryName);
  } else {
    console.error("Category name not found in URL.");
  }

// Add event listener to the filter button
const filterButton = document.querySelector(".filter_button button");
const filterMenu = document.querySelector(".filter_menu");

if (filterButton && filterMenu) {
  filterButton.addEventListener("click", toggleFilterMenu);
} else {
  console.error("Filter button or filter menu not found.");
}

// Add event listener to the "Apply Filters" button
const applyFiltersButton = document.getElementById("applyFilters");
if (applyFiltersButton) {
  applyFiltersButton.addEventListener("click", applyFilters);
} else {
  console.error("Apply Filters button not found.");
}
});

function toggleFilterMenu() {
  const filterMenu = document.querySelector(".filter_menu");
  if (filterMenu) {
    if (filterMenu.style.display === "block") {
      filterMenu.style.display = "none";
    } else {
      filterMenu.style.display = "block";
    }
  } else {
    console.error("Filter menu not found.");
  }
}

function applyFilters() {
const selectedFilters = Array.from(document.querySelectorAll(".filter_menu input[type='checkbox']:checked"))
  .map(checkbox => checkbox.value);

const urlParams = new URLSearchParams(window.location.search);
const categoryName = urlParams.get("category");

if (categoryName) {
  fetchProductsByCategory(categoryName, selectedFilters);
} else {
  console.error("Category name not found in URL.");
}
}

function fetchProductsByCategory(categoryName, filters = []) {
// Fetch products for the given category
fetch(`../fetchproduct.php?category=${encodeURIComponent(categoryName)}`)
  .then(response => response.json())
  .then(data => {
    // Find the category that matches the categoryName
    const category = data.find(cat => cat.name.toLowerCase() === categoryName.toLowerCase());
    if (category && category.products.length > 0) {
      let filteredProducts = category.products;

      // Apply filters
      if (filters.includes("price")) {
        filteredProducts = filteredProducts.sort((a, b) => a.price - b.price);
      }
      if (filters.includes("rating")) {
        filteredProducts = filteredProducts.sort((a, b) => b.rating - a.rating);
      }
      if (filters.includes("category")) {
        // Example filter: filter by a specific subcategory
        filteredProducts = filteredProducts.filter(product => product.subcategory === "exampleSubcategory");
      }

      renderProducts(filteredProducts);
    } else {
      console.error("No products found for the category:", categoryName);
      document.querySelector(".moreproduct_content").innerHTML =
        "<p>No products available for this category.</p>";
    }
  })
  .catch(error =>
    console.error("Error fetching products for category:", error)
  );
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

function renderProducts(products) {
  const productContainer = document.querySelector(".moreproduct_content");
  productContainer.innerHTML = ""; // Clear existing products

  products.forEach(product => {
    const productCard = document.createElement("div");
    productCard.classList.add("moreproduct_content_name");

    let imageUrl = Array.isArray(product.image) ? product.image[0] : product.image;
    const placeholderimage = "../images/placeholder-image.jpg";

    console.log("Product:", product.name, "Image Path:", imageUrl); // Debugging

    if (!imageUrl || imageUrl.trim() === "") {
      imageUrl = placeholderimage;
    } else if (!imageUrl.startsWith("http")) {
      imageUrl = `../${imageUrl}`; // Ensure proper relative path
    }
    // Calculate the average rating
    const averageRating = calculateAverageRating(product.reviews);

    productCard.innerHTML = `
      <div class="tab_pic">
        <img src="${imageUrl}" alt="${product.name}" onerror="this.onerror=null; this.src='../images//placeholder-image.jpg';">
      </div>
      <div class="right_side_tab">
        <div class="name">
          <h3>${product.name}</h3>
          <h4>R${product.price}</h4>
          <h5>(${product.review_count || 0}) <span>${generateStars(averageRating)}</span></h5>
        </div>
        <button class="addtocart" data-id="${product.id}" data-name="${product.name}" 
                data-price="${product.price}" data-image="${product.image}">
          <i class='bx bx-cart'></i>
        </button>
      </div>
    `;

    // Add click event to navigate to the product info page
    productCard.addEventListener("click", event => {
      // Prevent redirection if a button or its child was clicked.
      if (event.target.closest("button")) {
        return;
      }
      // Redirect to the product info page with the product ID
      window.location.href = `../pages/productinfo.php?productId=${product.id}`;
    });

    productContainer.appendChild(productCard);
  });
}

