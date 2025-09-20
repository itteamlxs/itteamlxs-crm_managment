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

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $startDate = $_GET['start_date'] ?? '';
    $endDate = $_GET['end_date'] ?? '';
    $actionType = $_GET['action_type'] ?? '';
    
    $auditLogs = $reportModel->getAuditLogs(10000, 0, $startDate, $endDate, $actionType);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Fecha', 'Usuario ID', 'Usuario', 'Acción', 'Tipo Entidad', 'ID Entidad', 'IP']);
    
    foreach ($auditLogs as $log) {
        fputcsv($output, [
            $log['created_at'],
            $log['user_id'] ?? 'Sistema',
            $log['username'] ?? 'Desconocido',
            $log['action'],
            $log['entity_type'],
            $log['entity_id'],
            $log['ip_address'] ?? 'N/A'
        ]);
    }
    
    fclose($output);
    exit;
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 50;
$offset = ($page - 1) * $limit;

// Filters
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$actionType = $_GET['action_type'] ?? '';

// Get report data with proper error handling
try {
    $auditLogs = $reportModel->getAuditLogs($limit, $offset, $startDate, $endDate, $actionType);
    $totalCount = $reportModel->getAuditLogsCount($startDate, $endDate, $actionType);
    $securityPosture = $reportModel->getSecurityPosture();
    $userActivities = $reportModel->getUserActivities();
} catch (Exception $e) {
    error_log("Error loading compliance reports: " . $e->getMessage());
    $auditLogs = [];
    $totalCount = 0;
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
    'audit_log_count' => $totalCount,
    'last_security_event' => null
];

include __DIR__ . '/../views/compliance_reports.php';