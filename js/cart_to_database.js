
function sendCartToServer() {
    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

    console.log(cartItems);

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

