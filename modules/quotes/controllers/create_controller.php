<?php
/**
 * Quote Create Controller
 * Handles quote creation with items, validation, and calculations
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

// Get clients and products for dropdowns
$clients = $quoteModel->getClients();
$products = $quoteModel->getProducts();

// Initialize form data
$formData = [
    'client_id' => '',
    'issue_date' => date('Y-m-d'),
    'expiry_date' => date('Y-m-d', strtotime('+7 days')),
    'status' => 'DRAFT',
    'items' => []
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        // Sanitize and validate input
        $clientId = (int)($_POST['client_id'] ?? 0);
        $issueDate = sanitizeInput($_POST['issue_date'] ?? '');
        $expiryDate = sanitizeInput($_POST['expiry_date'] ?? '');
        $status = sanitizeInput($_POST['status'] ?? 'DRAFT');
        $items = $_POST['items'] ?? [];
        
        // Validation
        $validationErrors = [];
        
        if (empty($clientId)) {
            $validationErrors[] = 'Client is required';
        }
        
        if (empty($issueDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $issueDate)) {
            $validationErrors[] = 'Valid issue date is required';
        }
        
        if (empty($expiryDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiryDate)) {
            $validationErrors[] = 'Valid expiry date is required';
        }
        
        if (strtotime($expiryDate) <= strtotime($issueDate)) {
            $validationErrors[] = 'Expiry date must be after issue date';
        }
        
        if (!in_array($status, ['DRAFT', 'SENT'])) {
            $validationErrors[] = 'Invalid status';
        }
        
        if (empty($items) || !is_array($items)) {
            $validationErrors[] = 'At least one item is required';
        }
        
        // Validate items
        $processedItems = [];
        $totalAmount = 0;
        
        if (empty($validationErrors)) {
            foreach ($items as $index => $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $quantity = (int)($item['quantity'] ?? 0);
                $unitPrice = (float)($item['unit_price'] ?? 0);
                $discount = (float)($item['discount'] ?? 0);
                
                if (empty($productId)) {
                    $validationErrors[] = "Product is required for item " . ($index + 1);
                    continue;
                }
                
                if ($quantity <= 0) {
                    $validationErrors[] = "Valid quantity is required for item " . ($index + 1);
                    continue;
                }
                
                if ($unitPrice <= 0) {
                    $validationErrors[] = "Valid unit price is required for item " . ($index + 1);
                    continue;
                }
                
                if ($discount < 0 || $discount > 100) {
                    $validationErrors[] = "Discount must be between 0 and 100 for item " . ($index + 1);
                    continue;
                }
                
                // Get product details for tax calculation
                $product = null;
                foreach ($products as $p) {
                    if ($p['product_id'] == $productId) {
                        $product = $p;
                        break;
                    }
                }
                
                if (!$product) {
                    $validationErrors[] = "Invalid product selected for item " . ($index + 1);
                    continue;
                }
                
                // Calculate amounts
                $subtotalBeforeDiscount = $unitPrice * $quantity;
                $discountAmount = $subtotalBeforeDiscount * ($discount / 100);
                $subtotal = $subtotalBeforeDiscount - $discountAmount;
                $taxAmount = $subtotal * ($product['tax_rate'] / 100);
                $itemTotal = $subtotal + $taxAmount;
                
                $processedItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal
                ];
                
                $totalAmount += $itemTotal;
            }
        }
        
        // If no validation errors, create quote
        if (empty($validationErrors)) {
            $quoteData = [
                'client_id' => $clientId,
                'user_id' => $user['user_id'],
                'parent_quote_id' => null,
                'status' => $status,
                'total_amount' => $totalAmount,
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate
            ];
            
            $quoteId = $quoteModel->createQuote($quoteData, $processedItems);
            
            if ($quoteId) {
                $_SESSION['quote_success'] = 'Quote created successfully';
                redirect('/?module=quotes&action=list');
            } else {
                $error = 'Failed to create quote. Please try again.';
            }
        } else {
            $error = implode('<br>', $validationErrors);
        }
        
        // Store form data for redisplay
        $formData = [
            'client_id' => $clientId,
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'status' => $status,
            'items' => $items
        ];
    }
}

// Handle AJAX requests for dynamic data
if (isAjaxRequest()) {
    $action = sanitizeInput($_GET['ajax_action'] ?? '');
    
    switch ($action) {
        case 'get_product':
            $productId = (int)($_GET['product_id'] ?? 0);
            $product = null;
            
            foreach ($products as $p) {
                if ($p['product_id'] == $productId) {
                    $product = $p;
                    break;
                }
            }
            
            if ($product) {
                jsonResponse([
                    'success' => true,
                    'product' => $product
                ]);
            } else {
                jsonResponse(['error' => 'Product not found'], 404);
            }
            break;
            
        case 'calculate_totals':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $items = $_POST['items'] ?? [];
                $calculations = [];
                $grandTotal = 0;
                
                foreach ($items as $item) {
                    $productId = (int)($item['product_id'] ?? 0);
                    $quantity = (int)($item['quantity'] ?? 0);
                    $unitPrice = (float)($item['unit_price'] ?? 0);
                    $discount = (float)($item['discount'] ?? 0);
                    
                    // Get product for tax rate
                    $taxRate = 0;
                    foreach ($products as $p) {
                        if ($p['product_id'] == $productId) {
                            $taxRate = $p['tax_rate'];
                            break;
                        }
                    }
                    
                    $subtotalBeforeDiscount = $unitPrice * $quantity;
                    $discountAmount = $subtotalBeforeDiscount * ($discount / 100);
                    $subtotal = $subtotalBeforeDiscount - $discountAmount;
                    $taxAmount = $subtotal * ($taxRate / 100);
                    $itemTotal = $subtotal + $taxAmount;
                    
                    $calculations[] = [
                        'subtotal' => number_format($subtotal, 2),
                        'tax_amount' => number_format($taxAmount, 2),
                        'item_total' => number_format($itemTotal, 2)
                    ];
                    
                    $grandTotal += $itemTotal;
                }
                
                jsonResponse([
                    'success' => true,
                    'items' => $calculations,
                    'grand_total' => number_format($grandTotal, 2)
                ]);
            }
            break;
            
        default:
            jsonResponse(['error' => 'Invalid action'], 400);
    }
}

// Include create view
include __DIR__ . '/../views/create.php';