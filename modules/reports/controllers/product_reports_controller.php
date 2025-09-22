<?php
/**
 * Product Reports Controller
 * Handle product performance and category reports
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ReportModel.php';

requireLogin();
requirePermission('view_product_reports');

$reportModel = new ReportModel();

// Handle AJAX refresh request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
    if ($_GET['action'] === 'refresh') {
        if ($reportModel->refreshMaterializedViews()) {
            jsonResponse(['success' => true]);
        } else {
            jsonResponse(['success' => false, 'error' => 'Failed to refresh data'], 500);
        }
    }
}

// Get report data
$productPerformance = $reportModel->getProductPerformance();
$categorySummary = $reportModel->getCategorySummary();
$lowStockProducts = $reportModel->getLowStockProducts();

// Get low stock threshold for the view
try {
    $db = Database::getInstance();
    $thresholdResult = $db->fetch("SELECT setting_value FROM settings WHERE setting_key = 'low_stock_threshold'");
    $lowStockThreshold = $thresholdResult ? (int)$thresholdResult['setting_value'] : 10;
} catch (Exception $e) {
    $lowStockThreshold = 10; // Default fallback
}

$pageTitle = __('view_product_reports');

include __DIR__ . '/../views/product_reports.php';