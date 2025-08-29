<?php
/**
 * Categories Controller
 * Handles product category management
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ProductModel.php';

requireLogin();

$productModel = new ProductModel();

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $action = $_POST['action'] ?? '';
        
        try {
            switch ($action) {
                case 'create':
                    $categoryName = sanitizeInput($_POST['category_name'] ?? '');
                    $description = sanitizeInput($_POST['description'] ?? '');
                    
                    if (empty($categoryName)) {
                        $error = __('category_name_required');
                    } elseif ($productModel->categoryNameExists($categoryName)) {
                        $error = __('category_name_already_exists');
                    } else {
                        $data = [
                            'category_name' => $categoryName,
                            'description' => $description
                        ];
                        $productModel->createCategory($data);
                        $success = __('category_created_successfully');
                    }
                    break;
                    
                case 'update':
                    $categoryId = (int)($_POST['category_id'] ?? 0);
                    $categoryName = sanitizeInput($_POST['category_name'] ?? '');
                    $description = sanitizeInput($_POST['description'] ?? '');
                    
                    if (empty($categoryName)) {
                        $error = __('category_name_required');
                    } elseif ($productModel->categoryNameExists($categoryName, $categoryId)) {
                        $error = __('category_name_already_exists');
                    } else {
                        $data = [
                            'category_name' => $categoryName,
                            'description' => $description
                        ];
                        $productModel->updateCategory($categoryId, $data);
                        $success = __('category_updated_successfully');
                    }
                    break;
                    
                case 'delete':
                    $categoryId = (int)($_POST['category_id'] ?? 0);
                    if ($categoryId > 0) {
                        $productModel->deleteCategory($categoryId);
                        $success = __('category_deleted_successfully');
                    }
                    break;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Get categories data
try {
    $categories = $productModel->getCategories();
    $categorySummary = $productModel->getCategorySummary();
    
} catch (Exception $e) {
    $error = __('error_loading_categories');
    logError("Categories loading failed: " . $e->getMessage());
    $categories = [];
    $categorySummary = [];
}

// Include the view
require_once __DIR__ . '/../views/categories.php';