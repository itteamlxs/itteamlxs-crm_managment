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
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('edit_product') ?: 'Edit Product'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-pencil-square"></i> <?php echo __('edit_product') ?: 'Edit Product'; ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>"><?php echo __('dashboard') ?: 'Dashboard'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('products', 'products'); ?>"><?php echo __('products') ?: 'Products'; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo __('edit_product') ?: 'Edit Product'; ?></li>
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
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo sanitizeOutput($success); ?>
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
                            <input type="hidden" name="action" value="update_product">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">
                                            <?php echo __('product_name') ?: 'Product Name'; ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" 
                                               value="<?php echo sanitizeOutput($product['product_name']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">
                                            <?php echo __('sku') ?: 'SKU'; ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="sku" name="sku" 
                                               value="<?php echo sanitizeOutput($product['sku']); ?>" required>
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
                                            <?php echo $product['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                                            <?php echo sanitizeOutput($category['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label"><?php echo __('description') ?: 'Description'; ?></label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="<?php echo __('product_description_placeholder') ?: 'Describe the product features and specifications...'; ?>"><?php echo sanitizeOutput($product['description'] ?? ''); ?></textarea>
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
                                                   value="<?php echo $product['price']; ?>" 
                                                   step="0.01" min="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tax_rate" class="form-label"><?php echo __('tax_rate') ?: 'Tax Rate'; ?> (%)</label>
                                        <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                               value="<?php echo $product['tax_rate'] ?? '0.00'; ?>" 
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
                                               value="<?php echo $product['stock_quantity']; ?>" 
                                               min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_stock_level" class="form-label"><?php echo __('min_stock_level') ?: 'Minimum Stock Level'; ?></label>
                                        <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" 
                                               value="<?php echo $product['min_stock_level'] ?? '10'; ?>" 
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
                                    <i class="bi bi-save"></i> <?php echo __('update_product') ?: 'Update Product'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone -->
                <?php if (hasPermission('delete_products')): ?>
                <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> <?php echo __('danger_zone') ?: 'Danger Zone'; ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6><?php echo __('delete_product') ?: 'Delete Product'; ?></h6>
                                <p class="text-muted mb-0"><?php echo __('delete_product_warning') ?: 'This will deactivate the product and set stock to zero. This action cannot be undone.'; ?></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deleteProduct(<?php echo $product['product_id']; ?>, '<?php echo sanitizeOutput($product['product_name']); ?>')">
                                    <i class="bi bi-trash"></i> <?php echo __('delete_product') ?: 'Delete Product'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <!-- Current Product Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo __('current_info') ?: 'Current Information'; ?></h6>
                    </div>
                    <div class="card-body">
                        <h6><?php echo sanitizeOutput($product['product_name']); ?></h6>
                        <p class="small"><strong><?php echo __('sku') ?: 'SKU'; ?>:</strong> <code><?php echo sanitizeOutput($product['sku']); ?></code></p>
                        <p class="small"><strong><?php echo __('category') ?: 'Category'; ?>:</strong> <?php echo sanitizeOutput($product['category_name']); ?></p>
                        <p class="small"><strong><?php echo __('price') ?: 'Price'; ?>:</strong> <?php echo formatCurrency($product['price']); ?></p>
                        <p class="small">
                            <strong><?php echo __('stock') ?: 'Stock'; ?>:</strong> 
                            <span class="badge <?php echo $product['stock_quantity'] <= ($product['min_stock_level'] ?? 10) ? 'bg-warning' : 'bg-success'; ?>">
                                <?php echo $product['stock_quantity']; ?>
                            </span>
                        </p>
                        <p class="small">
                            <strong><?php echo __('status') ?: 'Status'; ?>:</strong>
                            <span class="badge <?php echo $product['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $product['is_active'] ? (__('active') ?: 'Active') : (__('inactive') ?: 'Inactive'); ?>
                            </span>
                        </p>
                        <hr>
                        <p class="small text-muted">
                            <strong><?php echo __('created') ?: 'Created'; ?>:</strong><br>
                            <?php echo formatDate($product['created_at'] ?? '', 'M d, Y H:i'); ?>
                        </p>
                        <?php if (!empty($product['updated_at'])): ?>
                        <p class="small text-muted">
                            <strong><?php echo __('last_updated') ?: 'Last Updated'; ?>:</strong><br>
                            <?php echo formatDate($product['updated_at'], 'M d, Y H:i'); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> <?php echo __('help') ?: 'Help'; ?></h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li><i class="bi bi-check-circle text-success"></i> <?php echo __('help_sku_unique') ?: 'SKU must be unique across all products'; ?></li>
                            <li><i class="bi bi-check-circle text-success"></i> <?php echo __('help_stock_changes') ?: 'Stock changes are logged for auditing'; ?></li>
                            <li><i class="bi bi-check-circle text-success"></i> <?php echo __('help_active_quotes') ?: 'Products with active quotes cannot be deleted'; ?></li>
                            <li><i class="bi bi-exclamation-triangle text-warning"></i> <?php echo __('help_price_changes') ?: 'Price changes affect new quotes only'; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="action" value="delete_product">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProduct(id, name) {
            if (confirm('<?php echo __('confirm_delete_product') ?: 'Are you sure you want to delete'; ?> "' + name + '"?\n\n<?php echo __('delete_product_confirmation') ?: 'This will deactivate the product and cannot be undone.'; ?>')) {
                const form = document.getElementById('deleteForm');
                form.action = '<?php echo url('products', 'products'); ?>&sub_action=edit&id=' + id;
                form.submit();
            }
        }
        
        // Highlight changes
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('productForm');
            const inputs = form.querySelectorAll('input, select, textarea');
            
            // Store original values
            const originalValues = {};
            inputs.forEach(input => {
                originalValues[input.name] = input.value;
            });
            
            // Check for changes
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value !== originalValues[this.name]) {
                        this.classList.add('border-warning');
                    } else {
                        this.classList.remove('border-warning');
                    }
                });
            });
        });
    </script>
</body>
</html>