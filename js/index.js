const itemcard = document.querySelectorAll('.CL-card');
const addtocartbutton = document.querySelector('.addtocart');

// Function to update the cart icon with the total number of items
function updateCartIcon() {
    // Get the span element inside the cart icon
    const cartIconCount = document.querySelector('.cart-icon span');

    // Get cart items from localStorage
    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

    // Calculate total quantity of items in the cart
    let totalItems = 0;
    cartItems.forEach(item => {
        totalItems += item.quantity;
    });

    // Update the span element with the total number of items
    cartIconCount.textContent = totalItems;
}

// Call this function when the page loads
document.addEventListener('DOMContentLoaded', () => {
    updateCartIcon();  // Update cart count on page load
});

itemcard.forEach(card => {
    card.addEventListener('click', (event) => {
      if (event.target.tagName === 'BUTTON' || event.target.tagName === 'I') {
        return;
      }

      // Get the unique ID or data-link of the card
      const productId = card.getAttribute('data-id');

      window.location.href = '../pages/productinfo.php?id=${productId}';
    });
  });

  