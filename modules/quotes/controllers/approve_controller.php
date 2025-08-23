<?php
/**
 * Quote Approve Controller
 * Handles quote approval with stock validation
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

// Get quote ID
$quoteId = (int)($_GET['id'] ?? 0);

if (empty($quoteId)) {
    redirect('/?module=quotes&action=list');
}

// Get quote details
$quote = $quoteModel->getQuoteById($quoteId);

if (!$quote) {
    $_SESSION['quote_error'] = 'Quote not found';
    redirect('/?module=quotes&action=list');
}

// Check if user can approve this quote
if (!$user['is_admin'] && $quote['username'] !== $user['username']) {
    $_SESSION['quote_error'] = 'Access denied';
    redirect('/?module=quotes&action=list');
}

// Check if quote can be approved
if ($quote['status'] !== 'SENT') {
    $_SESSION['quote_error'] = 'Only SENT quotes can be approved';
    redirect('/?module=quotes&action=list');
}

// Check stock availability
$stockCheck = $quoteModel->checkStockAvailability($quoteId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $action = sanitizeInput($_POST['action'] ?? '');
        
        if ($action === 'approve') {
            // Final stock check before approval
            $finalStockCheck = $quoteModel->checkStockAvailability($quoteId);
            
            if (!$finalStockCheck['available']) {
                $error = 'Cannot approve quote due to insufficient stock';
            } else {
                if ($quoteModel->approveQuote($quoteId)) {
                    $_SESSION['quote_success'] = 'Quote approved successfully. Stock has been updated.';
                    redirect('/?module=quotes&action=list');
                } else {
                    $error = 'Failed to approve quote. Please try again.';
                }
            }
        } elseif ($action === 'reject') {
            // Update quote status to REJECTED
            $quoteData = [
                'client_id' => $quote['client_id'],
                'status' => 'REJECTED',
                'total_amount' => $quote['total_amount'],
                'issue_date' => $quote['issue_date'],
                'expiry_date' => $quote['expiry_date']
            ];
            
            // Get items for update
            $quoteDetails = $quoteModel->getQuoteForEdit($quoteId);
            
            if ($quoteModel->updateQuote($quoteId, $quoteData, $quoteDetails['items'])) {
                $_SESSION['quote_success'] = 'Quote rejected successfully';
                redirect('/?module=quotes&action=list');
            } else {
                $error = 'Failed to reject quote. Please try again.';
            }
        }
    }
}

// Handle AJAX stock check
if (isAjaxRequest() && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = sanitizeInput($_GET['ajax_action'] ?? '');
    
    if ($action === 'check_stock') {
        $stockCheck = $quoteModel->checkStockAvailability($quoteId);
        jsonResponse([
            'available' => $stockCheck['available'],
            'issues' => $stockCheck['issues']
        ]);
    }
}

// Include approve view
include __DIR__ . '/../views/approve.php';