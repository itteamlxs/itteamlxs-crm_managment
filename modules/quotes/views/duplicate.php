<!DOCTYPE html>
<html lang="<?php echo getUserLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('duplicate_quote'); ?> - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0"><?php echo __('duplicate_quote'); ?></h1>
                    <a href="<?php echo url('quotes', 'view', ['id' => $originalQuoteId]); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> <?php echo __('back_to_original_quote'); ?>
                    </a>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-files"></i>
                    <?php echo __('duplicating_quote'); ?> <strong><?php echo sanitizeOutput($originalQuote['quote_number']); ?></strong>
                    <?php echo __('for_client'); ?> <strong><?php echo sanitizeOutput($originalQuote['company_name']); ?></strong>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6><?php echo __('please_correct_errors'); ?>:</h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo sanitizeOutput($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form id="quoteForm" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?php echo __('duplicate_quote_details'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="client_id" class="form-label"><?php echo __('client'); ?> <span class="text-danger">*</span></label>
                                            <select class="form-select" id="client_id" name="client_id" required>
                                                <option value=""><?php echo __('select_client'); ?></option>
                                                <?php foreach ($clients as $client): ?>
                                                    <option value="<?php echo $client['client_id']; ?>" 
                                                            <?php echo $quoteData['client_id'] == $client['client_id'] ? 'selected' : ''; ?>>
                                                        <?php echo sanitizeOutput($client['company_name'] . ' - ' . $client['contact_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="status" class="form-label"><?php echo __('status'); ?></label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="DRAFT" <?php echo $quoteData['status'] === 'DRAFT' ? 'selected' : ''; ?>><?php echo __('draft'); ?></option>
                                                <option value="SENT" <?php echo $quoteData['status'] === 'SENT' ? 'selected' : ''; ?>><?php echo __('sent'); ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="issue_date" class="form-label"><?php echo __('issue_date'); ?> <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="issue_date" name="issue_date" 
                                                   value="<?php echo sanitizeOutput($quoteData['issue_date']); ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="expiry_date" class="form-label"><?php echo __('expiry_date'); ?> <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                                                   value="<?php echo sanitizeOutput($quoteData['expiry_date']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0"><?php echo __('duplicate_quote_items'); ?></h5>
                                    <button type="button" id="addItemBtn" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus"></i> <?php echo __('add_item'); ?>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        <?php echo __('duplicate_items_info'); ?>
                                    </div>

                                    <div id="itemsContainer">
                                        <?php foreach ($items as $index => $item): ?>
                                            <div class="item-row border rounded p-3 mb-3" data-item-index="<?php echo $index; ?>">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <h6 class="mb-0"><?php echo __('item'); ?> <span class="item-number"><?php echo $index + 1; ?></span></h6>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label"><?php echo __('product'); ?> <span class="text-danger">*</span></label>
                                                        <select class="form-select product-select" name="items[<?php echo $index; ?>][product_id]" required>
                                                            <option value=""><?php echo __('select_product'); ?></option>
                                                            <?php foreach ($products as $product): ?>
                                                                <option value="<?php echo $product['product_id']; ?>"
                                                                        data-price="<?php echo $product['price']; ?>"
                                                                        data-tax-rate="<?php echo $product['tax_rate']; ?>"
                                                                        data-stock="<?php echo $product['stock_quantity']; ?>"
                                                                        <?php echo $item['product_id'] == $product['product_id'] ? 'selected' : ''; ?>>
                                                                    <?php echo sanitizeOutput($product['product_name'] . ' - ' . $product['sku']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label"><?php echo __('quantity'); ?> <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control quantity-input" 
                                                               name="items[<?php echo $index; ?>][quantity]" 
                                                               value="<?php echo $item['quantity']; ?>" 
                                                               min="1" required>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label"><?php echo __('unit_price'); ?> <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control price-input" 
                                                               name="items[<?php echo $index; ?>][unit_price]" 
                                                               value="<?php echo $item['unit_price']; ?>" 
                                                               step="0.01" min="0.01" required>
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label"><?php echo __('discount'); ?> %</label>
                                                        <input type="number" class="form-control discount-input" 
                                                               name="items[<?php echo $index; ?>][discount]" 
                                                               value="<?php echo $item['discount']; ?>" 
                                                               step="0.01" min="0" max="100">
                                                    </div>
                                                    <div class="col-md-2 mb-3">
                                                        <label class="form-label"><?php echo __('tax_rate'); ?> %</label>
                                                        <input type="number" class="form-control tax-rate-input" 
                                                               name="items[<?php echo $index; ?>][tax_rate]" 
                                                               value="<?php echo $item['tax_rate'] ?? 0; ?>" 
                                                               step="0.01" min="0" readonly>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label"><?php echo __('tax_amount'); ?></label>
                                                        <input type="text" class="form-control tax-amount-display" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label"><?php echo __('item_total'); ?></label>
                                                        <input type="text" class="form-control item-total-display" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div id="noItemsMessage" style="display: none;" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-4"></i>
                                        <p class="mt-2"><?php echo __('no_items_added'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?php echo __('duplicate_quote_summary'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><?php echo __('subtotal'); ?>:</span>
                                        <strong id="subtotalAmount">$0.00</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><?php echo __('discount'); ?>:</span>
                                        <strong id="discountAmount">$0.00</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><?php echo __('tax'); ?>:</span>
                                        <strong id="taxAmount">$0.00</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="h5"><?php echo __('total'); ?>:</span>
                                        <strong class="h5 text-primary" id="totalAmount">$0.00</strong>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-files"></i> <?php echo __('create_duplicate'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0"><?php echo __('original_quote_info'); ?></h6>
                                </div>
                                <div class="card-body">
                                    <small class="text-muted">
                                        <strong><?php echo __('quote_number'); ?>:</strong> <?php echo sanitizeOutput($originalQuote['quote_number']); ?><br>
                                        <strong><?php echo __('status'); ?>:</strong> 
                                        <span class="badge bg-<?php echo $originalQuote['status'] === 'APPROVED' ? 'success' : ($originalQuote['status'] === 'REJECTED' ? 'danger' : 'warning'); ?>">
                                            <?php echo sanitizeOutput($originalQuote['status']); ?>
                                        </span><br>
                                        <strong><?php echo __('original_total'); ?>:</strong> <?php echo formatCurrency($originalQuote['total_amount']); ?><br>
                                        <strong><?php echo __('created_by'); ?>:</strong> <?php echo sanitizeOutput($originalQuote['created_by_name']); ?>
                                    </small>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body text-center">
                                    <i class="bi bi-lightbulb text-info"></i>
                                    <p class="small mb-0 text-muted mt-2">
                                        <?php echo __('duplicate_creates_new_draft_quote'); ?>
                                    </p>
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
        <div class="item-row border rounded p-3 mb-3" data-item-index="">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="mb-0"><?php echo __('item'); ?> <span class="item-number"></span></h6>
                <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label"><?php echo __('product'); ?> <span class="text-danger">*</span></label>
                    <select class="form-select product-select" name="items[][product_id]" required>
                        <option value=""><?php echo __('select_product'); ?></option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['product_id']; ?>"
                                    data-price="<?php echo $product['price']; ?>"
                                    data-tax-rate="<?php echo $product['tax_rate']; ?>"
                                    data-stock="<?php echo $product['stock_quantity']; ?>">
                                <?php echo sanitizeOutput($product['product_name'] . ' - ' . $product['sku']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><?php echo __('quantity'); ?> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control quantity-input" name="items[][quantity]" min="1" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><?php echo __('unit_price'); ?> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control price-input" name="items[][unit_price]" step="0.01" min="0.01" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><?php echo __('discount'); ?> %</label>
                    <input type="number" class="form-control discount-input" name="items[][discount]" step="0.01" min="0" max="100" value="0">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><?php echo __('tax_rate'); ?> %</label>
                    <input type="number" class="form-control tax-rate-input" name="items[][tax_rate]" step="0.01" min="0" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label"><?php echo __('tax_amount'); ?></label>
                    <input type="text" class="form-control tax-amount-display" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo __('item_total'); ?></label>
                    <input type="text" class="form-control item-total-display" readonly>
                </div>
            </div>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url(); ?>/../public/assets/js/quotes.js"></script>
</body>
</html>