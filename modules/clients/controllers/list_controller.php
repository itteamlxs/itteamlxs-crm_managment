<?php
/**
 * Client List Controller
 * Handles client listing with pagination, search and permissions
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/ClientModel.php';

// Check permissions
requirePermission('view_clients');

$clientModel = new ClientModel();

// Get parameters
$page = (int)($_GET['page'] ?? 1);
$limit = (int)($_GET['limit'] ?? 10);
$search = sanitizeInput($_GET['search'] ?? '');
$orderBy = sanitizeOrderBy($_GET['order'] ?? 'created_at DESC', ['company_name', 'contact_name', 'email', 'created_at']);

// Validate pagination
$pagination = validatePagination($page, $limit, 50);

// Get clients data
$result = $clientModel->getClientsList($pagination['page'], $pagination['limit'], $search, $orderBy);

// Check for AJAX request
if (isAjaxRequest()) {
    jsonResponse([
        'success' => true,
        'data' => $result['data'],
        'total' => $result['total'],
        'page' => $result['page'],
        'pages' => $result['pages']
    ]);
}

// Include the view
include __DIR__ . '/../views/list.php';