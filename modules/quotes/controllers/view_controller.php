<?php
/**
 * Quote View Controller
 * Display quote details with items
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

// Get quote ID from URL
$quoteId = (int)($_GET['id'] ?? 0);

if ($quoteId <= 0) {
    $_SESSION['error'] = __('invalid_quote');
    redirect(url('quotes', 'list'));
}

try {
    // Get quote details with items
    $quote = $quoteModel->getQuoteById($quoteId);
    
    if (!$quote) {
        $_SESSION['error'] = __('quote_not_found');
        redirect(url('quotes', 'list'));
    }
    
    // Get client details
    $client = $quoteModel->getClientById($quote['client_id']);
    
    // Get user details
    $user = $quoteModel->getUserById($quote['user_id']);
    
    // Check permissions for actions
    $canCreateQuotes = hasPermission('create_quotes');
    $canRenewQuotes = hasPermission('renew_quotes');
    
    // Check if quote can be approved/rejected
    $canApproveReject = $canCreateQuotes && $quote['status'] === 'SENT';
    
    // Check if quote can be sent
    $canSend = $canCreateQuotes && in_array($quote['status'], ['DRAFT']);
    
    // Check if quote can be edited
    $canEdit = $canCreateQuotes && in_array($quote['status'], ['DRAFT']);
    
    // Check if quote can be renewed
    $canRenew = $canRenewQuotes && in_array($quote['status'], ['APPROVED', 'REJECTED', 'SENT']);
    
    // Check if quote is expired
    $isExpired = $quote['status'] === 'SENT' && strtotime($quote['expiry_date']) < time();
    
} catch (Exception $e) {
    logError("Quote view error: " . $e->getMessage());
    $_SESSION['error'] = __('error_loading_quote');
    redirect(url('quotes', 'list'));
}

// Include the view
require_once __DIR__ . '/../views/view.php';