<?php
/**
 * Reports Main Controller
 * Router for all report types - sales, clients, products, compliance
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ReportModel.php';

requireLogin();

// Get the report action
$reportAction = sanitizeInput($_GET['action'] ?? 'sales');

// Route to specific report controller based on action
switch ($reportAction) {
    case 'clients':
        requirePermission('view_client_reports');
        $reportModel = new ReportModel();
        
        // Handle AJAX refresh request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
            if (($_GET['sub_action'] ?? '') === 'refresh') {
                if ($reportModel->refreshMaterializedViews()) {
                    jsonResponse(['success' => true]);
                } else {
                    jsonResponse(['success' => false, 'error' => 'Failed to refresh data'], 500);
                }
            }
        }
        
        // Get report data
        $clientActivity = $reportModel->getClientActivity();
        $clientPurchasePatterns = $reportModel->getClientPurchasePatterns();
        $topClients = $reportModel->getTopClients(10);
        
        $pageTitle = __('view_client_reports');
        include __DIR__ . '/../views/client_reports.php';
        break;
        
    case 'products':
        requirePermission('view_product_reports');
        $reportModel = new ReportModel();
        
        // Handle AJAX refresh request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
            if ($_GET['sub_action'] === 'refresh') {
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
        
        $pageTitle = __('view_product_reports');
        include __DIR__ . '/../views/product_reports.php';
        break;
        
    case 'compliance':
        requirePermission('view_compliance_reports');
        $reportModel = new ReportModel();
        
        // Pagination
        $page = (int)($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        // Get report data
        $auditLogs = $reportModel->getAuditLogs($limit, $offset);
        $securityPosture = $reportModel->getSecurityPosture();
        
        $pageTitle = __('view_compliance_reports');
        include __DIR__ . '/../views/compliance_reports.php';
        break;
        
    case 'sales':
    default:
        requirePermission('view_sales_reports');
        $reportModel = new ReportModel();
        
        // Handle AJAX refresh request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
            if ($_GET['sub_action'] === 'refresh') {
                if ($reportModel->refreshMaterializedViews()) {
                    jsonResponse(['success' => true]);
                } else {
                    jsonResponse(['success' => false, 'error' => 'Failed to refresh data'], 500);
                }
            }
        }
        
        // Get report data
        $salesPerformance = $reportModel->getSalesPerformance();
        $salesTrends = $reportModel->getSalesTrends(12);
        
        $pageTitle = __('view_sales_reports');
        include __DIR__ . '/../views/sales_reports.php';
        break;
}