setInterval(() => {
    fetch("./update_activity.php")
      .then(response => response.json())
      .then(data => console.log("User activity updated:", data));
  }, 300000); // 5 minutes (300,000 ms)
  