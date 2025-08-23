<?php
/**
 * Categories Controller
 * Maneja listado, creación, edición y eliminación de categorías
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
$categoryId = (int)($_GET['id'] ?? 0);

// Procesar formularios POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $formAction = sanitizeInput($_POST['action'] ?? '');
        
        switch ($formAction) {
            case 'create_category':
                if (hasPermission('create_products')) {
                    $result = handleCreateCategory($productModel, $_POST);
                    if ($result === true) {
                        $success = __('category_created_successfully');
                    } else {
                        $error = $result;
                    }
                } else {
                    $error = __('access_denied');
                }
                break;
                
            case 'update_category':
                if (hasPermission('edit_products')) {
                    $result = handleUpdateCategory($productModel, $_POST, $categoryId);
                    if ($result === true) {
                        $success = __('category_updated_successfully');
                    } else {
                        $error = $result;
                    }
                } else {
                    $error = __('access_denied');
                }
                break;
                
            case 'delete_category':
                if (hasPermission('delete_products')) {
                    $result = $productModel->deleteCategory($categoryId);
                    if ($result === true) {
                        $success = __('category_deleted_successfully');
                        $action = 'list';
                    } else {
                        $error = is_array($result) ? $result['error'] : __('error_deleting_category');
                    }
                } else {
                    $error = __('access_denied');
                }
                break;
        }
    }
}

/**
 * Manejar creación de categoría
 */
function handleCreateCategory($model, $data) {
    if (empty($data['category_name'])) {
        return __('category_name_required');
    }
    
    $categoryData = [
        'category_name' => sanitizeInput($data['category_name']),
        'description' => sanitizeInput($data['description'] ?? '')
    ];
    
    $result = $model->createCategory($categoryData);
    return $result ? true : __('error_creating_category');
}

/**
 * Manejar actualización de categoría
 */
function handleUpdateCategory($model, $data, $categoryId) {
    if (empty($data['category_name'])) {
        return __('category_name_required');
    }
    
    $categoryData = [
        'category_name' => sanitizeInput($data['category_name']),
        'description' => sanitizeInput($data['description'] ?? '')
    ];
    
    $result = $model->updateCategory($categoryId, $categoryData);
    return $result ? true : __('error_updating_category');
}

// Obtener datos según la acción
switch ($action) {
    case 'list':
        $categories = $productModel->getCategories(true); // Con estadísticas
        break;
        
    case 'add':
        if (!hasPermission('create_products')) {
            redirect(url('products', 'categories'));
        }
        break;
        
    case 'edit':
        if (!hasPermission('edit_products')) {
            redirect(url('products', 'categories'));
        }
        
        $category = $productModel->getCategoryById($categoryId);
        if (!$category) {
            redirect(url('products', 'categories'));
        }
        break;
        
    default:
        redirect(url('products', 'categories'));
        break;
}

// Incluir vista
include __DIR__ . "/../views/categories_{$action}.php";