<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/header&footer.css">
    <link rel="stylesheet" href="../css/register.css">
    <title>Register</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <?php include 'popover.php'; ?>
        <div class="register_container">
            <div class="container_content">
                <h1>Register</h1>
                <form id="signupForm">
                    <label for="">Names:</label>
                    <input type="text" name="Names" required>
                    <label for="">Surname:</label>
                    <input type="text" name="Surname" required>
                    <label for="">Cellphone</label>
                    <input type="text" name="Cellphone" required>
                    <label for="">Email</label>
                    <input type="email" name="Email_address" placeholder="e.g email@gmail.com">
                    <label for="">Password</label>
                    <input type="password" name="Password" placeholder="e.g p@sswo1d">
                    <label for="">Confirm Password</label>
                    <input type="password" name="Confirm_password" required>


                    <div class="checkboxes">
                        <label class="checkbox-container">
                            <input type="checkbox" name="TermsandConditions">
                            <span class="checkmark"></span>
                            I agree, <a href="#">Terms and conditions</a>.
                        </label>

                        <label class="checkbox-container">
                            <input type="checkbox" name="Prom_News">
                            <span class="checkmark"></span>
                            Receive promotional news.
                        </label>
                    </div>


                    <button type="submit">Sign Up</button>
                </form>
                <h3>Already have an account? <a href="./loginpage.php">Sign In</a></h3>
            </div>

        </div>

        <!-- Popover Notification -->
        <div id="popover" class="popover"></div>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        const accountSelector = document.getElementById('account_selector');

        accountSelector.addEventListener('change', () => {
            const selectedValue = accountSelector.value;
            if (selectedValue) {
                window.location.href = selectedValue; // Redirect to the selected URL
            }
        });
    </script>
    <script>
        document.getElementById("signupForm").addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch("../register.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    showPopover(data.message, data.status === "success" ? "success" : "error");

                    if (data.status === "success") {
                        setTimeout(() => {
                            window.location.href = "/index.php"; // Redirect to home page after 2 seconds
                        }, 2000);
                    }
                })
                .catch(error => {
                    showPopover("Something went wrong. Try again.", "error");
                });
        });

        function showPopover(message, type = "success") {
            const popover = document.getElementById("popover");
            popover.textContent = message;
            popover.className = "popover show " + (type === "error" ? "error" : "");

            setTimeout(() => {
                popover.classList.remove("show");
            }, 3000);
        }
    </script>
    <script src="../js/cart.js"></script>
</body>

</html>