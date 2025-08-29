<?php
/**
 * Quote Edit Controller
 * Handle quote editing form and processing
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

// Get quote ID from URL
$quoteId = (int)($_GET['id'] ?? 0);

if ($quoteId <= 0) {
    $_SESSION['error'] = __('invalid_quote');
    redirect(url('quotes', 'list'));
}

// Initialize variables
$clients = $quoteModel->getClients();
$products = $quoteModel->getProducts();
$errors = [];
$success = false;
$quote = null;

try {
    // Get quote details
    $quote = $quoteModel->getQuoteById($quoteId);
    
    if (!$quote) {
        $_SESSION['error'] = __('quote_not_found');
        redirect(url('quotes', 'list'));
    }
    
    // Check if quote can be edited
    if (!in_array($quote['status'], ['DRAFT'])) {
        $_SESSION['error'] = __('quote_cannot_be_edited');
        redirect(url('quotes', 'view', ['id' => $quoteId]));
    }
    
} catch (Exception $e) {
    logError("Quote edit load error: " . $e->getMessage());
    $_SESSION['error'] = __('error_loading_quote');
    redirect(url('quotes', 'list'));
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        try {
            // Sanitize input
            $clientId = (int)($_POST['client_id'] ?? 0);
            $expiryDays = (int)($_POST['expiry_days'] ?? 7);
            $items = $_POST['items'] ?? [];
            
            // Validate required fields
            if (empty($clientId)) {
                $errors[] = __('client_required');
            }
            
            if (empty($items) || !is_array($items)) {
                $errors[] = __('quote_items_required');
            }
            
            // Validate and process items
            $validItems = [];
            $totalAmount = 0;
            
            foreach ($items as $index => $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 0);
                $discount = min(100, max(0, (float)($item['discount'] ?? 0)));
                
                if ($productId <= 0 || $quantity <= 0) {
                    continue;
                }
                
                // Get product details
                $product = $quoteModel->getProductById($productId);
                if (!$product) {
                    $errors[] = __('invalid_product', ['index' => $index + 1]);
                    continue;
                }
                
                // Check stock availability
                if ($product['stock_quantity'] < $quantity) {
                    $errors[] = __('insufficient_stock', [
                        'product' => $product['product_name'],
                        'available' => $product['stock_quantity'],
                        'required' => $quantity
                    ]);
                    continue;
                }
                
                // Calculate amounts
                $unitPrice = $product['price'];
                $subtotalBeforeDiscount = $unitPrice * $quantity;
                $discountAmount = $subtotalBeforeDiscount * ($discount / 100);
                $subtotalAfterDiscount = $subtotalBeforeDiscount - $discountAmount;
                $taxAmount = $subtotalAfterDiscount * ($product['tax_rate'] / 100);
                $subtotal = $subtotalAfterDiscount + $taxAmount;
                
                $validItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal
                ];
                
                $totalAmount += $subtotal;
            }
            
            if (empty($validItems)) {
                $errors[] = __('no_valid_items');
            }
            
            // If no errors, update quote
            if (empty($errors)) {
                $quoteData = [
                    'client_id' => $clientId,
                    'total_amount' => $totalAmount,
                    'expiry_date' => date('Y-m-d', strtotime("+{$expiryDays} days"))
                ];
                
                $quoteModel->updateQuote($quoteId, $quoteData, $validItems);
                
                if (isAjaxRequest()) {
                    jsonResponse([
                        'success' => true,
                        'message' => __('quote_updated_successfully'),
                        'quote_id' => $quoteId,
                        'quote_number' => $quote['quote_number']
                    ]);
                } else {
                    $_SESSION['success'] = __('quote_updated_successfully');
                    redirect(url('quotes', 'view', ['id' => $quoteId]));
                }
            }
            
        } catch (Exception $e) {
            logError("Quote edit error: " . $e->getMessage());
            $errors[] = __('error_updating_quote');
        }
    }
    
    // Return errors for AJAX requests
    if (isAjaxRequest()) {
        jsonResponse(['success' => false, 'errors' => $errors], 400);
    }
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Include the view
require_once __DIR__ . '/../views/edit.php';