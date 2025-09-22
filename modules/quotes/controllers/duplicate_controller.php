<?php
/**
 * Quote Duplicate Controller
 * Handles quote duplication with form display and processing
 */

require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/QuoteModel.php';

// Check authentication and permissions
requireLogin();
requirePermission('create_quotes');

$model = new QuoteModel();
$user = getCurrentUser();
$errors = [];

// Get original quote ID
$originalQuoteId = (int)($_GET['id'] ?? 0);
if (!$originalQuoteId) {
    redirect(url('quotes', 'list'));
}

// Get original quote data
$originalQuote = $model->getQuoteById($originalQuoteId);
if (!$originalQuote) {
    redirect(url('quotes', 'list'));
}

// Get original quote items
$originalItems = $model->getQuoteItems($originalQuoteId);

// Get available data for dropdowns
$clients = $model->getActiveClients();
$products = $model->getAvailableProducts();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        // Validate form data
        $clientId = (int)($_POST['client_id'] ?? 0);
        $status = sanitizeInput($_POST['status'] ?? 'DRAFT');
        $issueDate = sanitizeInput($_POST['issue_date'] ?? '');
        $expiryDate = sanitizeInput($_POST['expiry_date'] ?? '');
        $items = $_POST['items'] ?? [];

        // Basic validations
        if (!$clientId) {
            $errors[] = __('client_required');
        }

        if (!in_array($status, ['DRAFT', 'SENT'])) {
            $errors[] = __('invalid_status');
        }

        if (empty($issueDate)) {
            $errors[] = __('issue_date_required');
        }

        if (empty($expiryDate)) {
            $errors[] = __('expiry_date_required');
        }

        if (!empty($issueDate) && !empty($expiryDate) && strtotime($expiryDate) <= strtotime($issueDate)) {
            $errors[] = __('expiry_date_must_be_after_issue');
        }

        // Validate items
        $validItems = [];
        $totalAmount = 0;

        if (empty($items)) {
            $errors[] = __('at_least_one_item_required');
        } else {
            foreach ($items as $index => $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 0);
                $unitPrice = (float)($item['unit_price'] ?? 0);
                $discount = (float)($item['discount'] ?? 0);
                $taxRate = (float)($item['tax_rate'] ?? 0);

                if ($productId && $quantity > 0 && $unitPrice > 0) {
                    // Validate stock availability
                    foreach ($products as $product) {
                        if ($product['product_id'] == $productId) {
                            if ($quantity > $product['stock_quantity']) {
                                $errors[] = __('insufficient_stock_for_item', ['item' => $index + 1, 'product' => $product['product_name']]);
                            }
                            break;
                        }
                    }

                    // Calculate amounts
                    $subtotalBeforeDiscount = $quantity * $unitPrice;
                    $discountAmount = ($subtotalBeforeDiscount * $discount) / 100;
                    $subtotalAfterDiscount = $subtotalBeforeDiscount - $discountAmount;
                    $taxAmount = ($subtotalAfterDiscount * $taxRate) / 100;
                    $itemSubtotal = $subtotalAfterDiscount + $taxAmount;

                    $validItems[] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount' => $discount,
                        'tax_amount' => $taxAmount,
                        'subtotal' => $itemSubtotal
                    ];

                    $totalAmount += $itemSubtotal;
                }
            }
        }

        // Create duplicate quote if no errors
        if (empty($errors)) {
            $quoteNumber = $model->generateQuoteNumber();
            
            $quoteData = [
                'client_id' => $clientId,
                'user_id' => $user['user_id'],
                'quote_number' => $quoteNumber,
                'status' => $status,
                'total_amount' => $totalAmount,
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate
            ];

            $newQuoteId = $model->createQuote($quoteData, $validItems);

            if ($newQuoteId) {
                $_SESSION['success_message'] = __('duplicate_quote_created_successfully', ['number' => $quoteNumber]);
                redirect(url('quotes', 'view', ['id' => $newQuoteId]));
            } else {
                $errors[] = __('error_creating_duplicate_quote');
            }
        }
    }
}

// Prepare form data with defaults
$quoteData = [
    'client_id' => $originalQuote['client_id'],
    'status' => 'DRAFT',
    'issue_date' => date('Y-m-d'),
    'expiry_date' => date('Y-m-d', strtotime('+7 days'))
];

// Prepare items with current data
$items = [];
foreach ($originalItems as $originalItem) {
    $item = [
        'product_id' => $originalItem['product_id'],
        'quantity' => $originalItem['quantity'],
        'unit_price' => $originalItem['unit_price'],
        'discount' => $originalItem['discount'],
        'tax_rate' => 0
    ];

    // Update with current product data if available
    foreach ($products as $product) {
        if ($product['product_id'] == $originalItem['product_id']) {
            $item['tax_rate'] = $product['tax_rate'];
            break;
        }
    }

    $items[] = $item;
}

// Generate CSRF token
$csrfToken = generateCSRFToken();

// Load view
require_once __DIR__ . '/../views/duplicate.php';