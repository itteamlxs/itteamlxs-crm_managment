<?php
/**
 * Products Controller
 * Handles product listing, create, edit
 */

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Require login and permissions
requireLogin();
requirePermission('view_products');

$productModel = new ProductModel();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'create_product') {
            requirePermission('manage_products');
            
            $productData = [
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'product_name' => sanitizeInput($_POST['product_name'] ?? ''),
                'sku' => sanitizeInput($_POST['sku'] ?? ''),
                'price' => (float)($_POST['price'] ?? 0),
                'tax_rate' => (float)($_POST['tax_rate'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0)
            ];
            
            // Validation
            $errors = [];
            if (empty($productData['product_name'])) $errors[] = 'Product name required';
            if (empty($productData['sku'])) $errors[] = 'SKU required';
            if ($productData['price'] <= 0) $errors[] = 'Price must be greater than 0';
            if ($productData['category_id'] <= 0) $errors[] = 'Category required';
            
            if (empty($errors)) {
                if ($productModel->createProduct($productData)) {
                    $success = 'Product created successfully';
                } else {
                    $error = 'Error creating product';
                }
            } else {
                $error = implode(', ', $errors);
            }
        }
        
        if ($action === 'edit_product') {
            requirePermission('manage_products');
            
            $productId = (int)($_POST['product_id'] ?? 0);
            $productData = [
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'product_name' => sanitizeInput($_POST['product_name'] ?? ''),
                'sku' => sanitizeInput($_POST['sku'] ?? ''),
                'price' => (float)($_POST['price'] ?? 0),
                'tax_rate' => (float)($_POST['tax_rate'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0)
            ];
            
            if ($productModel->updateProduct($productId, $productData)) {
                $success = 'Product updated successfully';
            } else {
                $error = 'Error updating product';
            }
        }
    }
}

// Get products and categories
$page = (int)($_GET['page'] ?? 1);
$search = sanitizeInput($_GET['search'] ?? '');
$productsData = $productModel->getAllProducts($page, 20, $search);
$categories = $productModel->getCategories();
$lowStockProducts = $productModel->getLowStockProducts();

// Get product for editing if specified
$editProduct = null;
if (isset($_GET['edit_id'])) {
    $editProduct = $productModel->getProductById((int)$_GET['edit_id']);
}

// Include view
include __DIR__ . '/../views/products.php';