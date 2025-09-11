<?php
/**
 * Add Product View with Navigation Integration
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../config/db.php';

requireLogin();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('add_product') ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Error Messages -->
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><?= __('add_product') ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= url('dashboard', 'index') ?>"><?= __('dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= url('products', 'list') ?>"><?= __('products') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= __('add_product') ?></li>
                    </ol>
                </nav>
            </div>
            <a href="<?= url('products', 'list') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <?= __('back_to_list') ?>
            </a>
        </div>

        <!-- Add Product Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle"></i> <?= __('new_product_details') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="addProductForm">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label"><?= __('product_name') ?> *</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" 
                                               value="<?= sanitizeOutput($_POST['product_name'] ?? '') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label"><?= __('sku') ?> *</label>
                                        <input type="text" class="form-control" id="sku" name="sku" 
                                               value="<?= sanitizeOutput($_POST['sku'] ?? '') ?>" required>
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
                                                    <?= (($_POST['category_id'] ?? '') == $category['category_id']) ? 'selected' : '' ?>>
                                                <?= sanitizeOutput($category['category_name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (empty($categories)): ?>
                                        <div class="form-text text-warning">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            <?= __('no_categories_available') ?>
                                            <a href="<?= url('products', 'categories') ?>"><?= __('create_category_first') ?></a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="price" class="form-label"><?= __('price') ?> *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   step="0.01" min="0" value="<?= $_POST['price'] ?? '' ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="tax_rate" class="form-label"><?= __('tax_rate') ?> (%)</label>
                                        <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                               step="0.01" min="0" max="100" value="<?= $_POST['tax_rate'] ?? '0' ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label"><?= __('stock_quantity') ?> *</label>
                                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                               min="0" value="<?= $_POST['stock_quantity'] ?? '0' ?>" required>
                                        <div class="form-text"><?= __('initial_stock_help') ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= url('products', 'list') ?>" class="btn btn-secondary">
                                    <?= __('cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> <?= __('create_product') ?>
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
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
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
        document.getElementById('stock_quantity').addEventListener('input', function() {
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
        });
        
        // Check if categories are available
        const categorySelect = document.getElementById('category_id');
        if (categorySelect.options.length <= 1) {
            document.getElementById('addProductForm').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('<?= __('create_category_first_message') ?>');
                window.location.href = '<?= url('products', 'categories') ?>';
            });
        }
    </script>
</body>
</html>