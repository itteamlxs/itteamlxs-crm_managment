<?php
/**
 * Client Reports Controller
 * Handle client activity and purchase patterns reports
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ReportModel.php';

requireLogin();
requirePermission('view_client_reports');

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
$clientActivity = $reportModel->getClientActivity();
$clientPurchasePatterns = $reportModel->getClientPurchasePatterns();
$topClients = $reportModel->getTopClients(10);

$pageTitle = __('view_client_reports');

include __DIR__ . '/../views/client_reports.php';