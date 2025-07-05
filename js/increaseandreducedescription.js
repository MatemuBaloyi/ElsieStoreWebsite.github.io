document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.querySelector(".viewMoreButton");
    const description = document.querySelector(".product_description_text");
  
    if (toggleButton && description) {
      toggleButton.addEventListener("click", () => {
        if (description.classList.contains("expanded")) {
          // Collapse the description
          description.classList.remove("expanded");
          toggleButton.textContent = "Read More";
          toggleButton.style.border = "1px solid orange";
          toggleButton.style.background = "#fff";
          toggleButton.style.color = "black";
        } else {
          // Expand the description
          description.classList.add("expanded");
          toggleButton.textContent = "View Less";
          toggleButton.style.background = "orange";
          toggleButton.style.color = "white";
        }
      });
    } else {
      console.error("Toggle button or description element not found.");
    }
  });