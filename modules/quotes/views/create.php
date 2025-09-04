<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('create_quote') ?> - <?= __('app_name') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .item-row {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        .remove-item {
            cursor: pointer;
            color: #dc3545;
        }
        .total-section {
            background-color: #e9ecef;
            border-radius: 0.375rem;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><?= __('create_quote') ?></h2>
            <a href="<?= url('quotes', 'list') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> <?= __('back_to_list') ?>
            </a>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h6><?= __('please_correct_errors') ?>:</h6>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= sanitizeOutput($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?= __('quote_created_successfully') ?>
            </div>
        <?php endif; ?>

        <!-- Quote Form -->
        <form method="POST" id="quoteForm">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            
            <div class="row">
                <!-- Quote Details -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> <?= __('quote_details') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="client_id" class="form-label"><?= __('client') ?> *</label>
                                    <select class="form-select" id="client_id" name="client_id" required>
                                        <option value=""><?= __('select_client') ?></option>
                                        <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['client_id'] ?>" 
                                                    <?= $quoteData['client_id'] == $client['client_id'] ? 'selected' : '' ?>>
                                                <?= sanitizeOutput($client['company_name'] . ' - ' . $client['contact_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="status" class="form-label"><?= __('status') ?> *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="DRAFT" <?= ($quoteData['status'] ?? '') === 'DRAFT' ? 'selected' : '' ?>>
                                            <?= __('draft') ?>
                                        </option>
                                        <option value="SENT" <?= ($quoteData['status'] ?? '') === 'SENT' ? 'selected' : '' ?>>
                                            <?= __('sent') ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="issue_date" class="form-label"><?= __('issue_date') ?> *</label>
                                    <input type="date" class="form-control" id="issue_date" name="issue_date" 
                                           value="<?= $quoteData['issue_date'] ?? '' ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="expiry_date" class="form-label"><?= __('expiry_date') ?> *</label>
                                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                                           value="<?= $quoteData['expiry_date'] ?? '' ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Items -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-list-ul"></i> <?= __('quote_items') ?></h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                <i class="bi bi-plus"></i> <?= __('add_item') ?>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="itemsContainer">
                                <!-- Items will be added here -->
                            </div>
                            
                            <div class="text-center py-3" id="noItemsMessage">
                                <p class="text-muted mb-0"><?= __('no_items_added') ?></p>
                                <small class="text-muted"><?= __('click_add_item_to_start') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="col-lg-4">
                    <div class="card sticky-top">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-calculator"></i> <?= __('quote_summary') ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="total-section">
                                <div class="d-flex justify-content-between">
                                    <span><?= __('subtotal') ?>:</span>
                                    <span id="subtotalAmount">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><?= __('discount') ?>:</span>
                                    <span id="discountAmount">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><?= __('tax') ?>:</span>
                                    <span id="taxAmount">$0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong><?= __('total') ?>:</strong>
                                    <strong id="totalAmount">$0.00</strong>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save"></i> <?= __('create_quote') ?>
                                </button>
                                <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary w-100 mt-2">
                                    <?= __('cancel') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Item Template -->
    <template id="itemTemplate">
        <div class="item-row" data-item-index="">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="mb-0"><?= __('item') ?> <span class="item-number"></span></h6>
                <i class="bi bi-trash remove-item" title="<?= __('remove_item') ?>"></i>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label"><?= __('product') ?> *</label>
                    <select class="form-select product-select" name="items[][product_id]" required>
                        <option value=""><?= __('select_product') ?></option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['product_id'] ?>" 
                                    data-price="<?= $product['price'] ?>"
                                    data-tax-rate="<?= $product['tax_rate'] ?>"
                                    data-stock="<?= $product['stock_quantity'] ?>">
                                <?= sanitizeOutput($product['product_name'] . ' - ' . $product['sku']) ?>
                                (<?= __('stock') ?>: <?= $product['stock_quantity'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label"><?= __('quantity') ?> *</label>
                    <input type="number" class="form-control quantity-input" name="items[][quantity]" 
                           min="1" step="1" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label"><?= __('unit_price') ?> *</label>
                    <input type="number" class="form-control price-input" name="items[][unit_price]" 
                           min="0" step="0.01" required>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-3">
                    <label class="form-label"><?= __('discount_percent') ?></label>
                    <input type="number" class="form-control discount-input" name="items[][discount]" 
                           min="0" max="100" step="0.01" value="0">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label"><?= __('tax_rate_percent') ?></label>
                    <input type="number" class="form-control tax-rate-input" name="items[][tax_rate]" 
                           min="0" step="0.01" readonly>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label"><?= __('tax_amount') ?></label>
                    <input type="text" class="form-control tax-amount-display" readonly>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label"><?= __('subtotal') ?></label>
                    <input type="text" class="form-control item-total-display" readonly>
                </div>
            </div>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>/crm-project/public/assets/js/quotes.js"></script>
    <script>
        let itemIndex = 0;
        
        document.addEventListener('DOMContentLoaded', function() {
            const addItemBtn = document.getElementById('addItemBtn');
            const itemsContainer = document.getElementById('itemsContainer');
            const noItemsMessage = document.getElementById('noItemsMessage');
            const template = document.getElementById('itemTemplate');
            
            addItemBtn.addEventListener('click', addItem);
            
            function addItem() {
                itemIndex++;
                const clone = template.content.cloneNode(true);
                
                // Update item number
                clone.querySelector('.item-number').textContent = itemIndex;
                clone.querySelector('.item-row').setAttribute('data-item-index', itemIndex);
                
                // Add event listeners
                const removeBtn = clone.querySelector('.remove-item');
                removeBtn.addEventListener('click', function() {
                    removeItem(this.closest('.item-row'));
                });
                
                const productSelect = clone.querySelector('.product-select');
                productSelect.addEventListener('change', function() {
                    updateItemPrices(this.closest('.item-row'));
                });
                
                const inputs = clone.querySelectorAll('.quantity-input, .price-input, .discount-input');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        updateItemPrices(this.closest('.item-row'));
                    });
                });
                
                itemsContainer.appendChild(clone);
                noItemsMessage.style.display = 'none';
                
                updateItemNumbers();
            }
            
            function removeItem(itemRow) {
                if (itemsContainer.children.length > 1) {
                    itemRow.remove();
                    updateItemNumbers();
                    updateQuoteTotal();
                    
                    if (itemsContainer.children.length === 0) {
                        noItemsMessage.style.display = 'block';
                    }
                }
            }
            
            function updateItemNumbers() {
                const items = itemsContainer.querySelectorAll('.item-row');
                items.forEach((item, index) => {
                    item.querySelector('.item-number').textContent = index + 1;
                });
            }
            
            function updateItemPrices(itemRow) {
                const productSelect = itemRow.querySelector('.product-select');
                const quantityInput = itemRow.querySelector('.quantity-input');
                const priceInput = itemRow.querySelector('.price-input');
                const discountInput = itemRow.querySelector('.discount-input');
                const taxRateInput = itemRow.querySelector('.tax-rate-input');
                const taxAmountDisplay = itemRow.querySelector('.tax-amount-display');
                const itemTotalDisplay = itemRow.querySelector('.item-total-display');
                
                // Update price and tax rate from selected product
                if (productSelect.value) {
                    const selectedOption = productSelect.selectedOptions[0];
                    priceInput.value = selectedOption.dataset.price || 0;
                    taxRateInput.value = selectedOption.dataset.taxRate || 0;
                }
                
                // Calculate totals
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(priceInput.value) || 0;
                const discountPercent = parseFloat(discountInput.value) || 0;
                const taxRate = parseFloat(taxRateInput.value) || 0;
                
                const subtotalBeforeDiscount = quantity * unitPrice;
                const discountAmount = (subtotalBeforeDiscount * discountPercent) / 100;
                const subtotalAfterDiscount = subtotalBeforeDiscount - discountAmount;
                const taxAmount = (subtotalAfterDiscount * taxRate) / 100;
                const itemTotal = subtotalAfterDiscount + taxAmount;
                
                taxAmountDisplay.value = '$' + taxAmount.toFixed(2);
                itemTotalDisplay.value = '$' + itemTotal.toFixed(2);
                
                updateQuoteTotal();
            }
            
            function updateQuoteTotal() {
                let subtotal = 0;
                let totalDiscount = 0;
                let totalTax = 0;
                
                const items = itemsContainer.querySelectorAll('.item-row');
                items.forEach(item => {
                    const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
                    const unitPrice = parseFloat(item.querySelector('.price-input').value) || 0;
                    const discountPercent = parseFloat(item.querySelector('.discount-input').value) || 0;
                    const taxRate = parseFloat(item.querySelector('.tax-rate-input').value) || 0;
                    
                    const itemSubtotal = quantity * unitPrice;
                    const itemDiscount = (itemSubtotal * discountPercent) / 100;
                    const itemAfterDiscount = itemSubtotal - itemDiscount;
                    const itemTax = (itemAfterDiscount * taxRate) / 100;
                    
                    subtotal += itemSubtotal;
                    totalDiscount += itemDiscount;
                    totalTax += itemTax;
                });
                
                const total = subtotal - totalDiscount + totalTax;
                
                document.getElementById('subtotalAmount').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('discountAmount').textContent = '$' + totalDiscount.toFixed(2);
                document.getElementById('taxAmount').textContent = '$' + totalTax.toFixed(2);
                document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
            }
            
            // Auto-calculate expiry date
            document.getElementById('issue_date').addEventListener('change', function() {
                const issueDate = new Date(this.value);
                if (issueDate) {
                    const expiryDate = new Date(issueDate);
                    expiryDate.setDate(expiryDate.getDate() + 7);
                    document.getElementById('expiry_date').value = expiryDate.toISOString().split('T')[0];
                }
            });
        });
    </script>
</body>
</html>