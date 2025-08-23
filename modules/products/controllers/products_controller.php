<?php
/**
 * Products Controller
 * Maneja listado, creación, edición y eliminación de productos
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../models/ProductModel.php';

// Verificar acceso al módulo
requireLogin();
requirePermission('view_products');

$productModel = new ProductModel();
$error = '';
$success = '';

// Determinar acción
$action = sanitizeInput($_GET['sub_action'] ?? 'list');
$productId = (int)($_GET['id'] ?? 0);

// Procesar formularios POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $formAction = sanitizeInput($_POST['action'] ?? '');
        
        switch ($formAction) {
            case 'create_product':
                if (hasPermission('create_products')) {
                    $result = handleCreateProduct($productModel, $_POST);
                    if ($result === true) {
                        $success = __('product_created_successfully');
                    } else {
                        $error = $result;
                    }
                } else {
                    $error = __('access_denied');
                }
                break;
                
            case 'update_product':
                if (hasPermission('edit_products')) {
                    $result = handleUpdateProduct($productModel, $_POST, $productId);
                    if ($result === true) {
                        $success = __('product_updated_successfully');
                    } else {
                        $error = $result;
                    }
                } else {
                    $error = __('access_denied');
                }
                break;
                
            case 'delete_product':
                if (hasPermission('delete_products')) {
                    $result = $productModel->deleteProduct($productId);
                    if ($result === true) {
                        $success = __('product_deleted_successfully');
                        $action = 'list'; // Redirect to list
                    } else {
                        $error = is_array($result) ? $result['error'] : __('error_deleting_product');
                    }
                } else {
                    $error = __('access_denied');
                }
                break;
        }
    }
}

/**
 * Manejar creación de producto
 */
function handleCreateProduct($model, $data) {
    // Validaciones
    if (empty($data['product_name'])) {
        return __('product_name_required');
    }
    
    if (empty($data['sku'])) {
        return __('sku_required');
    }
    
    if (!is_numeric($data['price']) || $data['price'] < 0) {
        return __('invalid_price');
    }
    
    if (!is_numeric($data['stock_quantity']) || $data['stock_quantity'] < 0) {
        return __('invalid_stock_quantity');
    }
    
    $productData = [
        'category_id' => (int)$data['category_id'],
        'product_name' => sanitizeInput($data['product_name']),
        'sku' => sanitizeInput($data['sku']),
        'price' => (float)$data['price'],
        'tax_rate' => (float)($data['tax_rate'] ?? 0),
        'stock_quantity' => (int)$data['stock_quantity'],
        'min_stock_level' => (int)($data['min_stock_level'] ?? 10),
        'description' => sanitizeInput($data['description'] ?? '')
    ];
    
    $result = $model->createProduct($productData);
    return $result ? true : __('error_creating_product');
}

/**
 * Manejar actualización de producto
 */
function handleUpdateProduct($model, $data, $productId) {
    // Validaciones similares a crear
    if (empty($data['product_name'])) {
        return __('product_name_required');
    }
    
    if (empty($data['sku'])) {
        return __('sku_required');
    }
    
    if (!is_numeric($data['price']) || $data['price'] < 0) {
        return __('invalid_price');
    }
    
    if (!is_numeric($data['stock_quantity']) || $data['stock_quantity'] < 0) {
        return __('invalid_stock_quantity');
    }
    
    $productData = [
        'category_id' => (int)$data['category_id'],
        'product_name' => sanitizeInput($data['product_name']),
        'sku' => sanitizeInput($data['sku']),
        'price' => (float)$data['price'],
        'tax_rate' => (float)($data['tax_rate'] ?? 0),
        'stock_quantity' => (int)$data['stock_quantity'],
        'min_stock_level' => (int)($data['min_stock_level'] ?? 10),
        'description' => sanitizeInput($data['description'] ?? '')
    ];
    
    $result = $model->updateProduct($productId, $productData);
    return $result ? true : __('error_updating_product');
}

// Obtener datos según la acción
switch ($action) {
    case 'list':
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 10);
        $search = sanitizeInput($_GET['search'] ?? '');
        $categoryId = (int)($_GET['category_id'] ?? 0) ?: null;
        
        $productsData = $productModel->getProducts($page, $limit, $search, $categoryId);
        $categories = $productModel->getCategories();
        $lowStockProducts = $productModel->getLowStockProducts();
        break;
        
    case 'add':
        if (!hasPermission('create_products')) {
            redirect(url('products', 'list'));
        }
        $categories = $productModel->getCategories();
        break;
        
    case 'edit':
        if (!hasPermission('edit_products')) {
            redirect(url('products', 'list'));
        }
        
        $product = $productModel->getProductById($productId);
        if (!$product) {
            redirect(url('products', 'list'));
        }
        $categories = $productModel->getCategories();
        break;
        
    default:
        redirect(url('products', 'list'));
        break;
}

// Incluir vista
include __DIR__ . "/../views/products_{$action}.php";