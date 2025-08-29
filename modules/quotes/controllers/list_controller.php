<?php
/**
 * Quotes List Controller
 * Handles quote listing with filtering and pagination
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/QuoteModel.php';

// Check permissions
requireLogin();
requirePermission('view_clients');

$quoteModel = new QuoteModel();
$user = getCurrentUser();

// Handle AJAX requests
if (isAjaxRequest()) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $action = sanitizeInput($_POST['action']);
        
        if ($action === 'update_status' && hasPermission('create_quotes')) {
            if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
                jsonResponse(['error' => __('invalid_security_token')], 400);
            }
            
            $quoteId = (int)($_POST['quote_id'] ?? 0);
            $status = sanitizeInput($_POST['status'] ?? '');
            
            if (!in_array($status, ['DRAFT', 'SENT', 'APPROVED', 'REJECTED'])) {
                jsonResponse(['error' => 'Invalid status'], 400);
            }
            
            if ($quoteModel->updateQuoteStatus($quoteId, $status)) {
                jsonResponse(['success' => __('quote_status_updated_successfully')]);
            } else {
                jsonResponse(['error' => __('error_updating_quote_status')], 500);
            }
        }
    }
    exit;
}

// Get filter parameters
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = min(50, max(5, (int)($_GET['limit'] ?? 10)));
$search = sanitizeInput($_GET['search'] ?? '');
$status = sanitizeInput($_GET['status'] ?? '');
$client_id = (int)($_GET['client_id'] ?? 0);

// Filter by user for non-admin sellers
$user_filter = null;
if (!$user['is_admin'] && getUserRole() === 'Seller') {
    $user_filter = $user['user_id'];
}

// Get quotes data
$params = [
    'page' => $page,
    'limit' => $limit,
    'search' => $search,
    'status' => $status,
    'client_id' => $client_id ?: '',
    'user_id' => $user_filter
];

$result = $quoteModel->getQuotesList($params);
$quotes = $result['quotes'];
$total = $result['total'];
$totalPages = $result['total_pages'];

// Get clients for filter dropdown
$clients = $quoteModel->getActiveClients();

// CSRF Token
$csrfToken = generateCSRFToken();

// Include view
require_once __DIR__ . '/../views/list.php';