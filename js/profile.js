document.addEventListener("DOMContentLoaded", () => {
    fetchUserProfile();
  
    document.querySelector("#profileForm").addEventListener("submit", (e) => {
      e.preventDefault();
      updateUserProfile();
    });
  
    document.querySelector("#addressForm").addEventListener("submit", (e) => {
      e.preventDefault();
      updateUserProfile();
    });
  });
  
  function fetchUserProfile() {
    fetch("../profile.php")
      .then((response) => response.json())
      .then((data) => {
        console.log("User profile data:", data);
        if (data.error) {
          console.error(data.error);
          return;
        }

        // ✅ Populate customer details
        document.querySelector("#name").value = data.name || "";
        document.querySelector("#surname").value = data.surname || "";
        document.querySelector("#cellphone").value = data.cellphone || "";
        document.querySelector("#email").value = data.email || "";

        // ✅ Populate address details (Allow empty fields for new address)
        document.querySelector("#province").value = data.province || "";
        document.querySelector("#city").value = data.city || "";
        document.querySelector("#suburb").value = data.suburb || "";
        document.querySelector("#street").value = data.street || "";
        document.querySelector("#postal_code").value = data.postal_code || "";
        document.querySelector("#complex").value = data.complex || "";
      })
      .catch((error) => console.error("Error fetching user profile:", error));
}

  
  function updateUserProfile() {
    const userData = {
      name: document.querySelector("#name").value,
      surname: document.querySelector("#surname").value,
      cellphone: document.querySelector("#cellphone").value,
      email: document.querySelector("#email").value,
      province: document.querySelector("#province").value,
      city: document.querySelector("#city").value,
      suburb: document.querySelector("#suburb").value,
      street: document.querySelector("#street").value,
      postal_code: document.querySelector("#postal_code").value,
      complex: document.querySelector("#complex").value,
    };
  
    fetch("../updateprofile.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(userData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showPopover("Profile updated successfully!", 'success')
        } else {
          showPopover("Error: " + data.error, 'error');
        }
      })
      .catch((error) => console.error("Error updating profile:", error));
  }
  