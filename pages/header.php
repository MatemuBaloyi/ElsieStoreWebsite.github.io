<header>
    <div class="header-content">
        <div class="desktop_header">
            <h1 class="header-name"><a href="./index.php" class="header-name">ElsieStore</a></h1>
            <form class="search-form" onsubmit="return false;">
                <input type="text" id="searchInput" class="search-input" placeholder="Search for products...">
                <div id="searchResults" class="search-results"></div>
            </form>

            <div class="right_header_content">

                <!-- <div class="btns">
                    <a href="./register.html" class="header-button" id="register-btn">Register</a>
                    <a href="./login.html" class="header-button" id="login-btn">Login</a>
                </div>
-->
                <div class="profilebtn">
                    <a href="./profilenav.php" class="header-button" id="profile-btn"><i class='bx bx-user'></i></a>
                </div>

                <div class="cart-icon">
                    <a href="./cartpage.php" class="cart-name"><i class='bx bx-cart'></a></i>
                    <span>0</span>
                </div>
            </div>
        </div>

        <div class="mobile_search_input">
            <input type="text" class="mobile-search-input" placeholder="Search for products...">
        </div>

    </div>
</header>
<style>
    .search-results {
        position: absolute;
        left: 50%;
        /* Center horizontally */
        transform: translateX(-50%);
        /* Adjust for centering */
        top: 60px;
        background: white;
        border: 1px solid #ddd;
        width: 300px;
        max-height: 300px;
        border-radius: 8px;
        overflow-y: auto;
        display: none;
        z-index: 999;
    }

    .search-results div {
        padding: 10px;
        cursor: pointer;
    }

    .search-results div:hover {
        background: #f0f0f0;
    }

    @media screen and (max-width: 710px) {
        .search-results {
            width: 70%;
            /* Adjust width for smaller screens */
            left: 50%;
            /* Keep it centered */
            transform: translateX(-50%);
            /* Adjust for centering */
            top: 115px;
            /* Adjust position for mobile view */
        }

    }
</style>
<script src="../js/search_input.js"></script>