 // Alternatively, you can include the JavaScript directly here
 document.addEventListener("DOMContentLoaded", () => {
    checkSessionStatus();
  });

  function checkSessionStatus() {
    fetch("../auth.php")
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.status === "expired") {
          showLogoutPopover();
          setTimeout(() => {
            window.location.href = "login.php"; // Redirect to login page
          }, 3000); // Redirect after 3 seconds
        }
      })
      .catch(error => console.error("Error checking session status:", error));
  }

// Show popover notification inside existing div
function showLogoutPopover() {
    const popover = document.querySelector(".popover");
    if (popover) {
        popover.textContent = "Session expired. Please log in again.";
        popover.style.display = "block"; // Make it visible
        popover.style.position = "fixed"; // Ensure it stays on top
        popover.style.top = "10px";
        popover.style.right = "10px";
        popover.style.background = "#ff5555";
        popover.style.color = "#fff";
        popover.style.padding = "10px";
        popover.style.borderRadius = "5px";
        popover.style.boxShadow = "0px 0px 10px rgba(0, 0, 0, 0.2)";

        setTimeout(() => {
           
        }, 3000);
    }
}

// Check session every 30 seconds
setInterval(checkSessionStatus, 30000);

