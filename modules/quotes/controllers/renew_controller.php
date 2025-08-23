<?php
/**
 * Quote Renew Controller
 * Handles quote renewal with updated prices and quantities
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../models/QuoteModel.php';

// Require login and permission
requireLogin();
requirePermission('renew_quotes');

$quoteModel = new QuoteModel();
$user = getCurrentUser();
$error = '';
$success = '';

// Get parent quote ID
$parentQuoteId = (int)($_GET['id'] ?? 0);

if (empty($parentQuoteId)) {
    redirect('/?module=quotes&action=list');
}

// Get parent quote details
$parentQuote = $quoteModel->getQuoteForEdit($parentQuoteId);

if (!$parentQuote) {
    $_SESSION['quote_error'] = 'Quote not found';
    redirect('/?module=quotes&action=list');
}

// Check if user can renew this quote
if (!$user['is_admin'] && $parentQuote['user_id'] != $user['user_id']) {
    $_SESSION['quote_error'] = 'Access denied';
    redirect('/?module=quotes&action=list');
}

// Check if quote can be renewed
$allowedStatuses = ['APPROVED', 'REJECTED', 'SENT'];
if (!in_array($parentQuote['status'], $allowedStatuses)) {
    $_SESSION['quote_error'] = 'Quote cannot be renewed';
    redirect('/?module=quotes&action=list');
}

// Initialize renewal form data
$formData = [
    'issue_date' => date('Y-m-d'),
    'expiry_date' => date('Y-m-d', strtotime('+7 days')),
    'update_prices' => true,
    'update_quantities' => false,
    'items' => []
];

// Prepare items with current prices
foreach ($parentQuote['items'] as $item) {
    $formData['items'][$item['product_id']] = [
        'product_id' => $item['product_id'],
        'product_name' => $item['product_name'],
        'sku' => $item['sku'],
        'original_quantity' => $item['quantity'],
        'new_quantity' => $item['quantity'],
        'original_price' => $item['unit_price'],
        'current_price' => $item['current_price'],
        'discount' => $item['discount'],
        'tax_rate' => $item['tax_rate']
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        // Sanitize and validate input
        $issueDate = sanitizeInput($_POST['issue_date'] ?? '');
        $expiryDate = sanitizeInput($_POST['expiry_date'] ?? '');
        $updatePrices = isset($_POST['update_prices']);
        $updateQuantities = isset($_POST['update_quantities']);
        $itemQuantities = $_POST['quantities'] ?? [];
        
        // Validation
        $validationErrors = [];
        
        if (empty($issueDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $issueDate)) {
            $validationErrors[] = 'Valid issue date is required';
        }
        
        if (empty($expiryDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiryDate)) {
            $validationErrors[] = 'Valid expiry date is required';
        }
        
        if (strtotime($expiryDate) <= strtotime($issueDate)) {
            $validationErrors[] = 'Expiry date must be after issue date';
        }
        
        // Validate quantities if updating
        if ($updateQuantities) {
            foreach ($formData['items'] as $productId => $item) {
                $newQuantity = (int)($itemQuantities[$productId] ?? 0);
                if ($newQuantity <= 0) {
                    $validationErrors[] = "Valid quantity is required for " . $item['product_name'];
                }
                $formData['items'][$productId]['new_quantity'] = $newQuantity;
            }
        }
        
        // Calculate new total amount
        $totalAmount = 0;
        $renewalItems = [];
        
        if (empty($validationErrors)) {
            foreach ($formData['items'] as $productId => $item) {
                $quantity = $updateQuantities ? $item['new_quantity'] : $item['original_quantity'];
                $unitPrice = $updatePrices ? $item['current_price'] : $item['original_price'];
                $discount = $item['discount'];
                $taxRate = $item['tax_rate'];
                
                // Calculate amounts
                $subtotalBeforeDiscount = $unitPrice * $quantity;
                $discountAmount = $subtotalBeforeDiscount * ($discount / 100);
                $subtotal = $subtotalBeforeDiscount - $discountAmount;
                $taxAmount = $subtotal * ($taxRate / 100);
                $itemTotal = $subtotal + $taxAmount;
                
                $renewalItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal
                ];
                
                $totalAmount += $itemTotal;
            }
            
            // Create renewal data
            $renewalData = [
                'user_id' => $user['user_id'],
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate,
                'total_amount' => $totalAmount,
                'update_prices' => $updatePrices,
                'update_quantities' => $updateQuantities,
                'items' => $updateQuantities ? $itemQuantities : []
            ];
            
            // Create renewed quote
            $newQuoteId = $quoteModel->renewQuote($parentQuoteId, $renewalData);
            
            if ($newQuoteId) {
                $_SESSION['quote_success'] = 'Quote renewed successfully';
                redirect('/?module=quotes&action=list');
            } else {
                $error = 'Failed to renew quote. Please try again.';
            }
        } else {
            $error = implode('<br>', $validationErrors);
        }
        
        // Store form data for redisplay
        $formData['issue_date'] = $issueDate;
        $formData['expiry_date'] = $expiryDate;
        $formData['update_prices'] = $updatePrices;
        $formData['update_quantities'] = $updateQuantities;
    }
}

// Handle AJAX calculations
if (isAjaxRequest() && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = sanitizeInput($_POST['ajax_action'] ?? '');
    
    if ($action === 'calculate_renewal') {
        $updatePrices = isset($_POST['update_prices']);
        $updateQuantities = isset($_POST['update_quantities']);
        $quantities = $_POST['quantities'] ?? [];
        
        $calculations = [];
        $grandTotal = 0;
        
        foreach ($formData['items'] as $productId => $item) {
            $quantity = $updateQuantities ? (int)($quantities[$productId] ?? $item['original_quantity']) : $item['original_quantity'];
            $unitPrice = $updatePrices ? $item['current_price'] : $item['original_price'];
            $discount = $item['discount'];
            $taxRate = $item['tax_rate'];
            
            $subtotalBeforeDiscount = $unitPrice * $quantity;
            $discountAmount = $subtotalBeforeDiscount * ($discount / 100);
            $subtotal = $subtotalBeforeDiscount - $discountAmount;
            $taxAmount = $subtotal * ($taxRate / 100);
            $itemTotal = $subtotal + $taxAmount;
            
            $calculations[$productId] = [
                'quantity' => $quantity,
                'unit_price' => number_format($unitPrice, 2),
                'subtotal' => number_format($subtotal, 2),
                'tax_amount' => number_format($taxAmount, 2),
                'item_total' => number_format($itemTotal, 2),
                'price_change' => $updatePrices ? ($item['current_price'] - $item['original_price']) : 0
            ];
            
            $grandTotal += $itemTotal;
        }
        
        jsonResponse([
            'success' => true,
            'items' => $calculations,
            'grand_total' => number_format($grandTotal, 2)
        ]);
    }
}

// Include renew view
include __DIR__ . '/../views/renew.php';