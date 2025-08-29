<?php
/**
 * Quote List Controller
 * Handle quote listing with pagination and search
 */

require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Check permissions
requirePermission('view_clients');

$quoteModel = new QuoteModel();
$currentUser = getCurrentUser();

// Get search parameters
$search = sanitizeInput($_GET['search'] ?? '');
$status = sanitizeInput($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = min(50, max(10, (int)($_GET['limit'] ?? 10)));

// Get quotes
$quotes = $quoteModel->getQuotes($search, $status, $page, $limit);
$totalCount = $quoteModel->getQuotesCount($search, $status);

// Calculate pagination
$totalPages = ceil($totalCount / $limit);
$hasNext = $page < $totalPages;
$hasPrev = $page > 1;

// Status options for filter
$statusOptions = [
    '' => 'all_statuses',
    'DRAFT' => 'draft',
    'SENT' => 'sent',
    'APPROVED' => 'approved',
    'REJECTED' => 'rejected'
];

// Check permissions for actions
$canCreateQuotes = hasPermission('create_quotes');
$canRenewQuotes = hasPermission('renew_quotes');

// Include the view
require_once __DIR__ . '/../views/list.php';