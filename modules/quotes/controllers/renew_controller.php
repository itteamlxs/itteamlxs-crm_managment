<?php
/**
 * Quote Renew Controller
 * Handle quote renewal process
 */

require_once __DIR__ . '/../models/QuoteModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Check permissions
requirePermission('renew_quotes');

$quoteModel = new QuoteModel();
$currentUser = getCurrentUser();

// Get quote ID from URL or POST
$quoteId = (int)($_GET['id'] ?? $_POST['quote_id'] ?? 0);

if ($quoteId <= 0) {
    if (isAjaxRequest()) {
        jsonResponse(['success' => false, 'error' => __('invalid_quote')], 400);
    } else {
        $_SESSION['error'] = __('invalid_quote');
        redirect(url('quotes', 'list'));
    }
}

// Get original quote
try {
    $originalQuote = $quoteModel->getQuoteById($quoteId);
    
    if (!$originalQuote) {
        if (isAjaxRequest()) {
            jsonResponse(['success' => false, 'error' => __('quote_not_found')], 404);
        } else {
            $_SESSION['error'] = __('quote_not_found');
            redirect(url('quotes', 'list'));
        }
    }
    
    // Check if quote can be renewed
    if (!in_array($originalQuote['status'], ['APPROVED', 'REJECTED', 'SENT'])) {
        if (isAjaxRequest()) {
            jsonResponse(['success' => false, 'error' => __('quote_cannot_be_renewed')], 400);
        } else {
            $_SESSION['error'] = __('quote_cannot_be_renewed');
            redirect(url('quotes', 'list'));
        }
    }
    
} catch (Exception $e) {
    logError("Quote renewal error: " . $e->getMessage());
    
    if (isAjaxRequest()) {
        jsonResponse(['success' => false, 'error' => __('error_loading_quote')], 500);
    } else {
        $_SESSION['error'] = __('error_loading_quote');
        redirect(url('quotes', 'list'));
    }
}

// Initialize variables
$errors = [];
$success = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        try {
            // Sanitize input
            $expiryDays = (int)($_POST['expiry_days'] ?? 7);
            
            // Validate expiry days
            if ($expiryDays < 1 || $expiryDays > 365) {
                $errors[] = __('invalid_expiry_days');
            }
            
            // If no errors, renew quote
            if (empty($errors)) {
                $newExpiryDate = date('Y-m-d', strtotime("+{$expiryDays} days"));
                
                $newQuoteId = $quoteModel->renewQuote($quoteId, $newExpiryDate);
                
                if (isAjaxRequest()) {
                    jsonResponse([
                        'success' => true,
                        'message' => __('quote_renewed_successfully'),
                        'new_quote_id' => $newQuoteId,
                        'redirect_url' => url('quotes', 'list')
                    ]);
                } else {
                    $_SESSION['success'] = __('quote_renewed_successfully');
                    redirect(url('quotes', 'list'));
                }
            }
            
        } catch (Exception $e) {
            logError("Quote renewal processing error: " . $e->getMessage());
            $errors[] = __('error_renewing_quote');
        }
    }
    
    // Return errors for AJAX requests
    if (isAjaxRequest()) {
        jsonResponse(['success' => false, 'errors' => $errors], 400);
    }
}

// Get client details
try {
    $clients = $quoteModel->getClients();
    $clientName = '';
    
    foreach ($clients as $client) {
        if ($client['client_id'] == $originalQuote['client_id']) {
            $clientName = $client['company_name'];
            break;
        }
    }
    
} catch (Exception $e) {
    logError("Error loading client details: " . $e->getMessage());
    $clientName = __('unknown_client');
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Include the view
require_once __DIR__ . '/../views/renew.php';