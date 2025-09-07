<?php
/**
 * Compliance Reports Controller
 * Handle audit logs and security posture reports
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ReportModel.php';

requireLogin();
requirePermission('view_compliance_reports');

$reportModel = new ReportModel();
$pageTitle = 'Reportes de Cumplimiento';

// Handle AJAX refresh request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest() && isset($_GET['action']) && $_GET['action'] === 'refresh') {
    $refreshResult = $reportModel->refreshMaterializedViews();
    jsonResponse(['success' => $refreshResult]);
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 50;
$offset = ($page - 1) * $limit;

// Get report data with proper error handling
try {
    $auditLogs = $reportModel->getAuditLogs($limit, $offset);
    $securityPosture = $reportModel->getSecurityPosture();
    $userActivities = $reportModel->getUserActivities();
} catch (Exception $e) {
    logError("Error loading compliance reports: " . $e->getMessage());
    $auditLogs = [];
    $securityPosture = [];
    $userActivities = [];
}

// Check if user has admin role for detailed view
$currentUser = getCurrentUser();
$isAdmin = $currentUser['is_admin'] ?? false;

// Ensure all variables are set for the view
$securityPosture = $securityPosture ?: [
    'failed_login_count' => 0,
    'locked_accounts' => 0,
    'inactive_accounts' => 0,
    'permission_changes' => 0,
    'audit_log_count' => 0,
    'last_security_event' => null
];

include __DIR__ . '/../views/compliance_reports.php';