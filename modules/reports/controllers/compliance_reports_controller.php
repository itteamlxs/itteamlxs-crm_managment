<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../modules/reports/models/ReportModel.php';

// Security checks
requireLogin();
requirePermission('view_reports');

$reportModel = new ReportModel();
$user = getCurrentUser();
$isAdmin = $user['is_admin'] ?? false;

// Get filters from request
$filters = [
    'entity_type' => sanitizeInput($_GET['entity_type'] ?? ''),
    'action' => sanitizeInput($_GET['action'] ?? ''),
    'start_date' => sanitizeInput($_GET['start_date'] ?? ''),
    'end_date' => sanitizeInput($_GET['end_date'] ?? '')
];

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = min(100, max(10, (int)($_GET['limit'] ?? 50)));
$offset = ($page - 1) * $limit;

// Get data
$auditLogs = $reportModel->getAuditLogs($limit, $offset, $filters);
$totalRecords = $reportModel->getAuditLogsCount($filters);
$totalPages = ceil($totalRecords / $limit);

$pagination = [
    'current_page' => $page,
    'total_pages' => $totalPages,
    'total_records' => $totalRecords,
    'limit' => $limit
];

// Get filter options
$entityTypes = $reportModel->getEntityTypes();
$actions = $reportModel->getActions();

// Get security posture and user activities (admin only)
$securityPosture = $reportModel->getSecurityPosture();
$userActivities = $isAdmin ? $reportModel->getUserActivities() : [];

// Page title
$pageTitle = __('compliance_reports');

// Include the view
include __DIR__ . '/../views/compliance_reports.php';