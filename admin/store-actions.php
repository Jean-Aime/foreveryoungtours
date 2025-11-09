<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is super admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header('Location: ../auth/login.php');
    exit();
}

$action = $_REQUEST['action'] ?? '';

switch($action) {
    case 'add_product':
        addProduct();
        break;
    case 'edit_product':
        editProduct();
        break;
    case 'delete_product':
        deleteProduct();
        break;
    case 'add_category':
        addCategory();
        break;
    case 'edit_category':
        editCategory();
        break;
    case 'delete_category':
        deleteCategory();
        break;
    default:
        header('Location: store-management.php');
        exit();
}

function addProduct() {
    global $pdo;
    
    try {
        // Generate slug from name
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name'])));
        
        // Calculate discount percentage
        $discount = 0;
        if(!empty($_POST['original_price']) && $_POST['original_price'] > 0) {
            $discount = round((($_POST['original_price'] - $_POST['price']) / $_POST['original_price']) * 100);
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO store_products (
                category_id, name, slug, description, short_description,
                price, original_price, discount_percentage, image_url,
                stock_quantity, stock_status, sku, status,
                is_featured, is_on_sale
            ) VALUES (
                :category_id, :name, :slug, :description, :short_description,
                :price, :original_price, :discount_percentage, :image_url,
                :stock_quantity, :stock_status, :sku, :status,
                :is_featured, :is_on_sale
            )
        ");
        
        $stmt->execute([
            ':category_id' => $_POST['category_id'],
            ':name' => $_POST['name'],
            ':slug' => $slug,
            ':description' => $_POST['description'] ?? null,
            ':short_description' => $_POST['short_description'] ?? null,
            ':price' => $_POST['price'],
            ':original_price' => $_POST['original_price'] ?? null,
            ':discount_percentage' => $discount,
            ':image_url' => $_POST['image_url'],
            ':stock_quantity' => $_POST['stock_quantity'],
            ':stock_status' => $_POST['stock_status'],
            ':sku' => $_POST['sku'],
            ':status' => $_POST['status'],
            ':is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            ':is_on_sale' => isset($_POST['is_on_sale']) ? 1 : 0
        ]);
        
        $_SESSION['success_message'] = "Product added successfully!";
        header('Location: store-management.php');
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error adding product: " . $e->getMessage();
        header('Location: store-management.php');
        exit();
    }
}

function editProduct() {
    global $pdo;
    
    try {
        // Generate slug from name
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name'])));
        
        // Calculate discount percentage
        $discount = 0;
        if(!empty($_POST['original_price']) && $_POST['original_price'] > 0) {
            $discount = round((($_POST['original_price'] - $_POST['price']) / $_POST['original_price']) * 100);
        }
        
        $stmt = $pdo->prepare("
            UPDATE store_products SET
                category_id = :category_id,
                name = :name,
                slug = :slug,
                description = :description,
                short_description = :short_description,
                price = :price,
                original_price = :original_price,
                discount_percentage = :discount_percentage,
                image_url = :image_url,
                stock_quantity = :stock_quantity,
                stock_status = :stock_status,
                sku = :sku,
                status = :status,
                is_featured = :is_featured,
                is_on_sale = :is_on_sale
            WHERE id = :id
        ");
        
        $stmt->execute([
            ':id' => $_POST['product_id'],
            ':category_id' => $_POST['category_id'],
            ':name' => $_POST['name'],
            ':slug' => $slug,
            ':description' => $_POST['description'] ?? null,
            ':short_description' => $_POST['short_description'] ?? null,
            ':price' => $_POST['price'],
            ':original_price' => $_POST['original_price'] ?? null,
            ':discount_percentage' => $discount,
            ':image_url' => $_POST['image_url'],
            ':stock_quantity' => $_POST['stock_quantity'],
            ':stock_status' => $_POST['stock_status'],
            ':sku' => $_POST['sku'],
            ':status' => $_POST['status'],
            ':is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            ':is_on_sale' => isset($_POST['is_on_sale']) ? 1 : 0
        ]);
        
        $_SESSION['success_message'] = "Product updated successfully!";
        header('Location: store-management.php');
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error updating product: " . $e->getMessage();
        header('Location: store-management.php');
        exit();
    }
}

function deleteProduct() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM store_products WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        
        $_SESSION['success_message'] = "Product deleted successfully!";
        header('Location: store-management.php');
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error deleting product: " . $e->getMessage();
        header('Location: store-management.php');
        exit();
    }
}

function addCategory() {
    global $pdo;
    
    try {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name'])));
        
        $stmt = $pdo->prepare("
            INSERT INTO store_categories (name, slug, description, color, status, display_order)
            VALUES (:name, :slug, :description, :color, :status, :display_order)
        ");
        
        $stmt->execute([
            ':name' => $_POST['name'],
            ':slug' => $slug,
            ':description' => $_POST['description'] ?? null,
            ':color' => $_POST['color'] ?? '#3B82F6',
            ':status' => $_POST['status'] ?? 'active',
            ':display_order' => $_POST['display_order'] ?? 0
        ]);
        
        $_SESSION['success_message'] = "Category added successfully!";
        header('Location: store-categories.php');
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error adding category: " . $e->getMessage();
        header('Location: store-categories.php');
        exit();
    }
}

function editCategory() {
    global $pdo;
    
    try {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name'])));
        
        $stmt = $pdo->prepare("
            UPDATE store_categories SET
                name = :name,
                slug = :slug,
                description = :description,
                color = :color,
                status = :status,
                display_order = :display_order
            WHERE id = :id
        ");
        
        $stmt->execute([
            ':id' => $_POST['id'],
            ':name' => $_POST['name'],
            ':slug' => $slug,
            ':description' => $_POST['description'] ?? null,
            ':color' => $_POST['color'] ?? '#3B82F6',
            ':status' => $_POST['status'] ?? 'active',
            ':display_order' => $_POST['display_order'] ?? 0
        ]);
        
        $_SESSION['success_message'] = "Category updated successfully!";
        header('Location: store-categories.php');
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error updating category: " . $e->getMessage();
        header('Location: store-categories.php');
        exit();
    }
}

function deleteCategory() {
    global $pdo;
    
    try {
        // Check if category has products
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM store_products WHERE category_id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        $count = $stmt->fetchColumn();
        
        if($count > 0) {
            $_SESSION['error_message'] = "Cannot delete category with existing products. Please reassign or delete products first.";
            header('Location: store-categories.php');
            exit();
        }
        
        $stmt = $pdo->prepare("DELETE FROM store_categories WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        
        $_SESSION['success_message'] = "Category deleted successfully!";
        header('Location: store-categories.php');
        exit();
        
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error deleting category: " . $e->getMessage();
        header('Location: store-categories.php');
        exit();
    }
}
?>
