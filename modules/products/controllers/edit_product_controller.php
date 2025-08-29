<?php
/**
 * Edit Product Controller
 * Handles product editing
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ProductModel.php';

requireLogin();

$productModel = new ProductModel();
$productId = (int)($_GET['id'] ?? 0);

if ($productId <= 0) {
    redirect(url('products', 'list'));
}

// Get product data
try {
    $product = $productModel->getProductById($productId);
    if (!$product) {
        redirect(url('products', 'list'));
    }
} catch (Exception $e) {
    $error = __('error_loading_product');
    logError("Product loading failed: " . $e->getMessage());
    redirect(url('products', 'list'));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        // Get and sanitize input data
        $productName = sanitizeInput($_POST['product_name'] ?? '');
        $sku = sanitizeInput($_POST['sku'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $taxRate = (float)($_POST['tax_rate'] ?? 0);
        $stockQuantity = (int)($_POST['stock_quantity'] ?? 0);
        
        // Validation
        $errors = [];
        
        if (empty($productName)) {
            $errors[] = __('product_name_required');
        }
        
        if (empty($sku)) {
            $errors[] = __('sku_required');
        } elseif (!preg_match('/^[a-zA-Z0-9_-]{1,50}$/', $sku)) {
            $errors[] = __('invalid_sku_format');
        } elseif ($productModel->skuExists($sku, $productId)) {
            $errors[] = __('sku_already_exists');
        }
        
        if ($categoryId <= 0) {
            $errors[] = __('category_required');
        }
        
        if ($price <= 0) {
            $errors[] = __('price_required');
        }
        
        if ($stockQuantity < 0) {
            $errors[] = __('invalid_stock_quantity');
        }
        
        if ($taxRate < 0 || $taxRate > 100) {
            $errors[] = __('invalid_tax_rate');
        }
        
        if (empty($errors)) {
            try {
                $data = [
                    'product_name' => $productName,
                    'sku' => strtoupper($sku),
                    'category_id' => $categoryId,
                    'price' => $price,
                    'tax_rate' => $taxRate,
                    'stock_quantity' => $stockQuantity
                ];
                
                $productModel->updateProduct($productId, $data);
                redirect(url('products', 'list') . '&success=' . urlencode(__('product_updated_successfully')));
                
            } catch (Exception $e) {
                $error = __('error_updating_product');
                logError("Product update failed: " . $e->getMessage());
            }
        } else {
            $error = implode('<br>', $errors);
        }
    }
}

// Get categories for dropdown
try {
    $categories = $productModel->getCategories();
} catch (Exception $e) {
    $error = __('error_loading_categories');
    logError("Categories loading failed: " . $e->getMessage());
    $categories = [];
}

// Include the view
require_once __DIR__ . '/../views/edit_product.php';