<?php
require "init.php";

try {
    // Fetch the products from Stripe
    $products = $stripe->products->all();
} catch (Exception $e) {
    die('Error fetching products: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorcycle Parts Marketplace</title>
    <style>
        body {
            background-color: #111;
            color: #eee;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #fff;
            margin: 20px 0;
            font-size: 2em;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1200px;
            justify-content: center;
        }
        .product-card {
            background-color: #222;
            color: #eee;
            border: 1px solid #444;
            border-radius: 10px;
            padding: 15px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: scale(1.05);
            border-color: #fff;
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .product-name {
            font-size: 1.2em;
            font-weight: bold;
            margin: 10px 0;
        }
        .product-price {
            font-size: 1em;
            color: #ccc;
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <h1>Motorcycle Parts Marketplace</h1>
    <div class="product-container">
        <?php foreach ($products->data as $product): ?>
            <div class="product-card">
                <?php if (!empty($product->images)): ?>
                    <img src="<?php echo htmlspecialchars($product->images[0]); ?>" alt="<?php echo htmlspecialchars($product->name); ?>" class="product-image">
                <?php else: ?>
                    <img src="placeholder.jpg" alt="No image available" class="product-image">
                <?php endif; ?>
                <div class="product-name"><?php echo htmlspecialchars($product->name); ?></div>
                <div class="product-price">
                    <?php
                    // Retrieve the price for each product
                    try {
                        $price = $stripe->prices->retrieve($product->default_price);
                        echo strtoupper($price->currency) . ' ' . number_format($price->unit_amount / 100, 2);
                    } catch (Exception $e) {
                        echo "Price not available";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
