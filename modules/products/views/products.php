<?php
/**
 * Products List View with Navigation Integration
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
    <title><?= __('products') ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Success Messages -->
        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= sanitizeOutput($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Error Messages -->
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= sanitizeOutput($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Low Stock Warning -->
        <?php if (!empty($lowStockProducts)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6><i class="bi bi-exclamation-triangle"></i> <?= __('low_stock_warning') ?></h6>
            <p><?= __('low_stock_products_found', ['count' => count($lowStockProducts)]) ?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><?= __('products') ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= url('dashboard', 'index') ?>"><?= __('dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= __('products') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?= url('products', 'categories') ?>" class="btn btn-outline-primary me-2">
                    <i class="bi bi-tags"></i> <?= __('manage_categories') ?>
                </a>
                <a href="<?= url('products', 'add') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> <?= __('add_product') ?>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="module" value="products">
                    <input type="hidden" name="action" value="list">
                    
                    <div class="col-md-4">
                        <label for="search" class="form-label"><?= __('search') ?></label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?= sanitizeOutput($search) ?>" 
                               placeholder="<?= __('search_products_placeholder') ?>">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="category_id" class="form-label"><?= __('category') ?></label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value=""><?= __('all_categories') ?></option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>" 
                                    <?= ($category_id == $category['category_id']) ? 'selected' : '' ?>>
                                <?= sanitizeOutput($category['category_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="limit" class="form-label"><?= __('per_page') ?></label>
                        <select class="form-select" id="limit" name="limit">
                            <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i> <?= __('search') ?>
                            </button>
                            <a href="<?= url('products', 'list') ?>" class="btn btn-outline-secondary">
                                <?= __('clear') ?>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products List -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <?= __('products_list') ?> 
                    <span class="badge bg-secondary"><?= number_format($total) ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($products)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= __('product_name') ?></th>
                                <th><?= __('sku') ?></th>
                                <th><?= __('category') ?></th>
                                <th><?= __('price') ?></th>
                                <th><?= __('stock_quantity') ?></th>
                                <th><?= __('created_at') ?></th>
                                <th><?= __('actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <strong><?= sanitizeOutput($product['product_name']) ?></strong>
                                </td>
                                <td>
                                    <code><?= sanitizeOutput($product['sku']) ?></code>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?= sanitizeOutput($product['category_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= formatCurrency($product['price']) ?>
                                    <?php if ($product['tax_rate'] > 0): ?>
                                    <small class="text-muted">(+<?= $product['tax_rate'] ?>% tax)</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= ($product['stock_quantity'] < 10) ? 'bg-warning' : 'bg-success' ?>">
                                        <?= number_format($product['stock_quantity']) ?>
                                    </span>
                                    <?php if ($product['stock_quantity'] < 10): ?>
                                    <i class="bi bi-exclamation-triangle text-warning" title="<?= __('low_stock') ?>"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= formatDate($product['created_at'], 'M d, Y') ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= url('products', 'edit', ['id' => $product['product_id']]) ?>" 
                                           class="btn btn-outline-primary" title="<?= __('edit') ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="deleteProduct(<?= $product['product_id'] ?>, '<?= sanitizeOutput($product['product_name']) ?>')"
                                                title="<?= __('delete') ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Products pagination">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('products', 'list', array_merge($_GET, ['page' => $page - 1])) ?>">
                                <?= __('previous') ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('products', 'list', array_merge($_GET, ['page' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('products', 'list', array_merge($_GET, ['page' => $page + 1])) ?>">
                                <?= __('next') ?>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <h5 class="mt-3"><?= __('no_products_found') ?></h5>
                    <p class="text-muted">
                        <?php if (!empty($search) || $category_id > 0): ?>
                            <?= __('no_products_match_search') ?>
                        <?php else: ?>
                            <?= __('no_products_available') ?>
                        <?php endif; ?>
                    </p>
                    <a href="<?= url('products', 'add') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> <?= __('add_first_product') ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="product_id" id="deleteProductId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url() ?>/assets/js/products.js"></script>
    <script>
        function deleteProduct(productId, productName) {
            if (confirm('<?= __('confirm_delete_product') ?>: ' + productName + '?\n<?= __('this_action_cannot_be_undone') ?>')) {
                document.getElementById('deleteProductId').value = productId;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>