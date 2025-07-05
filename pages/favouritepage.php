
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
  <link rel="stylesheet" href="../css/header&footer.css">
  <title>Favourite Products</title>
  <!-- Link to your CSS file if needed -->
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .favorites-container {
      display: flex;
      flex-direction: row;
      gap: 20px;
    }
    .favorite-card {
      border: 1px solid #ccc;
      padding: 10px;
      width: 420px;
      box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: row;
      justify-content: space-around;
    }
    .favorite-card img {
      width: 100px;
      height: auto;
    }
    .favorite-card h3 {
      margin: 5px 0 5px;
      font-size: 18px;
    }
    .favorite-card p {
      margin: 5px 1px;
      font-size: 14px;
    }
    .favorite-card button {
      
      background-color: #fff;
      border: 1px solid orange;
      border-radius: 5px;
      cursor: pointer;
    }
    .favorite-card button:hover {
      background-color: orange;
      
    } 
    .favorite-card button i {
      font-size: 1.1rem;
      padding: 5px 10px;
      color: orange;
    }
    .favorite-card button i:hover {
      color: white;
    }
  </style>
</head>
<body>
    <?php include 'header.php'; ?>
  <h1>Favourite Products</h1>
  <div class="favorites-container" id="favoritesContainer">
    <!-- Favourite product cards will be appended here -->
  </div>
  <?php include 'popover.php'; ?>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      fetchFavorites();
    });

    function fetchFavorites() {
      fetch("../favourite.php")
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            renderFavorites(data.favorites);
          } else {
            document.getElementById("favoritesContainer").innerHTML = `<p>${data.error}</p>`;
          }
        })
        .catch(error => {
          console.error("Error fetching favorites:", error);
          document.getElementById("favoritesContainer").innerHTML = `<p>Error loading favourites.</p>`;
        });
    }

    function renderFavorites(favorites) {
      const container = document.getElementById("favoritesContainer");
      if (favorites.length === 0) {
        container.innerHTML = "<p>You have no favourite products.</p>";
        return;
      }
      container.innerHTML = "";
      favorites.forEach(product => {
        // If the product images are stored as a comma-separated string,
        // we split them and take the first image.
        let imageUrl = product.images;
        if (typeof imageUrl === "string" && imageUrl.includes(",")) {
          imageUrl = imageUrl.split(",")[0].trim();
        }
        const card = document.createElement("div");
        card.classList.add("favorite-card");
        card.innerHTML = `
          <img src="${imageUrl}" alt="${product.ProductName}">
          <h3>${product.ProductName}</h3>
          <p>${product.Description}</p>
          <p>Price: R${product.Price}</p>
          <button onclick="removeFavorite(${product.ProductID})"><i class='bx bx-trash-alt'></i></button>
          
        `;
        container.appendChild(card);
      });
    }

    // (Optional) Function to remove a product from favorites via an AJAX call.
    // You would need to implement a corresponding removeFavorite.php file.
    function removeFavorite(productId) {
    console.log("Removing product ID:", productId); // Debugging
    if (confirm("Are you sure you want to remove this product from your favourites?")) {
        fetch("../removefavorite.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `product_id=${productId}` // Fix key name
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Product removed from favourites.");
                fetchFavorites(); // Refresh the list after removal
            } else {
                alert("Error removing product: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while removing the product.");
        });
    }
}

  </script>
</body>
</html>
