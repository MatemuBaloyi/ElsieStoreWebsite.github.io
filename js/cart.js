// Description: JavaScript file for the cart page.

document.addEventListener('DOMContentLoaded', () => {
    const cartItemsContainer = document.querySelector('.cart-items');
    const totalItemsElement = document.getElementById('total-items');
    const totalPriceElement = document.getElementById('total-price');
    const checkoutBtn = document.getElementById('checkout-btn');

    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

    // Function to render cart items
    function renderCartItems() {
        cartItemsContainer.innerHTML = '';
        let totalItems = 0;
        let totalPrice = 0;

        // If the cart is empty, show an empty cart message
        if (cartItems.length === 0) {
            const emptyCartMessage = document.createElement('div');
            emptyCartMessage.classList.add('empty-cart-message');
            emptyCartMessage.innerHTML = `
                <p class="empty">Your cart is empty.</p>
                <a href="./index.php" class="continue-shopping-btn">Continue Shopping</a>
            `;
            document.querySelector('.cart-summary').style.display = 'none';
            cartItemsContainer.appendChild(emptyCartMessage);
            return;
        }

        // Render each item in the cart
        cartItems.forEach((item, index) => {
            totalItems += item.quantity;
            totalPrice += item.price * item.quantity;

            const cartItemElement = document.createElement('div');
            cartItemElement.classList.add('cart-item');
            cartItemElement.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <div class="cart-item-details">
                    <h3>${item.name}</h3>
                    <div class="quantity-controls">
                        <button class="decrease-qty-btn" data-index="${index}">-</button>
                        <span class="item-quantity">${item.quantity}</span>
                        <button class="increase-qty-btn" data-index="${index}">+</button>
                    </div>
                    <p class="cart-item-price">R${(item.price * item.quantity).toFixed(2)}</p>
                </div>
                <div class="remove-item-btn" data-index="${index}"><i class='bx bx-trash'></i></div>
            `;

            cartItemsContainer.appendChild(cartItemElement);
        });

        totalItemsElement.textContent = totalItems;
        totalPriceElement.textContent = `${totalPrice.toFixed(2)}`;

        // Save cart items back to localStorage
        localStorage.setItem('cart', JSON.stringify(cartItems));

        // Attach event listeners for quantity updates and removal
        addCartEventListeners();
    }

    // Function to attach event listeners to buttons
    function addCartEventListeners() {
        document.querySelectorAll('.increase-qty-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const index = event.target.getAttribute('data-index');
                updateItemQuantity(index, cartItems[index].quantity + 1);
            });
        });

        document.querySelectorAll('.decrease-qty-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const index = event.target.getAttribute('data-index');
                if (cartItems[index].quantity > 1) {
                    updateItemQuantity(index, cartItems[index].quantity - 1);
                }
            });
        });

        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const index = event.target.getAttribute('data-index');
                removeItemFromCart(index);
            });
        });
    }

    // Function to update item quantity
    function updateItemQuantity(index, newQuantity) {
        cartItems[index].quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cartItems));
        renderCartItems();
    }

    // Function to remove an item from the cart
    function removeItemFromCart(index) {
        cartItems.splice(index, 1);

        if (cartItems.length === 0) {
            localStorage.removeItem('cart');
        } else {
            localStorage.setItem('cart', JSON.stringify(cartItems));
        }

        renderCartItems();
    }

    // Event listener for checkout button
    checkoutBtn.addEventListener('click', () => {
        alert('The checkout option is currently not available at the moment. If you want to order, please contact us directly.');
    });

    // Initial render of cart items
    renderCartItems();
});
function sendCartToServer() {
    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
  
    if (cartItems.length === 0) {
        console.log("Cart is empty.");
        return;
    }
  
    console.log("Sending cart data:", cartItems); // Debugging
  
    fetch("./cart_to_database_Server.php", {
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
  
