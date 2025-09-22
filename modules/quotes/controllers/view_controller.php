<?php
/**
 * Quote View Controller
 * Display quote details with items
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

// Get quote ID
$quoteId = (int)($_GET['id'] ?? 0);

if (empty($quoteId)) {
    redirect(url('quotes', 'list'));
}

// Get quote data
$quote = $quoteModel->getQuoteById($quoteId);

if (!$quote) {
    $_SESSION['error_message'] = __('quote_not_found');
    redirect(url('quotes', 'list'));
}

// Check if user can view this quote (non-admin sellers can only view their own quotes)
if (!$user['is_admin'] && getUserRole() === 'Seller' && $quote['user_id'] != $user['user_id']) {
    $_SESSION['error_message'] = __('access_denied');
    redirect(url('quotes', 'list'));
}

// Get quote items
$quoteItems = $quoteModel->getQuoteItems($quoteId);

// Handle status update via AJAX
if (isAjaxRequest() && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['error' => __('invalid_security_token')], 400);
    }
    
    $action = sanitizeInput($_POST['action'] ?? '');
    
    if ($action === 'update_status' && hasPermission('create_quotes')) {
        $newStatus = sanitizeInput($_POST['status'] ?? '');
        
        if (!in_array($newStatus, ['DRAFT', 'SENT', 'APPROVED', 'REJECTED'])) {
            jsonResponse(['error' => __('invalid_status')], 400);
        }
        
        // Check if status change is allowed
        $allowedTransitions = [
            'DRAFT' => ['SENT', 'REJECTED'],
            'SENT' => ['APPROVED', 'REJECTED'],
            'APPROVED' => [], // No changes allowed
            'REJECTED' => [] // No changes allowed
        ];
        
        if (!in_array($newStatus, $allowedTransitions[$quote['status']])) {
            jsonResponse(['error' => __('status_change_not_allowed')], 400);
        }
        
        // For approval, check stock availability
        if ($newStatus === 'APPROVED') {
            $stockCheck = $quoteModel->checkStockAvailability($quoteId);
            if (!$stockCheck['can_approve']) {
                jsonResponse([
                    'error' => __('insufficient_stock'),
                    'details' => $stockCheck['insufficient_stock']
                ], 400);
            }
        }
        
        if ($quoteModel->updateQuoteStatus($quoteId, $newStatus)) {
            jsonResponse(['success' => __('quote_status_updated_successfully')]);
        } else {
            jsonResponse(['error' => __('error_updating_quote_status')], 500);
        }
    }
    
    exit;
}

// Calculate totals
$subtotal = 0;
$totalDiscount = 0;
$totalTax = 0;

foreach ($quoteItems as $item) {
    $itemSubtotal = $item['quantity'] * $item['unit_price'];
    $itemDiscount = ($itemSubtotal * $item['discount']) / 100;
    $itemAfterDiscount = $itemSubtotal - $itemDiscount;
    
    $subtotal += $itemSubtotal;
    $totalDiscount += $itemDiscount;
    $totalTax += $item['tax_amount'];
}

$calculatedTotal = $subtotal - $totalDiscount + $totalTax;

// Check for success message
$successMessage = $_SESSION['success_message'] ?? '';
if ($successMessage) {
    unset($_SESSION['success_message']);
}

// Check for error message
$errorMessage = $_SESSION['error_message'] ?? '';
if ($errorMessage) {
    unset($_SESSION['error_message']);
}

// CSRF Token for AJAX requests
$csrfToken = generateCSRFToken();

// Include view
require_once __DIR__ . '/../views/view.php';