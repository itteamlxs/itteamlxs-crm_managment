<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-tags"></i> Product Categories</h2>
            <div>
                <a href="<?php echo url('products', 'list'); ?>" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-box"></i> Back to Products
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

        <div class="row">
            <div class="col-md-8">
                <!-- Categories List -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Categories (<?php echo count($categories); ?> total)</h5>
                        <?php if (hasPermission('manage_products')): ?>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="bi bi-plus"></i> Add Category
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($categories)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Products Count</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo $category['product_count']; ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo isset($category['created_at']) ? formatDate($category['created_at'], 'M d, Y') : 'N/A'; ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-tags text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No categories found. Create your first category to organize products.</p>
                            <?php if (hasPermission('manage_products')): ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="bi bi-plus"></i> Create First Category
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Category Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Category Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h4 mb-0 text-primary"><?php echo count($categories); ?></div>
                                    <small class="text-muted">Total Categories</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h4 mb-0 text-success">
                                        <?php echo array_sum(array_column($categories, 'product_count')); ?>
                                    </div>
                                    <small class="text-muted">Total Products</small>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($categories)): ?>
                        <hr>
                        <h6>Top Categories</h6>
                        <?php 
                        // Sort categories by product count
                        usort($categories, function($a, $b) {
                            return $b['product_count'] - $a['product_count'];
                        });
                        $topCategories = array_slice($categories, 0, 5);
                        ?>
                        <?php foreach ($topCategories as $category): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small"><?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="badge bg-outline-secondary"><?php echo $category['product_count']; ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Category Modal -->
        <?php if (hasPermission('manage_products')): ?>
        <div class="modal fade" id="addCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="category_name" name="category_name" required>
                                <div class="form-text">Choose a descriptive name for the category</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="3" 
                                          placeholder="Brief description of this category..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>