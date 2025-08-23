<?php
/**
 * Categories Controller
 * Handles product categories management
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
        requirePermission('manage_products');
        
        $action = $_POST['action'] ?? '';
        
        if ($action === 'create_category') {
            $categoryName = sanitizeInput($_POST['category_name'] ?? '');
            $description = sanitizeInput($_POST['description'] ?? '');
            
            // Validation
            if (empty($categoryName)) {
                $error = 'Category name is required';
            } else {
                if ($productModel->createCategory($categoryName, $description)) {
                    $success = 'Category created successfully';
                } else {
                    $error = 'Error creating category';
                }
            }
        }
        
        if ($action === 'update_category') {
            $categoryId = (int)($_POST['category_id'] ?? 0);
            $categoryName = sanitizeInput($_POST['category_name'] ?? '');
            $description = sanitizeInput($_POST['description'] ?? '');
            
            if (empty($categoryName) || $categoryId <= 0) {
                $error = 'Invalid category data';
            } else {
                if ($productModel->updateCategory($categoryId, $categoryName, $description)) {
                    $success = 'Category updated successfully';
                } else {
                    $error = 'Error updating category';
                }
            }
        }
        
        if ($action === 'delete_category') {
            $categoryId = (int)($_POST['category_id'] ?? 0);
            
            if ($categoryId <= 0) {
                $error = 'Invalid category ID';
            } else {
                if ($productModel->deleteCategory($categoryId)) {
                    $success = 'Category deleted successfully';
                } else {
                    $error = 'Cannot delete category with products';
                }
            }
        }
    }
}

// Get categories
$categories = $productModel->getCategories();

// Get category for editing if specified
$editCategory = null;
if (isset($_GET['edit_id'])) {
    $editCategory = $productModel->getCategoryById((int)$_GET['edit_id']);
}

// Include view
include __DIR__ . '/../views/categories.php';