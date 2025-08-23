<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-box"></i> Products Management</h2>
            <div>
                <a href="<?php echo url('products', 'categories'); ?>" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-tags"></i> Categories
                </a>
                <a href="<?php echo dashboardUrl(); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo sanitizeOutput($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Low Stock Alert -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>Low Stock Warning:</strong> <?php echo count($lowStockProducts); ?> products are running low on stock.
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <!-- Search and Actions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="module" value="products">
                            <input type="hidden" name="action" value="list">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="search" 
                                       value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" 
                                       placeholder="Search products, SKU, or category...">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                            <div class="col-md-3">
                                <?php if (hasPermission('manage_products')): ?>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i class="bi bi-plus"></i> Add Product
                                </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Products List (<?php echo $productsData['total']; ?> total)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productsData['products'])): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Tax Rate</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productsData['products'] as $product): ?>
                                    <tr <?php echo $product['stock_quantity'] <= 5 ? 'class="table-warning"' : ''; ?>>
                                        <td><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><code><?php echo htmlspecialchars($product['sku'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                                        <td><?php echo htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                                        <td>
                                            <span class="badge <?php echo $product['stock_quantity'] <= 5 ? 'bg-warning' : 'bg-success'; ?>">
                                                <?php echo $product['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $product['tax_rate']; ?>%</td>
                                        <td>
                                            <?php if (hasPermission('manage_products')): ?>
                                            <a href="?module=products&action=list&edit_id=<?php echo $product['product_id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($productsData['pages'] > 1): ?>
                        <nav>
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $productsData['pages']; $i++): ?>
                                <li class="page-item <?php echo $i === $productsData['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?module=products&action=list&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <p class="text-muted">No products found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Categories Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Categories</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($categories as $category): ?>
                        <div class="d-flex justify-content-between">
                            <span><?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="badge bg-secondary"><?php echo $category['product_count']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <?php if (!empty($lowStockProducts)): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 text-warning">Low Stock Alert</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($lowStockProducts as $product): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <small><?php echo htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></small>
                            <span class="badge bg-warning"><?php echo $product['stock_quantity']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Product Modal -->
        <?php if (hasPermission('manage_products')): ?>
        <div class="modal fade" id="addProductModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit_product' : 'create_product'; ?>">
                        <?php if ($editProduct): ?>
                        <input type="hidden" name="product_id" value="<?php echo $editProduct['product_id']; ?>">
                        <?php endif; ?>
                        
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="product_name" 
                                       value="<?php echo htmlspecialchars($editProduct['product_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SKU *</label>
                                <input type="text" class="form-control" name="sku" 
                                       value="<?php echo htmlspecialchars($editProduct['sku'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Category *</label>
                                        <select class="form-select" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>"
                                                    <?php echo ($editProduct['category_id'] ?? '') == $category['category_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Price *</label>
                                        <input type="number" class="form-control" name="price" step="0.01" 
                                               value="<?php echo $editProduct['price'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tax Rate (%)</label>
                                        <input type="number" class="form-control" name="tax_rate" step="0.01" 
                                               value="<?php echo $editProduct['tax_rate'] ?? '0'; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" name="stock_quantity" 
                                               value="<?php echo $editProduct['stock_quantity'] ?? '0'; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editProduct ? 'Update Product' : 'Create Product'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if ($editProduct): ?>
        // Auto-open modal for editing
        document.addEventListener('DOMContentLoaded', function() {
            new bootstrap.Modal(document.getElementById('addProductModal')).show();
        });
        <?php endif; ?>
    </script>
</body>
</html>