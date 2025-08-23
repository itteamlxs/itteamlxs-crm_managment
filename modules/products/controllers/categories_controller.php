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

// Check permissions - assuming manage_products permission exists
if (!hasPermission($_SESSION['user_id'], 'manage_products')) {
    header('Location: /public/index.php?error=access_denied');
    exit;
}

$database = Database::getInstance()->getConnection();
$productModel = new ProductModel($database);
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Invalid security token.';
    } else {
        // Sanitize inputs
        $categoryName = sanitizeInput($_POST['category_name'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');

        // Validate inputs
        if (empty($categoryName)) {
            $errors[] = 'Category name is required.';
        } elseif (strlen($categoryName) > 100) {
            $errors[] = 'Category name must be 100 characters or less.';
        } elseif ($productModel->categoryNameExists($categoryName)) {
            $errors[] = 'Category name already exists.';
        }

        if (strlen($description) > 1000) {
            $errors[] = 'Description must be 1000 characters or less.';
        }

        // Create category if no errors
        if (empty($errors)) {
            try {
                if ($productModel->createCategory($categoryName, $description)) {
                    $success = true;
                    // Clear form data
                    $_POST = [];
                } else {
                    $errors[] = 'Failed to create category.';
                }
            } catch (Exception $e) {
                $errors[] = 'Database error occurred.';
                error_log('Category creation error: ' . $e->getMessage());
            }
        }
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Load view
include __DIR__ . '/../views/add_categories.php';