<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

requireLogin();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('edit_product') ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= url() ?>/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Error Messages -->
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= sanitizeOutput($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-pencil-square"></i> <?= __('edit_product') ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= dashboardUrl() ?>"><i class="bi bi-house"></i> <?= __('dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= url('products', 'list') ?>"><?= __('products') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= __('edit_product') ?></li>
                    </ol>
                </nav>
            </div>
            <a href="<?= url('products', 'list') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <?= __('back_to_list') ?>
            </a>
        </div>

        <!-- Edit Product Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-box"></i> <?= __('product_details') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="editProductForm">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label"><?= __('product_name') ?> *</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" 
                                               value="<?= sanitizeOutput($_POST['product_name'] ?? $product['product_name']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label"><?= __('sku') ?> *</label>
                                        <input type="text" class="form-control" id="sku" name="sku" 
                                               value="<?= sanitizeOutput($_POST['sku'] ?? $product['sku']) ?>" required>
                                        <div class="form-text"><?= __('sku_format_help') ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label"><?= __('category') ?> *</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value=""><?= __('select_category') ?></option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['category_id'] ?>" 
                                                    <?php
                                                    $selectedCategory = $_POST['category_id'] ?? $product['category_id'];
                                                    echo ($selectedCategory == $category['category_id']) ? 'selected' : '';
                                                    ?>>
                                                <?= sanitizeOutput($category['category_name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="price" class="form-label"><?= __('price') ?> *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   step="0.01" min="0" 
                                                   value="<?= $_POST['price'] ?? $product['price'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="tax_rate" class="form-label"><?= __('tax_rate') ?> (%)</label>
                                        <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                               step="0.01" min="0" max="100" 
                                               value="<?= $_POST['tax_rate'] ?? $product['tax_rate'] ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label"><?= __('stock_quantity') ?> *</label>
                                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                               min="0" value="<?= $_POST['stock_quantity'] ?? $product['stock_quantity'] ?>" required>
                                        <div class="form-text"><?= __('current_stock_help') ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6><?= __('product_information') ?></h6>
                                        <small class="text-muted">
                                            <strong><?= __('created_at') ?>:</strong> <?= formatDate($product['created_at']) ?><br>
                                            <strong><?= __('updated_at') ?>:</strong> <?= formatDate($product['updated_at']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= url('products', 'list') ?>" class="btn btn-secondary">
                                    <?= __('cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> <?= __('update_product') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url() ?>/assets/js/products.js"></script>
    <script>
        // Form validation
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            const productName = document.getElementById('product_name').value.trim();
            const sku = document.getElementById('sku').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const stockQuantity = parseInt(document.getElementById('stock_quantity').value);
            const categoryId = document.getElementById('category_id').value;
            
            let errors = [];
            
            if (!productName) {
                errors.push('<?= __('product_name_required') ?>');
            }
            
            if (!sku) {
                errors.push('<?= __('sku_required') ?>');
            } else if (!/^[a-zA-Z0-9_-]{1,50}$/.test(sku)) {
                errors.push('<?= __('invalid_sku_format') ?>');
            }
            
            if (!price || price <= 0) {
                errors.push('<?= __('price_required') ?>');
            }
            
            if (isNaN(stockQuantity) || stockQuantity < 0) {
                errors.push('<?= __('invalid_stock_quantity') ?>');
            }
            
            if (!categoryId) {
                errors.push('<?= __('category_required') ?>');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
        
        // SKU formatting
        document.getElementById('sku').addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
        });
        
        // Price formatting
        document.getElementById('price').addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
        
        // Stock quantity warning
        const stockInput = document.getElementById('stock_quantity');
        const originalStock = <?= $product['stock_quantity'] ?>;
        
        stockInput.addEventListener('input', function() {
            const currentValue = parseInt(this.value);
            const warningThreshold = 10;
            
            // Remove existing warnings
            const existingWarning = this.parentNode.querySelector('.stock-warning');
            if (existingWarning) {
                existingWarning.remove();
            }
            
            if (currentValue >= 0 && currentValue < warningThreshold) {
                const warning = document.createElement('div');
                warning.className = 'form-text text-warning stock-warning';
                warning.innerHTML = '<i class="bi bi-exclamation-triangle"></i> <?= __('low_stock_warning') ?>';
                this.parentNode.appendChild(warning);
            }
            
            if (currentValue < originalStock) {
                const changeWarning = document.createElement('div');
                changeWarning.className = 'form-text text-info stock-warning';
                changeWarning.innerHTML = '<i class="bi bi-info-circle"></i> <?= __('stock_decrease_warning') ?>';
                this.parentNode.appendChild(changeWarning);
            }
        });
    </script>
</body>
</html>