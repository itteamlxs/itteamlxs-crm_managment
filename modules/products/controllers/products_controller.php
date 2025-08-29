<?php
/**
 * Products Controller
 * Handles product listing and management
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ProductModel.php';

requireLogin();

$productModel = new ProductModel();

// Get search and filter parameters
$search = sanitizeInput($_GET['search'] ?? '');
$category_id = (int)($_GET['category_id'] ?? 0);
$page = (int)($_GET['page'] ?? 1);
$limit = (int)($_GET['limit'] ?? 10);
$orderBy = sanitizeInput($_GET['order'] ?? 'product_name ASC');

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $action = $_POST['action'] ?? '';
        
        try {
            switch ($action) {
                case 'delete':
                    $productId = (int)($_POST['product_id'] ?? 0);
                    if ($productId > 0) {
                        $productModel->deleteProduct($productId);
                        $success = __('product_deleted_successfully');
                    }
                    break;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Get products data
try {
    $productsData = $productModel->getProducts($page, $limit, $search, $category_id ?: null, $orderBy);
    $products = $productsData['products'];
    $total = $productsData['total'];
    $totalPages = $productsData['pages'];
    
    // Get categories for filter
    $categories = $productModel->getCategories();
    
    // Get low stock products for warnings
    $lowStockProducts = $productModel->getLowStockProducts();
    
} catch (Exception $e) {
    $error = __('error_loading_products');
    logError("Products loading failed: " . $e->getMessage());
    $products = [];
    $categories = [];
    $lowStockProducts = [];
    $total = 0;
    $totalPages = 0;
}

// Include the view
require_once __DIR__ . '/../views/products.php';