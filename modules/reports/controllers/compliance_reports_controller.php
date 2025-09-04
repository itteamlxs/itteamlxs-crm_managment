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

// Pagination
$page = (int)($_GET['page'] ?? 1);
$limit = 50;
$offset = ($page - 1) * $limit;

// Get report data
$auditLogs = $reportModel->getAuditLogs($limit, $offset);
$securityPosture = $reportModel->getSecurityPosture();

$pageTitle = __('view_compliance_reports');

include __DIR__ . '/../views/compliance_reports.php';