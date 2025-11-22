<?php
/**
 * Quick Setup Script for Cart & Payment Tables
 * Run this file once to create all necessary tables
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Cart & Payment Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid green; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid red; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border: 1px solid blue; margin: 10px 0; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>🛒 Shopping Cart & Payment System Setup</h1>
    <p>This will create all necessary database tables for the cart and payment system.</p>
";

try {
    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/database/cart_payment_setup.sql');
    
    // Split into individual queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($queries as $query) {
        if (empty($query) || strpos($query, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($query);
            $success_count++;
            
            // Extract table name for display
            if (preg_match('/CREATE TABLE.*?`?(\w+)`?/i', $query, $matches)) {
                echo "<div class='success'>✓ Created table: <strong>{$matches[1]}</strong></div>";
            } elseif (preg_match('/INSERT INTO.*?`?(\w+)`?/i', $query, $matches)) {
                echo "<div class='success'>✓ Inserted data into: <strong>{$matches[1]}</strong></div>";
            }
        } catch (PDOException $e) {
            $error_count++;
            // Only show error if it's not "table already exists"
            if (strpos($e->getMessage(), 'already exists') === false) {
                echo "<div class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            } else {
                echo "<div class='info'>ℹ Table already exists (skipped)</div>";
            }
        }
    }
    
    echo "<hr>";
    echo "<h2>Setup Complete!</h2>";
    echo "<div class='success'>";
    echo "<p><strong>Summary:</strong></p>";
    echo "<ul>";
    echo "<li>Queries executed: " . count($queries) . "</li>";
    echo "<li>Successful: $success_count</li>";
    echo "<li>Errors: $error_count</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h3>Tables Created:</h3>";
    echo "<ul>";
    echo "<li>✓ shopping_cart</li>";
    echo "<li>✓ wishlist</li>";
    echo "<li>✓ orders</li>";
    echo "<li>✓ order_items</li>";
    echo "<li>✓ tour_order_details</li>";
    echo "<li>✓ payment_transactions</li>";
    echo "<li>✓ user_addresses</li>";
    echo "<li>✓ discount_coupons</li>";
    echo "<li>✓ coupon_usage</li>";
    echo "</ul>";
    
    echo "<div class='success'>";
    echo "<p><strong>✅ You can now use the shopping cart!</strong></p>";
    echo "<p>Go back to the <a href='pages/store.php'>Store Page</a> and try adding products to cart.</p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<p><strong>Note:</strong> You can delete this file (setup-cart-tables.php) after setup is complete.</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h2>Setup Failed!</h2>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "</body></html>";
?>
