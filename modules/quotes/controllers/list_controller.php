<?php
/**
 * Quotes List Controller
 * Handles quote listing with filters, pagination, and security
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../models/QuoteModel.php';

// Require login and permission
requireLogin();
requirePermission('create_quotes');

$quoteModel = new QuoteModel();
$user = getCurrentUser();
$error = '';
$success = '';

// Handle success messages from other actions
if (isset($_SESSION['quote_success'])) {
    $success = $_SESSION['quote_success'];
    unset($_SESSION['quote_success']);
}

// Handle filters
$filters = [
    'status' => sanitizeInput($_GET['status'] ?? ''),
    'client_name' => sanitizeInput($_GET['client_name'] ?? ''),
    'username' => sanitizeInput($_GET['username'] ?? ''),
    'date_from' => sanitizeInput($_GET['date_from'] ?? ''),
    'date_to' => sanitizeInput($_GET['date_to'] ?? ''),
    'order_by' => sanitizeInput($_GET['order_by'] ?? 'issue_date DESC')
];

// Non-admin users can only see their own quotes
if (!$user['is_admin']) {
    $filters['username'] = $user['username'];
}

// Handle pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = min(100, max(10, (int)($_GET['limit'] ?? 20)));
$pagination = validatePagination($page, $limit);

// Get quotes and total count
$quotes = $quoteModel->getAllQuotes($filters, $pagination);
$totalQuotes = $quoteModel->getQuoteCount($filters);

// Calculate pagination
$totalPages = ceil($totalQuotes / $limit);

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['error' => 'Invalid security token'], 403);
    }
    
    $action = sanitizeInput($_POST['action'] ?? '');
    $quoteId = (int)($_POST['quote_id'] ?? 0);
    
    // Verify quote ownership or admin rights
    $quote = $quoteModel->getQuoteById($quoteId);
    if (!$quote || (!$user['is_admin'] && $quote['username'] !== $user['username'])) {
        jsonResponse(['error' => 'Quote not found or access denied'], 404);
    }
    
    switch ($action) {
        case 'approve':
            if (!hasPermission('create_quotes')) {
                jsonResponse(['error' => 'Permission denied'], 403);
            }
            
            // Check stock availability
            $stockCheck = $quoteModel->checkStockAvailability($quoteId);
            if (!$stockCheck['available']) {
                jsonResponse([
                    'error' => 'Insufficient stock',
                    'stock_issues' => $stockCheck['issues']
                ], 400);
            }
            
            if ($quoteModel->approveQuote($quoteId)) {
                jsonResponse(['success' => true, 'message' => 'Quote approved successfully']);
            } else {
                jsonResponse(['error' => 'Failed to approve quote'], 500);
            }
            break;
            
        case 'update_status':
            $newStatus = sanitizeInput($_POST['status'] ?? '');
            $allowedStatuses = ['DRAFT', 'SENT', 'REJECTED'];
            
            if (!in_array($newStatus, $allowedStatuses)) {
                jsonResponse(['error' => 'Invalid status'], 400);
            }
            
            // Get current quote details
            $currentQuote = $quoteModel->getQuoteForEdit($quoteId);
            if (!$currentQuote) {
                jsonResponse(['error' => 'Quote not found'], 404);
            }
            
            // Update quote data
            $quoteData = [
                'client_id' => $currentQuote['client_id'],
                'status' => $newStatus,
                'total_amount' => $currentQuote['total_amount'],
                'issue_date' => $currentQuote['issue_date'],
                'expiry_date' => $currentQuote['expiry_date']
            ];
            
            if ($quoteModel->updateQuote($quoteId, $quoteData, $currentQuote['items'])) {
                jsonResponse(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                jsonResponse(['error' => 'Failed to update status'], 500);
            }
            break;
            
        default:
            jsonResponse(['error' => 'Invalid action'], 400);
    }
}

// Get user statistics
$userStats = $quoteModel->getUserQuoteStats($user['user_id']);

// Get expiring quotes for notifications
$expiringQuotes = [];
if ($user['is_admin'] || hasPermission('view_sales_reports')) {
    $expiringQuotes = $quoteModel->getExpiringQuotes();
}

// Include the list view
include __DIR__ . '/../views/list.php';