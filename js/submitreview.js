document.addEventListener("DOMContentLoaded", () => {
  const reviewForm = document.querySelector("#reviewForm");

  if (reviewForm) {
      reviewForm.addEventListener("submit", (event) => {
          event.preventDefault();

          const productId = new URLSearchParams(window.location.search).get("productId");
          const rating = document.querySelector("#rating").value;
          const comment = document.querySelector("#reviewComment").value.trim();
          const customerId = document.querySelector("#customerId").value; // Get CustomerID

          if (!rating || !comment || !customerId) {
              alert("Please provide all required fields.");
              return;
          }

          const formData = new FormData();
          formData.append("productId", productId);
          formData.append("customerId", customerId);
          formData.append("rating", rating);
          formData.append("comment", comment);

          fetch("../submit_review.php", {
              method: "POST",
              body: formData,
          })
          .then((response) => response.json())
          .then((data) => {
              if (data.success) {
                showPopover(data.message, 'success');
                setTimeout(() => {
                window.location.href = `../pages/productinfo.php?productId=${productId}`;
                }, 2000);
                  
              } else {
                  showPopover("Error: " + data.message, 'error');
              }
          })
          .catch((error) => {
              console.error("Error submitting review:", error);
              alert("An error occurred. Please try again.");
          });
      });
  }
  else {
      console.error("reviewForm not found in the DOM.");
  }
});
