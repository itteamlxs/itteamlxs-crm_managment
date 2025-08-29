<?php
/**
 * Quote Duplicate Controller
 * Handle quote duplication process
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

// Handle AJAX duplicate request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        jsonResponse(['success' => false, 'error' => __('invalid_security_token')], 403);
    }
    
    $originalQuoteId = (int)($_POST['quote_id'] ?? 0);
    
    if ($originalQuoteId <= 0) {
        jsonResponse(['success' => false, 'error' => __('invalid_quote')], 400);
    }
    
    try {
        // Get original quote
        $originalQuote = $quoteModel->getQuoteById($originalQuoteId);
        
        if (!$originalQuote) {
            jsonResponse(['success' => false, 'error' => __('quote_not_found')], 404);
        }
        
        // Duplicate quote
        $newQuoteId = $quoteModel->duplicateQuote($originalQuoteId);
        $newQuote = $quoteModel->getQuoteById($newQuoteId);
        
        jsonResponse([
            'success' => true,
            'message' => __('quote_duplicated_successfully'),
            'new_quote_id' => $newQuoteId,
            'new_quote_number' => $newQuote['quote_number'],
            'redirect_url' => url('quotes', 'edit', ['id' => $newQuoteId])
        ]);
        
    } catch (Exception $e) {
        logError("Quote duplication error: " . $e->getMessage());
        jsonResponse(['success' => false, 'error' => __('error_duplicating_quote')], 500);
    }
}

// Handle non-AJAX requests - show confirmation form
$quoteId = (int)($_GET['id'] ?? 0);

if ($quoteId <= 0) {
    $_SESSION['error'] = __('invalid_quote');
    redirect(url('quotes', 'list'));
}

try {
    // Get quote details
    $quote = $quoteModel->getQuoteById($quoteId);
    
    if (!$quote) {
        $_SESSION['error'] = __('quote_not_found');
        redirect(url('quotes', 'list'));
    }
    
    // Get client details
    $client = $quoteModel->getClientById($quote['client_id']);
    
} catch (Exception $e) {
    logError("Quote duplicate load error: " . $e->getMessage());
    $_SESSION['error'] = __('error_loading_quote');
    redirect(url('quotes', 'list'));
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Include the view
require_once __DIR__ . '/../views/duplicate.php';