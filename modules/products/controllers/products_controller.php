<?php
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../models/ProductModel.php';

session_start();

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: /public/index.php?module=auth&action=login');
    exit;
}

// Check permissions
if (!hasPermission($_SESSION['user_id'], 'manage_products')) {
    header('Location: /public/index.php?error=access_denied');
    exit;
}

$database = Database::getInstance()->getConnection();
$productModel = new ProductModel($database);
$errors = [];
$success = false;

// Get categories for dropdown
$categories = $productModel->getAllCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Invalid security token.';
    } else {
        // Sanitize inputs
        $categoryId = filter_var($_POST['category_id'] ?? '', FILTER_VALIDATE_INT);
        $productName = sanitizeInput($_POST['product_name'] ?? '');
        $sku = sanitizeInput($_POST['sku'] ?? '');
        $price = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);
        $taxRate = filter_var($_POST['tax_rate'] ?? '', FILTER_VALIDATE_FLOAT);
        $stockQuantity = filter_var($_POST['stock_quantity'] ?? '', FILTER_VALIDATE_INT);

        // Validate inputs
        if (!$categoryId || $categoryId <= 0) {
            $errors[] = 'Please select a valid category.';
        }

        if (empty($productName)) {
            $errors[] = 'Product name is required.';
        } elseif (strlen($productName) > 255) {
            $errors[] = 'Product name must be 255 characters or less.';
        }

        if (empty($sku)) {
            $errors[] = 'SKU is required.';
        } elseif (strlen($sku) > 50) {
            $errors[] = 'SKU must be 50 characters or less.';
        } elseif ($productModel->skuExists($sku)) {
            $errors[] = 'SKU already exists.';
        }

        if ($price === false || $price < 0) {
            $errors[] = 'Please enter a valid price.';
        } elseif ($price > 99999999.99) {
            $errors[] = 'Price cannot exceed 99,999,999.99.';
        }

        if ($taxRate === false || $taxRate < 0) {
            $errors[] = 'Please enter a valid tax rate.';
        } elseif ($taxRate > 999.99) {
            $errors[] = 'Tax rate cannot exceed 999.99%.';
        }

        if ($stockQuantity === false || $stockQuantity < 0) {
            $errors[] = 'Please enter a valid stock quantity.';
        } elseif ($stockQuantity > 2147483647) {
            $errors[] = 'Stock quantity is too large.';
        }

        // Create product if no errors
        if (empty($errors)) {
            try {
                if ($productModel->createProduct($categoryId, $productName, $sku, $price, $taxRate, $stockQuantity)) {
                    $success = true;
                    // Clear form data
                    $_POST = [];
                } else {
                    $errors[] = 'Failed to create product.';
                }
            } catch (Exception $e) {
                $errors[] = 'Database error occurred.';
                error_log('Product creation error: ' . $e->getMessage());
            }
        }
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Load view
include __DIR__ . '/../views/add_products.php';