<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('add_product') ?: 'Add Product'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-plus-square"></i> <?php echo __('add_product') ?: 'Add Product'; ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>"><?php echo __('dashboard') ?: 'Dashboard'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('products', 'products'); ?>"><?php echo __('products') ?: 'Products'; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo __('add_product') ?: 'Add Product'; ?></li>
                    </ol>
                </nav>
            </div>
            <a href="<?php echo url('products', 'products'); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> <?php echo __('back_to_list') ?: 'Back to List'; ?>
            </a>
        </div>

        <!-- Alerts -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo sanitizeOutput($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Product Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo __('product_details') ?: 'Product Details'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="productForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="create_product">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">
                                            <?php echo __('product_name') ?: 'Product Name'; ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" 
                                               value="<?php echo sanitizeOutput($_POST['product_name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">
                                            <?php echo __('sku') ?: 'SKU'; ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="sku" name="sku" 
                                               value="<?php echo sanitizeOutput($_POST['sku'] ?? ''); ?>" 
                                               placeholder="PROD-001" required>
                                        <div class="form-text"><?php echo __('sku_help') ?: 'Unique product identifier'; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    <?php echo __('category') ?: 'Category'; ?> <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value=""><?php echo __('select_category') ?: 'Select Category'; ?></option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>"
                                            <?php echo ($_POST['category_id'] ?? '') == $category['category_id'] ? 'selected' : ''; ?>>
                                            <?php echo sanitizeOutput($category['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label"><?php echo __('description') ?: 'Description'; ?></label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="<?php echo __('product_description_placeholder') ?: 'Describe the product features and specifications...'; ?>"><?php echo sanitizeOutput($_POST['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            <?php echo __('price') ?: 'Price'; ?> <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   value="<?php echo sanitizeOutput($_POST['price'] ?? ''); ?>" 
                                                   step="0.01" min="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tax_rate" class="form-label"><?php echo __('tax_rate') ?: 'Tax Rate'; ?> (%)</label>
                                        <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                               value="<?php echo sanitizeOutput($_POST['tax_rate'] ?? '0.00'); ?>" 
                                               step="0.01" min="0" max="100">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label">
                                            <?php echo __('stock_quantity') ?: 'Stock Quantity'; ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                               value="<?php echo sanitizeOutput($_POST['stock_quantity'] ?? '0'); ?>" 
                                               min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_stock_level" class="form-label"><?php echo __('min_stock_level') ?: 'Minimum Stock Level'; ?></label>
                                        <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" 
                                               value="<?php echo sanitizeOutput($_POST['min_stock_level'] ?? '10'); ?>" 
                                               min="0">
                                        <div class="form-text"><?php echo __('min_stock_help') ?: 'Alert when stock falls below this level'; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="<?php echo url('products', 'products'); ?>" class="btn btn-secondary me-2">
                                    <?php echo __('cancel') ?: 'Cancel'; ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> <?php echo __('create_product') ?: 'Create Product'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Product Preview -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo __('preview') ?: 'Preview'; ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="productPreview">
                            <h6 class="product-name text-muted"><?php echo __('product_name') ?: 'Product Name'; ?></h6>
                            <p class="product-sku"><small class="text-muted"><?php echo __('sku') ?: 'SKU'; ?>: <code class="sku-code">PROD-XXX</code></small></p>
                            <p class="product-category"><small><?php echo __('category') ?: 'Category'; ?>: <span class="category-name text-muted"><?php echo __('select_category') ?: 'Select Category'; ?></span></small></p>
                            <p class="product-price h5 text-primary">$<span class="price-value">0.00</span></p>
                            <p class="product-stock">
                                <?php echo __('stock') ?: 'Stock'; ?>: <span class="badge bg-secondary stock-badge">0</span>
                                <small class="text-muted">/ Min: <span class="min-stock">10</span></small>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> <?php echo __('help') ?: 'Help'; ?></h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li><i class="bi bi-check-circle text-success"></i> <?php echo __('help_required_fields') ?: 'Fields marked with * are required'; ?></li>
                            <li><i class="bi bi-check-circle text-success"></i> <?php echo __('help_sku_unique') ?: 'SKU must be unique across all products'; ?></li>
                            <li><i class="bi bi-check-circle text-success"></i> <?php echo __('help_min_stock') ?: 'Minimum stock triggers low inventory alerts'; ?></li>
                            <li><i class="bi bi-check-circle text-success"></i> <?php echo __('help_tax_rate') ?: 'Tax rate is applied during quote generation'; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Real-time preview updates
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('productForm');
            
            // Update preview on input changes
            form.addEventListener('input', updatePreview);
            form.addEventListener('change', updatePreview);
            
            function updatePreview() {
                const name = document.getElementById('product_name').value || '<?php echo __('product_name') ?: 'Product Name'; ?>';
                const sku = document.getElementById('sku').value || 'PROD-XXX';
                const price = document.getElementById('price').value || '0.00';
                const stock = document.getElementById('stock_quantity').value || '0';
                const minStock = document.getElementById('min_stock_level').value || '10';
                const categorySelect = document.getElementById('category_id');
                const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || '<?php echo __('select_category') ?: 'Select Category'; ?>';
                
                document.querySelector('.product-name').textContent = name;
                document.querySelector('.sku-code').textContent = sku;
                document.querySelector('.category-name').textContent = categoryName;
                document.querySelector('.price-value').textContent = parseFloat(price).toFixed(2);
                document.querySelector('.stock-badge').textContent = stock;
                document.querySelector('.min-stock').textContent = minStock;
                
                // Update stock badge color
                const stockBadge = document.querySelector('.stock-badge');
                const stockNum = parseInt(stock);
                const minStockNum = parseInt(minStock);
                
                stockBadge.className = 'badge ' + (stockNum <= minStockNum ? 'bg-warning' : 'bg-success');
            }
            
            // SKU auto-generation suggestion
            const nameInput = document.getElementById('product_name');
            const skuInput = document.getElementById('sku');
            
            nameInput.addEventListener('blur', function() {
                if (!skuInput.value && nameInput.value) {
                    const suggestion = nameInput.value
                        .toUpperCase()
                        .replace(/[^A-Z0-9]/g, '-')
                        .substring(0, 10) + '-' + Math.random().toString(36).substring(2, 5).toUpperCase();
                    skuInput.value = suggestion;
                    updatePreview();
                }
            });
        });
    </script>
</body>
</html>