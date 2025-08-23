<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name')); ?> - Create Quote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2><i class="bi bi-file-plus"></i> Create Quote</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo url('quotes', 'list'); ?>">Quotes</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" id="quoteForm">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="row">
                <!-- Quote Details -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Quote Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                        <select name="client_id" id="client_id" class="form-select" required>
                                            <option value="">Select Client</option>
                                            <?php foreach ($clients as $client): ?>
                                            <option value="<?php echo $client['client_id']; ?>" 
                                                    <?php echo $formData['client_id'] == $client['client_id'] ? 'selected' : ''; ?>>
                                                <?php echo sanitizeOutput($client['company_name']); ?> - <?php echo sanitizeOutput($client['contact_name']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                                        <input type="date" name="issue_date" id="issue_date" class="form-control" 
                                               value="<?php echo sanitizeOutput($formData['issue_date']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control" 
                                               value="<?php echo sanitizeOutput($formData['expiry_date']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="DRAFT" <?php echo $formData['status'] === 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                                            <option value="SENT" <?php echo $formData['status'] === 'SENT' ? 'selected' : ''; ?>>Send to Client</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Items -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Quote Items</h5>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addQuoteItem()">
                                <i class="bi bi-plus-circle"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="quote-items">
                                <?php if (!empty($formData['items'])): ?>
                                    <?php foreach ($formData['items'] as $index => $item): ?>
                                    <div class="quote-item mb-3 p-3 border rounded" data-index="<?php echo $index; ?>">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Product <span class="text-danger">*</span></label>
                                                <select name="items[<?php echo $index; ?>][product_id]" class="form-select product-select" required>
                                                    <option value="">Select Product</option>
                                                    <?php foreach ($products as $product): ?>
                                                    <option value="<?php echo $product['product_id']; ?>" 
                                                            data-price="<?php echo $product['price']; ?>"
                                                            data-tax-rate="<?php echo $product['tax_rate']; ?>"
                                                            data-stock="<?php echo $product['stock_quantity']; ?>"
                                                            <?php echo isset($item['product_id']) && $item['product_id'] == $product['product_id'] ? 'selected' : ''; ?>>
                                                        <?php echo sanitizeOutput($product['product_name']); ?> (<?php echo sanitizeOutput($product['sku']); ?>)
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                <input type="number" name="items[<?php echo $index; ?>][quantity]" 
                                                       class="form-control quantity-input" min="1" 
                                                       value="<?php echo sanitizeOutput($item['quantity'] ?? 1); ?>" required>
                                                <small class="text-muted stock-info"></small>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                                                <input type="number" name="items[<?php echo $index; ?>][unit_price]" 
                                                       class="form-control price-input" step="0.01" min="0"
                                                       value="<?php echo sanitizeOutput($item['unit_price'] ?? ''); ?>" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Discount %</label>
                                                <input type="number" name="items[<?php echo $index; ?>][discount]" 
                                                       class="form-control discount-input" step="0.01" min="0" max="100"
                                                       value="<?php echo sanitizeOutput($item['discount'] ?? 0); ?>">
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Total</label>
                                                <div class="item-total fw-bold">$0.00</div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeQuoteItem(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="quote-item mb-3 p-3 border rounded" data-index="0">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Product <span class="text-danger">*</span></label>
                                                <select name="items[0][product_id]" class="form-select product-select" required>
                                                    <option value="">Select Product</option>
                                                    <?php foreach ($products as $product): ?>
                                                    <option value="<?php echo $product['product_id']; ?>" 
                                                            data-price="<?php echo $product['price']; ?>"
                                                            data-tax-rate="<?php echo $product['tax_rate']; ?>"
                                                            data-stock="<?php echo $product['stock_quantity']; ?>">
                                                        <?php echo sanitizeOutput($product['product_name']); ?> (<?php echo sanitizeOutput($product['sku']); ?>)
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                <input type="number" name="items[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
                                                <small class="text-muted stock-info"></small>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                                                <input type="number" name="items[0][unit_price]" class="form-control price-input" step="0.01" min="0" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Discount %</label>
                                                <input type="number" name="items[0][discount]" class="form-control discount-input" step="0.01" min="0" max="100" value="0">
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">Total</label>
                                                <div class="item-total fw-bold">$0.00</div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeQuoteItem(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="col-md-4">
                    <div class="card sticky-top" style="top: 20px;">
                        <div class="card-header">
                            <h5 class="mb-0">Quote Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">Subtotal:</div>
                                <div class="col text-end" id="subtotal">$0.00</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">Discount:</div>
                                <div class="col text-end" id="total-discount">$0.00</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">Tax:</div>
                                <div class="col text-end" id="total-tax">$0.00</div>
                            </div>
                            <hr>
                            <div class="row fs-5 fw-bold">
                                <div class="col">Total:</div>
                                <div class="col text-end" id="grand-total">$0.00</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Create Quote
                                </button>
                                <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let itemIndex = <?php echo !empty($formData['items']) ? count($formData['items']) : 1; ?>;
        
        // Add new quote item
        function addQuoteItem() {
            const template = `
                <div class="quote-item mb-3 p-3 border rounded" data-index="${itemIndex}">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="items[${itemIndex}][product_id]" class="form-select product-select" required>
                                <option value="">Select Product</option>
                                <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['product_id']; ?>" 
                                        data-price="<?php echo $product['price']; ?>"
                                        data-tax-rate="<?php echo $product['tax_rate']; ?>"
                                        data-stock="<?php echo $product['stock_quantity']; ?>">
                                    <?php echo sanitizeOutput($product['product_name']); ?> (<?php echo sanitizeOutput($product['sku']); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input" min="1" value="1" required>
                            <small class="text-muted stock-info"></small>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                            <input type="number" name="items[${itemIndex}][unit_price]" class="form-control price-input" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Discount %</label>
                            <input type="number" name="items[${itemIndex}][discount]" class="form-control discount-input" step="0.01" min="0" max="100" value="0">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Total</label>
                            <div class="item-total fw-bold">$0.00</div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeQuoteItem(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('quote-items').insertAdjacentHTML('beforeend', template);
            itemIndex++;
            attachEventListeners();
        }
        
        // Remove quote item
        function removeQuoteItem(button) {
            const items = document.querySelectorAll('.quote-item');
            if (items.length > 1) {
                button.closest('.quote-item').remove();
                calculateTotals();
            } else {
                alert('At least one item is required');
            }
        }
        
        // Attach event listeners to form elements
        function attachEventListeners() {
            // Product selection changes
            document.querySelectorAll('.product-select').forEach(select => {
                select.addEventListener('change', function() {
                    const option = this.selectedOptions[0];
                    const item = this.closest('.quote-item');
                    
                    if (option.value) {
                        const price = option.dataset.price;
                        const stock = option.dataset.stock;
                        
                        item.querySelector('.price-input').value = price;
                        item.querySelector('.stock-info').textContent = `Stock: ${stock}`;
                        
                        // Validate quantity against stock
                        const quantityInput = item.querySelector('.quantity-input');
                        if (parseInt(quantityInput.value) > parseInt(stock)) {
                            quantityInput.classList.add('is-invalid');
                            item.querySelector('.stock-info').classList.add('text-danger');
                        } else {
                            quantityInput.classList.remove('is-invalid');
                            item.querySelector('.stock-info').classList.remove('text-danger');
                        }
                    } else {
                        item.querySelector('.price-input').value = '';
                        item.querySelector('.stock-info').textContent = '';
                    }
                    
                    calculateTotals();
                });
            });
            
            // Quantity, price, discount changes
            document.querySelectorAll('.quantity-input, .price-input, .discount-input').forEach(input => {
                input.addEventListener('input', function() {
                    // Validate quantity against stock
                    if (this.classList.contains('quantity-input')) {
                        const item = this.closest('.quote-item');
                        const productSelect = item.querySelector('.product-select');
                        const selectedOption = productSelect.selectedOptions[0];
                        
                        if (selectedOption && selectedOption.dataset.stock) {
                            const stock = parseInt(selectedOption.dataset.stock);
                            const quantity = parseInt(this.value);
                            
                            if (quantity > stock) {
                                this.classList.add('is-invalid');
                                item.querySelector('.stock-info').classList.add('text-danger');
                                item.querySelector('.stock-info').textContent = `Stock: ${stock} (Insufficient!)`;
                            } else {
                                this.classList.remove('is-invalid');
                                item.querySelector('.stock-info').classList.remove('text-danger');
                                item.querySelector('.stock-info').textContent = `Stock: ${stock}`;
                            }
                        }
                    }
                    
                    calculateTotals();
                });
            });
        }
        
        // Calculate totals for all items
        function calculateTotals() {
            let subtotal = 0;
            let totalDiscount = 0;
            let totalTax = 0;
            
            document.querySelectorAll('.quote-item').forEach(item => {
                const productSelect = item.querySelector('.product-select');
                const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
                const unitPrice = parseFloat(item.querySelector('.price-input').value) || 0;
                const discount = parseFloat(item.querySelector('.discount-input').value) || 0;
                
                const selectedOption = productSelect.selectedOptions[0];
                const taxRate = selectedOption ? parseFloat(selectedOption.dataset.taxRate) || 0 : 0;
                
                const lineSubtotal = quantity * unitPrice;
                const lineDiscount = lineSubtotal * (discount / 100);
                const lineSubtotalAfterDiscount = lineSubtotal - lineDiscount;
                const lineTax = lineSubtotalAfterDiscount * (taxRate / 100);
                const lineTotal = lineSubtotalAfterDiscount + lineTax;
                
                item.querySelector('.item-total').textContent = ' + lineTotal.toFixed(2);
                
                subtotal += lineSubtotal;
                totalDiscount += lineDiscount;
                totalTax += lineTax;
            });
            
            const grandTotal = subtotal - totalDiscount + totalTax;
            
            document.getElementById('subtotal').textContent = ' + subtotal.toFixed(2);
            document.getElementById('total-discount').textContent = '- + totalDiscount.toFixed(2);
            document.getElementById('total-tax').textContent = ' + totalTax.toFixed(2);
            document.getElementById('grand-total').textContent = ' + grandTotal.toFixed(2);
        }
        
        // Form validation
        document.getElementById('quoteForm').addEventListener('submit', function(e) {
            let isValid = true;
            const errors = [];
            
            // Check if client is selected
            const clientSelect = document.getElementById('client_id');
            if (!clientSelect.value) {
                errors.push('Please select a client');
                clientSelect.classList.add('is-invalid');
                isValid = false;
            } else {
                clientSelect.classList.remove('is-invalid');
            }
            
            // Check dates
            const issueDate = document.getElementById('issue_date');
            const expiryDate = document.getElementById('expiry_date');
            
            if (!issueDate.value) {
                errors.push('Issue date is required');
                issueDate.classList.add('is-invalid');
                isValid = false;
            } else {
                issueDate.classList.remove('is-invalid');
            }
            
            if (!expiryDate.value) {
                errors.push('Expiry date is required');
                expiryDate.classList.add('is-invalid');
                isValid = false;
            } else {
                expiryDate.classList.remove('is-invalid');
            }
            
            if (issueDate.value && expiryDate.value && new Date(expiryDate.value) <= new Date(issueDate.value)) {
                errors.push('Expiry date must be after issue date');
                expiryDate.classList.add('is-invalid');
                isValid = false;
            }
            
            // Check quote items
            const quoteItems = document.querySelectorAll('.quote-item');
            let hasValidItem = false;
            
            quoteItems.forEach((item, index) => {
                const productSelect = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                const priceInput = item.querySelector('.price-input');
                
                if (productSelect.value && quantityInput.value && priceInput.value) {
                    hasValidItem = true;
                    
                    // Check stock availability
                    const selectedOption = productSelect.selectedOptions[0];
                    const stock = parseInt(selectedOption.dataset.stock);
                    const quantity = parseInt(quantityInput.value);
                    
                    if (quantity > stock) {
                        errors.push(`Item ${index + 1}: Quantity (${quantity}) exceeds available stock (${stock})`);
                        quantityInput.classList.add('is-invalid');
                        isValid = false;
                    }
                }
            });
            
            if (!hasValidItem) {
                errors.push('At least one complete quote item is required');
                isValid = false;
            }
            
            // Show errors if any
            if (!isValid) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                return false;
            }
            
            return true;
        });
        
        // Auto-calculate expiry date when issue date changes
        document.getElementById('issue_date').addEventListener('change', function() {
            const issueDate = new Date(this.value);
            const expiryDate = new Date(issueDate);
            expiryDate.setDate(issueDate.getDate() + 7); // Default 7 days
            
            document.getElementById('expiry_date').value = expiryDate.toISOString().split('T')[0];
        });
        
        // Initialize event listeners and calculations
        document.addEventListener('DOMContentLoaded', function() {
            attachEventListeners();
            calculateTotals();
            
            // Set minimum dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('issue_date').setAttribute('min', today);
            document.getElementById('expiry_date').setAttribute('min', today);
        });
    </script>
</body>
</html>