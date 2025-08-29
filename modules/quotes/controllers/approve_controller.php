<?php
/**
 * Quote Approve Controller
 * Handle quote approval and stock updates
 */

require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Check permissions
requirePermission('create_quotes');

$quoteModel = new QuoteModel();
$currentUser = getCurrentUser();

// Handle AJAX approve request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'error' => __('invalid_security_token')], 403);
    }
    
    $quoteId = (int)($_POST['quote_id'] ?? 0);
    
    if ($quoteId <= 0) {
        jsonResponse(['success' => false, 'error' => __('invalid_quote')], 400);
    }
    
    try {
        // Get quote details
        $quote = $quoteModel->getQuoteById($quoteId);
        
        if (!$quote) {
            jsonResponse(['success' => false, 'error' => __('quote_not_found')], 404);
        }
        
        // Check if quote can be approved
        if ($quote['status'] !== 'SENT') {
            jsonResponse(['success' => false, 'error' => __('quote_cannot_be_approved')], 400);
        }
        
        // Check stock availability
        $stockCheck = $quoteModel->canApproveQuote($quoteId);
        
        if (!$stockCheck['can_approve']) {
            $stockErrors = [];
            foreach ($stockCheck['insufficient_stock'] as $item) {
                $stockErrors[] = __('insufficient_stock_item', [
                    'product' => $item['product_name'],
                    'required' => $item['required'],
                    'available' => $item['available']
                ]);
            }
            
            jsonResponse([
                'success' => false,
                'error' => __('insufficient_stock_for_approval'),
                'stock_errors' => $stockErrors
            ], 400);
        }
        
        // Approve quote (triggers will handle stock updates)
        $quoteModel->updateQuoteStatus($quoteId, 'APPROVED');
        
        jsonResponse([
            'success' => true,
            'message' => __('quote_approved_successfully'),
            'quote_number' => $quote['quote_number']
        ]);
        
    } catch (Exception $e) {
        logError("Quote approval error: " . $e->getMessage());
        jsonResponse(['success' => false, 'error' => __('error_approving_quote')], 500);
    }
}

// Handle reject request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reject') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        if (isAjaxRequest()) {
            jsonResponse(['success' => false, 'error' => __('invalid_security_token')], 403);
        } else {
            $_SESSION['error'] = __('invalid_security_token');
            redirect(url('quotes', 'list'));
        }
    }
    
    $quoteId = (int)($_POST['quote_id'] ?? 0);
    
    if ($quoteId <= 0) {
        if (isAjaxRequest()) {
            jsonResponse(['success' => false, 'error' => __('invalid_quote')], 400);
        } else {
            $_SESSION['error'] = __('invalid_quote');
            redirect(url('quotes', 'list'));
        }
    }
    
    try {
        $quote = $quoteModel->getQuoteById($quoteId);
        
        if (!$quote) {
            if (isAjaxRequest()) {
                jsonResponse(['success' => false, 'error' => __('quote_not_found')], 404);
            } else {
                $_SESSION['error'] = __('quote_not_found');
                redirect(url('quotes', 'list'));
            }
        }
        
        if (!in_array($quote['status'], ['SENT', 'DRAFT'])) {
            if (isAjaxRequest()) {
                jsonResponse(['success' => false, 'error' => __('quote_cannot_be_rejected')], 400);
            } else {
                $_SESSION['error'] = __('quote_cannot_be_rejected');
                redirect(url('quotes', 'list'));
            }
        }
        
        // Reject quote
        $quoteModel->updateQuoteStatus($quoteId, 'REJECTED');
        
        if (isAjaxRequest()) {
            jsonResponse([
                'success' => true,
                'message' => __('quote_rejected_successfully'),
                'quote_number' => $quote['quote_number']
            ]);
        } else {
            $_SESSION['success'] = __('quote_rejected_successfully');
            redirect(url('quotes', 'list'));
        }
        
    } catch (Exception $e) {
        logError("Quote rejection error: " . $e->getMessage());
        
        if (isAjaxRequest()) {
            jsonResponse(['success' => false, 'error' => __('error_rejecting_quote')], 500);
        } else {
            $_SESSION['error'] = __('error_rejecting_quote');
            redirect(url('quotes', 'list'));
        }
    }
}

// If not POST request, redirect to list
redirect(url('quotes', 'list'));