<?php
require "init.php";

// Fetch products to display in the form
$products = $stripe->products->all(['limit' => 10]);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get selected products from the form
    $selected_products = $_POST['products'] ?? [];

    // Initialize line items for the payment link
    $line_items = [];

    // Add selected products as line items
    foreach ($selected_products as $product_id) {
        $product = $stripe->products->retrieve($product_id);
        $price_id = $product->default_price;

        // Add product as a line item
        $line_items[] = [
            'price' => $price_id,
            'quantity' => 1
        ];
    }

    try {
        // Create a payment link
        $payment_link = $stripe->paymentLinks->create([
            'line_items' => $line_items
        ]);

        // Redirect to the generated payment link URL
        header('Location: ' . $payment_link->url);
        exit;

    } catch (Exception $e) {
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Payment Link</title>
    <style>
        /* Reusing the black and white minimalist design from create-customer.php */
        :root {
            --primary-color: #333;
            --secondary-color: #000;
            --bg-color: #fff;
            --text-color: #000;
            --border-radius: 8px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1c;
            color: var(--text-color);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form, .invoice-success {
            max-width: 800px;
            background: var(--bg-color);
            padding: 25px;
            border-radius: var(--border-radius);
            border: 1px solid #ccc;
            width: 100%;
        }

        h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: var(--secondary-color);
            font-weight: bold;
        }

        .products {
            display: flex;
            justify-content: space-between;
            flex-wrap: nowrap; /* Prevent wrapping */
            gap: 20px;
            margin-bottom: 20px;
        }

        .product-item {
            flex: 1 1 20%; /* Adjusts the width of each product item */
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            text-align: center;
        }

        .product-item img {
            max-width: 100%;
            height: auto;
            border-radius: var(--border-radius);
        }

        button {
            display: block;
            width: 100%;
            background-color: var(--primary-color);
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        button:hover {
            background-color: #555;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .invoice-success {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background-color: var(--bg-color);
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
        }

        .invoice-success h2 {
            font-size: 24px;
            color: var(--primary-color);
        }

        .invoice-success p {
            font-size: 16px;
            margin-top: 10px;
            color: var(--secondary-color);
        }

        .invoice-success .link {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: var(--primary-color);
            color: #fff;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .invoice-success .link:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <form action="generate-payment-link.php" method="POST">
        <h2>Generate Payment Link</h2>

        <!-- Product Selection -->
        <label>Select Products:</label>
        <div class="products">
            <?php foreach ($products->data as $product): ?>
                <div class="product-item">
                    <label>
                        <input type="checkbox" name="products[]" value="<?= htmlspecialchars($product->id) ?>">
                        <img src="<?= htmlspecialchars($product->images[0]) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                        <br>
                        <?= htmlspecialchars($product->name) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Generate Payment Link Button -->
        <button type="submit">Generate Payment Link</button>
    </form>
</body>
</html>
