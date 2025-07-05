<?php 
require 'config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow cross-origin requests

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch categories, products, reviews, and customers
    $stmt = $pdo->prepare("
        SELECT 
            c.CategoryID AS category_id, 
            c.CategoryName AS category_name,
            p.ProductID AS product_id, 
            p.ProductName AS product_name, 
            p.Description AS product_description,
            p.Price, 
            p.images AS images,
            r.ReviewID AS review_id,
            r.Comment AS review_text,
            r.Rating AS review_rating,
            Date_FORMAT(r.ReviewDate, '%Y-%m-%d') AS review_date,
            cu.CustomerID AS customer_id,
            cu.FirstName AS customer_firstname,
            cu.LastName AS customer_lastname
        FROM productcategories c
        LEFT JOIN products p ON p.CategoryID = c.CategoryID
        LEFT JOIN reviews r ON r.ProductID = p.ProductID
        LEFT JOIN customer cu ON r.CustomerID = cu.CustomerID
        ORDER BY c.CategoryID, p.ProductID, r.ReviewID
    ");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize data into categories
    $categories = [];
    foreach ($result as $row) {
        $categoryId = $row['category_id'];
        if (!isset($categories[$categoryId])) {
            $categories[$categoryId] = [
                "id" => $row['category_id'],
                "name" => $row['category_name'],
                "products" => []
            ];
        }

        // Add products to the category
        if ($row['product_id']) {
            $productId = $row['product_id'];
            if (!isset($categories[$categoryId]['products'][$productId])) {
                // Split the images field into an array
                $imagesArray = explode(',', $row['images']);
                $processedImages = array_map(function($img) {
                    $img = trim($img);
                    if (!$img) {
                        return '../images/placeholder-image.jpg';
                    }
                    if (strpos($img, 'drive.google.com') !== false && strpos($img, 'uc?id=') === false) {
                        $img = str_replace("https://drive.google.com/open?id=", "https://lh3.googleusercontent.com/d/", $img);
                    }
                    return $img;
                }, $imagesArray);

                $categories[$categoryId]['products'][$productId] = [
                    "id" => $row['product_id'],
                    "name" => $row['product_name'],
                    "description" => $row['product_description'],
                    "price" => $row['Price'],
                    "image" => $processedImages,
                    "reviews" => [],
                    "review_count" => 0 // Initialize review count
                ];
            }

            // Add review if it exists
            if ($row['review_id']) {
                $categories[$categoryId]['products'][$productId]['reviews'][] = [
                    "id" => $row['review_id'],
                    "text" => $row['review_text'],
                    "rating" => $row['review_rating'],
                    "date" => $row['review_date'],
                    "customer" => [
                        "id" => $row['customer_id'],
                        "customer_names" => $row['customer_firstname'] . " " . $row['customer_lastname']
                    ]
                ];
                // Increment review count
                $categories[$categoryId]['products'][$productId]['review_count']++;
            }
        }
    }

    // Convert product arrays from associative to indexed
    foreach ($categories as &$category) {
        $category["products"] = array_values($category["products"]);
    }

    // Send categories as JSON
    echo json_encode(array_values($categories));
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
