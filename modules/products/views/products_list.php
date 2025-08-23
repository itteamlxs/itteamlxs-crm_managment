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
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('products') ?: 'Products'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-box"></i> <?php echo __('products') ?: 'Products'; ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>"><?php echo __('dashboard') ?: 'Dashboard'; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo __('products') ?: 'Products'; ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <?php if (hasPermission('create_products')): ?>
                <a href="<?php echo url('products', 'products', ['sub_action' => 'add']); ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> <?php echo __('add_product') ?: 'Add Product'; ?>
                </a>
                <?php endif; ?>
                <a href="<?php echo url('products', 'categories'); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-tags"></i> <?php echo __('categories') ?: 'Categories'; ?>
                </a>
            </div>
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

        <!-- Low Stock Alert -->
        <?php if (!empty($lowStockProducts) && count($lowStockProducts) > 0): ?>
            <div class="alert alert-warning" role="alert">
                <i class="bi bi-exclamation-triangle"></i> 
                <strong><?php echo __('low_stock_alert') ?: 'Low Stock Alert'; ?>:</strong>
                <?php echo count($lowStockProducts); ?> <?php echo __('products_low_stock') ?: 'products are running low on stock'; ?>
                <a href="#lowStockModal" class="alert-link" data-bs-toggle="modal"><?php echo __('view_details') ?: 'View Details'; ?></a>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="module" value="products">
                    <input type="hidden" name="action" value="products">
                    
                    <div class="col-md-4">
                        <label class="form-label"><?php echo __('search') ?: 'Search'; ?></label>
                        <input type="text" name="search" class="form-control" 
                               value="<?php echo sanitizeOutput($_GET['search'] ?? ''); ?>"
                               placeholder="<?php echo __('search_products_placeholder') ?: 'Product name or SKU...'; ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label"><?php echo __('category') ?: 'Category'; ?></label>
                        <select name="category_id" class="form-select">
                            <option value=""><?php echo __('all_categories') ?: 'All Categories'; ?></option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>"
                                    <?php echo ($_GET['category_id'] ?? '') == $category['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo sanitizeOutput($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label"><?php echo __('per_page') ?: 'Per Page'; ?></label>
                        <select name="limit" class="form-select">
                            <option value="10" <?php echo ($_GET['limit'] ?? 10) == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo ($_GET['limit'] ?? 10) == 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo ($_GET['limit'] ?? 10) == 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search"></i> <?php echo __('search') ?: 'Search'; ?>
                        </button>
                        <a href="<?php echo url('products', 'products'); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> <?php echo __('clear') ?: 'Clear'; ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo __('products_list') ?: 'Products List'; ?> 
                    <span class="badge bg-primary"><?php echo $productsData['total']; ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($productsData['products'])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><?php echo __('product_name') ?: 'Product Name'; ?></th>
                                    <th><?php echo __('sku') ?: 'SKU'; ?></th>
                                    <th><?php echo __('category') ?: 'Category'; ?></th>
                                    <th><?php echo __('price') ?: 'Price'; ?></th>
                                    <th><?php echo __('stock') ?: 'Stock'; ?></th>
                                    <th><?php echo __('status') ?: 'Status'; ?></th>
                                    <th><?php echo __('actions') ?: 'Actions'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productsData['products'] as $product): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo sanitizeOutput($product['product_name']); ?></strong>
                                            <?php if (!empty($product['description'])): ?>
                                                <br><small class="text-muted"><?php echo sanitizeOutput(substr($product['description'], 0, 50)); ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><code><?php echo sanitizeOutput($product['sku']); ?></code></td>
                                        <td><?php echo sanitizeOutput($product['category_name']); ?></td>
                                        <td><?php echo formatCurrency($product['price']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $product['stock_quantity'] <= ($product['min_stock_level'] ?? 10) ? 'bg-warning' : 'bg-success'; ?>">
                                                <?php echo $product['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $product['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $product['is_active'] ? (__('active') ?: 'Active') : (__('inactive') ?: 'Inactive'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <?php if (hasPermission('edit_products')): ?>
                                                <a href="<?php echo url('products', 'products', ['sub_action' => 'edit', 'id' => $product['product_id']]); ?>" 
                                                   class="btn btn-outline-primary" title="<?php echo __('edit') ?: 'Edit'; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if (hasPermission('delete_products')): ?>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="deleteProduct(<?php echo $product['product_id']; ?>, '<?php echo sanitizeOutput($product['product_name']); ?>')"
                                                        title="<?php echo __('delete') ?: 'Delete'; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($productsData['pages'] > 1): ?>
                        <nav aria-label="Products pagination">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo $productsData['current_page'] <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo url('products', 'products', array_merge($_GET, ['page' => $productsData['current_page'] - 1])); ?>">
                                        <?php echo __('previous') ?: 'Previous'; ?>
                                    </a>
                                </li>
                                
                                <?php for ($i = max(1, $productsData['current_page'] - 2); $i <= min($productsData['pages'], $productsData['current_page'] + 2); $i++): ?>
                                    <li class="page-item <?php echo $i == $productsData['current_page'] ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo url('products', 'products', array_merge($_GET, ['page' => $i])); ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?php echo $productsData['current_page'] >= $productsData['pages'] ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo url('products', 'products', array_merge($_GET, ['page' => $productsData['current_page'] + 1])); ?>">
                                        <?php echo __('next') ?: 'Next'; ?>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-box" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3"><?php echo __('no_products_found') ?: 'No products found'; ?></h5>
                        <p class="text-muted"><?php echo __('no_products_match_search') ?: 'No products match your search criteria.'; ?></p>
                        <?php if (hasPermission('create_products')): ?>
                            <a href="<?php echo url('products', 'products', ['sub_action' => 'add']); ?>" class="btn btn-primary">
                                <i class="bi bi-plus"></i> <?php echo __('add_first_product') ?: 'Add First Product'; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Low Stock Modal -->
    <?php if (!empty($lowStockProducts)): ?>
    <div class="modal fade" id="lowStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo __('low_stock_products') ?: 'Low Stock Products'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><?php echo __('product_name') ?: 'Product'; ?></th>
                                    <th><?php echo __('current_stock') ?: 'Current Stock'; ?></th>
                                    <th><?php echo __('min_level') ?: 'Min Level'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockProducts as $product): ?>
                                    <tr>
                                        <td><?php echo sanitizeOutput($product['product_name']); ?></td>
                                        <td><span class="badge bg-warning"><?php echo $product['stock_quantity']; ?></span></td>
                                        <td><?php echo $product['min_stock_level']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="action" value="delete_product">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProduct(id, name) {
            if (confirm('<?php echo __('confirm_delete_product') ?: 'Are you sure you want to delete'; ?> "' + name + '"?')) {
                const form = document.getElementById('deleteForm');
                form.action = '<?php echo url('products', 'products'); ?>&sub_action=list&id=' + id;
                form.submit();
            }
        }
    </script>
</body>
</html>