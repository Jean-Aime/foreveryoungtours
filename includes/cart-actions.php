<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to continue']);
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];

header('Content-Type: application/json');

switch($action) {
    case 'add_to_cart':
        addToCart($pdo, $user_id);
        break;
    case 'update_cart':
        updateCart($pdo, $user_id);
        break;
    case 'remove_from_cart':
        removeFromCart($pdo, $user_id);
        break;
    case 'get_cart':
        getCart($pdo, $user_id);
        break;
    case 'get_cart_count':
        getCartCount($pdo, $user_id);
        break;
    case 'add_to_wishlist':
        addToWishlist($pdo, $user_id);
        break;
    case 'remove_from_wishlist':
        removeFromWishlist($pdo, $user_id);
        break;
    case 'get_wishlist':
        getWishlist($pdo, $user_id);
        break;
    case 'clear_cart':
        clearCart($pdo, $user_id);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function addToCart($pdo, $user_id) {
    try {
        $product_id = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            return;
        }
        
        // Check if product exists and is in stock
        $stmt = $pdo->prepare("SELECT id, name, price, stock_quantity, stock_status FROM store_products WHERE id = ? AND status = 'active'");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            return;
        }
        
        if ($product['stock_status'] === 'out_of_stock' || $product['stock_quantity'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Product is out of stock']);
            return;
        }
        
        // Check if product already in cart
        $stmt = $pdo->prepare("SELECT id, quantity FROM shopping_cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update quantity
            $new_quantity = $existing['quantity'] + $quantity;
            if ($new_quantity > $product['stock_quantity']) {
                echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
                return;
            }
            
            $stmt = $pdo->prepare("UPDATE shopping_cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $existing['id']]);
        } else {
            // Add new item
            $stmt = $pdo->prepare("INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        
        // Get cart count
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart_count = $stmt->fetch()['count'];
        
        echo json_encode([
            'success' => true, 
            'message' => 'Product added to cart',
            'cart_count' => $cart_count
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function updateCart($pdo, $user_id) {
    try {
        $cart_id = $_POST['cart_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;
        
        if (!$cart_id) {
            echo json_encode(['success' => false, 'message' => 'Cart ID is required']);
            return;
        }
        
        if ($quantity < 1) {
            removeFromCart($pdo, $user_id);
            return;
        }
        
        // Check stock availability
        $stmt = $pdo->prepare("
            SELECT p.stock_quantity 
            FROM shopping_cart c 
            JOIN store_products p ON c.product_id = p.id 
            WHERE c.id = ? AND c.user_id = ?
        ");
        $stmt->execute([$cart_id, $user_id]);
        $item = $stmt->fetch();
        
        if (!$item) {
            echo json_encode(['success' => false, 'message' => 'Cart item not found']);
            return;
        }
        
        if ($quantity > $item['stock_quantity']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
            return;
        }
        
        $stmt = $pdo->prepare("UPDATE shopping_cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Cart updated']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function removeFromCart($pdo, $user_id) {
    try {
        $cart_id = $_POST['cart_id'] ?? $_GET['cart_id'] ?? null;
        
        if (!$cart_id) {
            echo json_encode(['success' => false, 'message' => 'Cart ID is required']);
            return;
        }
        
        $stmt = $pdo->prepare("DELETE FROM shopping_cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function getCart($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                c.id as cart_id,
                c.quantity,
                p.id as product_id,
                p.name,
                p.price,
                p.image_url,
                p.stock_quantity,
                p.stock_status,
                (c.quantity * p.price) as subtotal
            FROM shopping_cart c
            JOIN store_products p ON c.product_id = p.id
            WHERE c.user_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll();
        
        $total = array_sum(array_column($items, 'subtotal'));
        
        echo json_encode([
            'success' => true,
            'items' => $items,
            'total' => $total,
            'count' => count($items)
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function getCartCount($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $count = $stmt->fetch()['count'];
        
        echo json_encode(['success' => true, 'count' => $count]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function addToWishlist($pdo, $user_id) {
    try {
        $product_id = $_POST['product_id'] ?? null;
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            return;
        }
        
        // Check if already in wishlist
        $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Product already in wishlist']);
            return;
        }
        
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $product_id]);
        
        echo json_encode(['success' => true, 'message' => 'Added to wishlist']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function removeFromWishlist($pdo, $user_id) {
    try {
        $product_id = $_POST['product_id'] ?? $_GET['product_id'] ?? null;
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            return;
        }
        
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        
        echo json_encode(['success' => true, 'message' => 'Removed from wishlist']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function getWishlist($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                w.id as wishlist_id,
                p.id as product_id,
                p.name,
                p.price,
                p.original_price,
                p.image_url,
                p.stock_status,
                p.rating
            FROM wishlist w
            JOIN store_products p ON w.product_id = p.id
            WHERE w.user_id = ?
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'items' => $items,
            'count' => count($items)
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

function clearCart($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Cart cleared']);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
