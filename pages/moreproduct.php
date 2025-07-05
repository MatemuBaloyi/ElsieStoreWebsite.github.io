
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../images/ES_icon.svg" type="image/svg">
    <link rel="stylesheet" href="../css/moreproduct.css">
    <link rel="stylesheet" href="../css/header&footer.css">
    <title>Document</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="products">
            <div class="moreproduct_container_header">
                <div class="categoryname">
                    <h2>Clothes</h2>
                </div>
                <div class="filter_button">
                    <button class="button"><i class='bx bx-filter' ></i></button>
                </div>
                <div class="filter_menu" style="display: none;">
                <!-- Add your filter options here -->
                <label>
                    <input type="checkbox" name="filter" value="price"> Price
                </label>
                <label>
                    <input type="checkbox" name="filter" value="rating"> Rating
                </label>
                <label>
                    <input type="checkbox" name="filter" value="category"> Category
                </label>
                <button id="applyFilters">Apply Filters</button>
            </div>
            </div>

            <div class="moreproduct_container">
                <div class="moreproduct_content">
                    <br>
                    
                    <br>
                </div>
            </div>
        </section>
        <?php include 'popover.php'; ?>
    </main>
    <?php include 'footer.php';?>
    
      <script src="../js/moreproduct.js"></script>
      <script src="../js/Logoutpopover.js"></script>
</body>
</html>