<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/header&footer.css">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title>
</head>
<body>
    <?php include 'header.php';?>
    <main>
        <div class="login_container">
            <div class="login_container_content">
                <h1>Login</h1>
                <form id="loginForm">
                    <label for="">Email</label>
                    <input type="email" name="signinEmail"  placeholder="email@gmail.com">
                    <label for="">Password</label>
                    <input type="password" name="signinPassword"  placeholder="e.g p@sswo1d">

                    <a href="">Forgot Password?</a>
    
                    <button type="submit">Signin</button>
                </form>
                <h3>Don't have an account? <a href="./registerpage.php">Signup</a></h3>
            </div>
           
        </div>
        <?php include 'popover.php'; ?>

    </main>
    <?php include 'footer.php';?>

       <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch("../login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showPopover(data.message || "Login successful! Redirecting...", 'success');
            setTimeout(() => {
                window.location.href = data.redirect || "./index.php"; // Redirect to previous page or index
            }, 2000);
        }
        else{
            showPopover(data.error || "Login failed. Please try again.", 'error');
        }
    })
    .catch(error => {
        showPopover("Something went wrong. Try again.", "error");
    });
});
</script>
    <script src="../js/popover.js"></script>
</body>
</html>