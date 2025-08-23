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
}

// Get categories
$categories = $productModel->getCategories();

// Include view
include __DIR__ . '/../views/categories.php';