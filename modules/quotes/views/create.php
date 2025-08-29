<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput(__('create_quote')) ?> - <?= sanitizeOutput(__('app_name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><?= sanitizeOutput(__('create_quote')) ?></h2>
                        <p class="text-muted"><?= sanitizeOutput(__('create_new_quote_description')) ?></p>
                    </div>
                    <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> <?= sanitizeOutput(__('back_to_list')) ?>
                    </a>
                </div>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= sanitizeOutput($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Quote Form -->
                <form id="quoteForm" method="POST" action="<?= url('quotes', 'create') ?>">
                    <input type="hidden" name="csrf_token" value="<?= sanitizeOutput($csrfToken) ?>">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Quote Details -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><?= sanitizeOutput(__('quote_details')) ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="client_id" class="form-label">
                                                    <?= sanitizeOutput(__('client')) ?> <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="client_id" name="client_id" required>
                                                    <option value=""><?= sanitizeOutput(__('select_client')) ?></option>
                                                    <?php foreach ($clients as $client): ?>
                                                        <option value="<?= sanitizeOutput($client['client_id']) ?>">
                                                            <?= sanitizeOutput($client['company_name']) ?> - <?= sanitizeOutput($client['contact_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="expiry_days" class="form-label">
                                                    <?= sanitizeOutput(__('expiry_days')) ?>
                                                </label>
                                                <input type="number" class="form-control" id="expiry_days" 
                                                       name="expiry_days" value="7" min="1" max="365">
                                                <div class="form-text"><?= sanitizeOutput(__('expiry_days_help')) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quote Items -->
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><?= sanitizeOutput(__('quote_items')) ?></h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                                        <i class="bi bi-plus-circle"></i> <?= sanitizeOutput(__('add_item')) ?>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="quoteItems">
                                        <!-- Quote items will be added dynamically -->
                                    </div>
                                    
                                    <div class="text-center py-3" id="noItemsMessage">
                                        <i class="bi bi-cart-plus display-4 text-muted"></i>
                                        <p class="text-muted mt-2"><?= sanitizeOutput(__('no_items_added')) ?></p>
                                        <button type="button" class="btn btn-primary" onclick="addQuoteItem()">
                                            <i class="bi bi-plus-circle"></i> <?= sanitizeOutput(__('add_first_item')) ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Summary -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><?= sanitizeOutput(__('quote_summary')) ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <span><?= sanitizeOutput(__('subtotal')) ?>:</span>
                                        <span id="subtotalAmount">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><?= sanitizeOutput(__('discount')) ?>:</span>
                                        <span id="discountAmount">$0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><?= sanitizeOutput(__('tax')) ?>:</span>
                                        <span id="taxAmount">$0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span><?= sanitizeOutput(__('total')) ?>:</span>
                                        <span id="totalAmount">$0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary" id="saveQuoteBtn">
                                            <i class="bi bi-check-lg"></i> <?= sanitizeOutput(__('create_quote')) ?>
                                        </button>
                                        <a href="<?= url('quotes', 'list') ?>" class="btn btn-outline-secondary">
                                            <?= sanitizeOutput(__('cancel')) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Item Template -->
    <template id="itemTemplate">
        <div class="quote-item border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="mb-0"><?= sanitizeOutput(__('item')) ?> <span class="item-number">1</span></h6>
                <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><?= sanitizeOutput(__('product')) ?> <span class="text-danger">*</span></label>
                        <select class="form-select product-select" name="items[][product_id]" required>
                            <option value=""><?= sanitizeOutput(__('select_product')) ?></option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= sanitizeOutput($product['product_id']) ?>" 
                                        data-price="<?= sanitizeOutput($product['price']) ?>"
                                        data-tax-rate="<?= sanitizeOutput($product['tax_rate']) ?>"
                                        data-stock="<?= sanitizeOutput($product['stock_quantity']) ?>">
                                    <?= sanitizeOutput($product['product_name']) ?> (<?= sanitizeOutput($product['sku']) ?>) - 
                                    <?= sanitizeOutput(formatCurrency($product['price'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><?= sanitizeOutput(__('quantity')) ?></label>
                        <input type="number" class="form-control quantity-input" 
                               name="items[][quantity]" min="1" value="1" required>
                        <small class="form-text text-muted stock-info"></small>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><?= sanitizeOutput(__('discount')) ?> (%)</label>
                        <input type="number" class="form-control discount-input" 
                               name="items[][discount]" min="0" max="100" step="0.01" value="0">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted"><?= sanitizeOutput(__('unit_price')) ?>: <span class="unit-price">$0.00</span></small>
                </div>
                <div class="col-md-6 text-end">
                    <strong><?= sanitizeOutput(__('subtotal')) ?>: <span class="item-subtotal">$0.00</span></strong>
                </div>
            </div>
        </div>
    </template>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        const csrfToken = '<?= sanitizeOutput($csrfToken) ?>';
        const translations = {
            'item': '<?= sanitizeOutput(__('item')) ?>',
            'select_product': '<?= sanitizeOutput(__('select_product')) ?>',
            'stock_available': '<?= sanitizeOutput(__('stock_available')) ?>',
            'insufficient_stock': '<?= sanitizeOutput(__('insufficient_stock')) ?>',
            'confirm_remove_item': '<?= sanitizeOutput(__('confirm_remove_item')) ?>',
            'at_least_one_item': '<?= sanitizeOutput(__('at_least_one_item_required')) ?>'
        };
    </script>
    <script src="assets/js/quotes.js"></script>
</body>
</html>