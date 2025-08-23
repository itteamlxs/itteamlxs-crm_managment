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
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('categories') ?: 'Categories'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-tags"></i> <?php echo __('categories') ?: 'Categories'; ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>"><?php echo __('dashboard') ?: 'Dashboard'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('products', 'products'); ?>"><?php echo __('products') ?: 'Products'; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo __('categories') ?: 'Categories'; ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <?php if (hasPermission('create_products')): ?>
                <a href="<?php echo url('products', 'categories', ['sub_action' => 'add']); ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> <?php echo __('add_category') ?: 'Add Category'; ?>
                </a>
                <?php endif; ?>
                <a href="<?php echo url('products', 'products'); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-box"></i> <?php echo __('products') ?: 'Products'; ?>
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

        <!-- Categories Grid -->
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-tag text-primary"></i>
                                        <?php echo sanitizeOutput($category['category_name']); ?>
                                    </h5>
                                    <?php if (hasPermission('edit_products') || hasPermission('delete_products')): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if (hasPermission('edit_products')): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo url('products', 'categories', ['sub_action' => 'edit', 'id' => $category['category_id']]); ?>">
                                                    <i class="bi bi-pencil"></i> <?php echo __('edit') ?: 'Edit'; ?>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            <?php if (hasPermission('delete_products')): ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="deleteCategory(<?php echo $category['category_id']; ?>, '<?php echo sanitizeOutput($category['category_name']); ?>')">
                                                    <i class="bi bi-trash"></i> <?php echo __('delete') ?: 'Delete'; ?>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($category['description'])): ?>
                                    <p class="card-text text-muted"><?php echo sanitizeOutput($category['description']); ?></p>
                                <?php endif; ?>
                                
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-primary mb-0"><?php echo $category['product_count'] ?? 0; ?></h4>
                                        <small class="text-muted"><?php echo __('products') ?: 'Products'; ?></small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success mb-0"><?php echo formatCurrency($category['total_value'] ?? 0); ?></h4>
                                        <small class="text-muted"><?php echo __('total_value') ?: 'Total Value'; ?></small>
                                    </div>
                                </div>
                                
                                <?php if (isset($category['low_stock_count']) && $category['low_stock_count'] > 0): ?>
                                    <div class="alert alert-warning mt-3 mb-0" role="alert">
                                        <small>
                                            <i class="bi bi-exclamation-triangle"></i>
                                            <?php echo $category['low_stock_count']; ?> <?php echo __('products_low_stock') ?: 'products low on stock'; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <?php echo __('created') ?: 'Created'; ?>: <?php echo formatDate($category['created_at'] ?? '', 'M d, Y'); ?>
                                    </small>
                                    <a href="<?php echo url('products', 'products', ['category_id' => $category['category_id']]); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <?php echo __('view_products') ?: 'View Products'; ?>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-tags" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3"><?php echo __('no_categories_found') ?: 'No categories found'; ?></h4>
                        <p class="text-muted"><?php echo __('no_categories_available') ?: 'There are no product categories available yet.'; ?></p>
                        <?php if (hasPermission('create_products')): ?>
                            <a href="<?php echo url('products', 'categories', ['sub_action' => 'add']); ?>" class="btn btn-primary">
                                <i class="bi bi-plus"></i> <?php echo __('create_first_category') ?: 'Create First Category'; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Stats -->
        <?php if (!empty($categories)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-graph-up"></i> <?php echo __('category_summary') ?: 'Category Summary'; ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <h3 class="text-primary"><?php echo count($categories); ?></h3>
                                <small class="text-muted"><?php echo __('total_categories') ?: 'Total Categories'; ?></small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-success"><?php echo array_sum(array_column($categories, 'product_count')); ?></h3>
                                <small class="text-muted"><?php echo __('total_products') ?: 'Total Products'; ?></small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-info"><?php echo formatCurrency(array_sum(array_column($categories, 'total_value'))); ?></h3>
                                <small class="text-muted"><?php echo __('total_inventory_value') ?: 'Total Inventory Value'; ?></small>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-warning"><?php echo array_sum(array_column($categories, 'low_stock_count')); ?></h3>
                                <small class="text-muted"><?php echo __('low_stock_items') ?: 'Low Stock Items'; ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="action" value="delete_category">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteCategory(id, name) {
            if (confirm('<?php echo __('confirm_delete_category') ?: 'Are you sure you want to delete category'; ?> "' + name + '"?\n\n<?php echo __('delete_category_warning') ?: 'This action cannot be undone. Categories with products cannot be deleted.'; ?>')) {
                const form = document.getElementById('deleteForm');
                form.action = '<?php echo url('products', 'categories'); ?>&sub_action=list&id=' + id;
                form.submit();
            }
        }
    </script>
</body>
</html>