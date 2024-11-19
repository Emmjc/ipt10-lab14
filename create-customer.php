<?php

require "init.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration Result</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1c1c1c;
            color: #f4f4f4;
            margin: 0;
        }

        /* Container for Messages */
        .container {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
            border: 1px solid #444;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 15px;
        }

        /* Styling for Customer Details */
        .customer-info {
            margin: 15px 0;
            padding: 10px;
            background-color: #333;
            border-radius: 5px;
        }

        .customer-info p {
            margin: 8px 0;
            font-size: 1em;
            color: #ddd;
        }

        /* Error Message Styling */
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $line1 = $_POST['line1'];
            $line2 = $_POST['line2'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            $postal_code = $_POST['postal_code'];

            try {
                // Create the customer in Stripe
                $customer = $stripe->customers->create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => [
                        'line1' => $line1,
                        'line2' => $line2,
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                        'postal_code' => $postal_code,
                    ],
                ]);

                echo "<h2>Customer Created Successfully</h2>";
                echo "<div class='customer-info'>";
                echo "<p><strong>ID:</strong> " . htmlspecialchars($customer->id) . "</p>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($customer->name) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($customer->email) . "</p>";
                echo "<p><strong>Phone:</strong> " . htmlspecialchars($customer->phone) . "</p>";
                echo "</div>";

            } catch (Exception $e) {
                echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>
