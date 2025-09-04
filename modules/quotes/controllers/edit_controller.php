<?php
/**
 * Edit Quote Controller
 * Handles quote editing with items modification
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/QuoteModel.php';

// Check permissions
requireLogin();
requirePermission('create_quotes');

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

// Check if user can edit this quote
if (!$user['is_admin'] && getUserRole() === 'Seller' && $quote['user_id'] != $user['user_id']) {
    $_SESSION['error_message'] = __('access_denied');
    redirect(url('quotes', 'list'));
}

// Check if quote can be edited (only DRAFT and SENT quotes)
if (!in_array($quote['status'], ['DRAFT', 'SENT'])) {
    $_SESSION['error_message'] = __('only_draft_sent_quotes_can_be_edited');
    redirect(url('quotes', 'view', ['id' => $quoteId]));
}

// Get existing quote items
$existingItems = $quoteModel->getQuoteItems($quoteId);

// Initialize variables
$errors = [];
$success = false;
$quoteData = [];
$items = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        // Sanitize and validate quote data
        $quoteData = [
            'client_id' => (int)($_POST['client_id'] ?? 0),
            'status' => sanitizeInput($_POST['status'] ?? 'DRAFT'),
            'issue_date' => sanitizeInput($_POST['issue_date'] ?? ''),
            'expiry_date' => sanitizeInput($_POST['expiry_date'] ?? ''),
            'total_amount' => 0
        ];
        
        // Validate required fields
        if (empty($quoteData['client_id'])) {
            $errors[] = __('client_required');
        }
        
        if (empty($quoteData['issue_date'])) {
            $errors[] = __('issue_date_required');
        }
        
        if (empty($quoteData['expiry_date'])) {
            $errors[] = __('expiry_date_required');
        }
        
        if (!in_array($quoteData['status'], ['DRAFT', 'SENT'])) {
            $errors[] = __('invalid_status');
        }
        
        // Validate dates
        if (!empty($quoteData['issue_date']) && !strtotime($quoteData['issue_date'])) {
            $errors[] = __('invalid_issue_date');
        }
        
        if (!empty($quoteData['expiry_date']) && !strtotime($quoteData['expiry_date'])) {
            $errors[] = __('invalid_expiry_date');
        }
        
        if (!empty($quoteData['issue_date']) && !empty($quoteData['expiry_date']) && 
            strtotime($quoteData['expiry_date']) <= strtotime($quoteData['issue_date'])) {
            $errors[] = __('expiry_date_must_be_after_issue_date');
        }
        
        // Process quote items
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            foreach ($_POST['items'] as $index => $item) {
                $product_id = (int)($item['product_id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 0);
                $unit_price = (float)($item['unit_price'] ?? 0);
                $discount = (float)($item['discount'] ?? 0);
                $tax_rate = (float)($item['tax_rate'] ?? 0);
                
                if ($product_id > 0 && $quantity > 0 && $unit_price > 0) {
                    $subtotal_before_discount = $quantity * $unit_price;
                    $discount_amount = ($subtotal_before_discount * $discount) / 100;
                    $subtotal_after_discount = $subtotal_before_discount - $discount_amount;
                    $tax_amount = ($subtotal_after_discount * $tax_rate) / 100;
                    $subtotal = $subtotal_after_discount + $tax_amount;
                    
                    $items[] = [
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                        'unit_price' => $unit_price,
                        'discount' => $discount,
                        'tax_amount' => $tax_amount,
                        'subtotal' => $subtotal
                    ];
                    
                    $quoteData['total_amount'] += $subtotal;
                }
            }
        }
        
        if (empty($items)) {
            $errors[] = __('at_least_one_item_required');
        }
        
        // Update quote if no errors
        if (empty($errors)) {
            $updated = $quoteModel->updateQuote($quoteId, $quoteData, $items);
            
            if ($updated) {
                $success = true;
                $_SESSION['success_message'] = __('quote_updated_successfully');
                redirect(url('quotes', 'view', ['id' => $quoteId]));
            } else {
                $errors[] = __('error_updating_quote');
            }
        }
    }
}

// Get data for form
$clients = $quoteModel->getActiveClients();
$products = $quoteModel->getAvailableProducts();

// Set default values from existing quote
if (empty($quoteData)) {
    $quoteData = [
        'client_id' => $quote['client_id'],
        'status' => $quote['status'],
        'issue_date' => $quote['issue_date'],
        'expiry_date' => $quote['expiry_date']
    ];
}

// Prepare items data from existing quote items
if (empty($items)) {
    $items = [];
    foreach ($existingItems as $existingItem) {
        // Get current product tax rate
        $currentTaxRate = 0;
        foreach ($products as $product) {
            if ($product['product_id'] == $existingItem['product_id']) {
                $currentTaxRate = $product['tax_rate'];
                break;
            }
        }
        
        $items[] = [
            'product_id' => $existingItem['product_id'] ?? 0,
            'quantity' => $existingItem['quantity'] ?? 0,
            'unit_price' => $existingItem['unit_price'] ?? 0,
            'discount' => $existingItem['discount'] ?? 0,
            'tax_amount' => $existingItem['tax_amount'] ?? 0,
            'subtotal' => $existingItem['subtotal'] ?? 0,
            'product_name' => $existingItem['product_name'] ?? '',
            'sku' => $existingItem['sku'] ?? '',
            'tax_rate' => $currentTaxRate
        ];
    }
}

// CSRF Token
$csrfToken = generateCSRFToken();

// Include view
require_once __DIR__ . '/../views/edit.php';