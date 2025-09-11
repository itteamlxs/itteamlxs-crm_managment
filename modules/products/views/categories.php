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
    <title><?= __('categories') ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= url() ?>/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Success Messages -->
        <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= sanitizeOutput($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

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
                <h1><i class="bi bi-tags"></i> <?= __('categories') ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= dashboardUrl() ?>"><i class="bi bi-house"></i> <?= __('dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= url('products', 'list') ?>"><?= __('products') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= __('categories') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?= url('products', 'list') ?>" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> <?= __('back_to_products') ?>
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg"></i> <?= __('add_category') ?>
                </button>
            </div>
        </div>

        <!-- Categories List -->
        <div class="row">
            <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">
                                <?= sanitizeOutput($category['category_name']) ?>
                            </h5>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item" onclick="editCategory(<?= $category['category_id'] ?>, '<?= sanitizeOutput($category['category_name']) ?>', '<?= sanitizeOutput($category['description']) ?>')">
                                            <i class="bi bi-pencil"></i> <?= __('edit') ?>
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item text-danger" 
                                                onclick="deleteCategory(<?= $category['category_id'] ?>, '<?= sanitizeOutput($category['category_name']) ?>')">
                                            <i class="bi bi-trash"></i> <?= __('delete') ?>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <?php if (!empty($category['description'])): ?>
                        <p class="card-text text-muted">
                            <?= sanitizeOutput($category['description']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php 
                        $productCount = 0;
                        foreach ($categorySummary as $summary) {
                            if ($summary['category_id'] == $category['category_id']) {
                                $productCount = $summary['product_count'];
                                break;
                            }
                        }
                        ?>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <?= formatDate($category['created_at'], 'M d, Y') ?>
                            </small>
                            <span class="badge bg-primary">
                                <?= $productCount ?> <?= __('products') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <h5 class="mt-3"><?= __('no_categories_found') ?></h5>
                    <p class="text-muted"><?= __('no_categories_available') ?></p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-lg"></i> <?= __('add_first_category') ?>
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('add_category') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="addCategoryForm">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label for="add_category_name" class="form-label"><?= __('category_name') ?> *</label>
                            <input type="text" class="form-control" id="add_category_name" name="category_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="add_description" class="form-label"><?= __('description') ?></label>
                            <textarea class="form-control" id="add_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= __('cancel') ?>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> <?= __('create_category') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('edit_category') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editCategoryForm">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="category_id" id="edit_category_id">
                        
                        <div class="mb-3">
                            <label for="edit_category_name" class="form-label"><?= __('category_name') ?> *</label>
                            <input type="text" class="form-control" id="edit_category_name" name="category_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label"><?= __('description') ?></label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= __('cancel') ?>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> <?= __('update_category') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="category_id" id="deleteCategoryId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url() ?>/assets/js/products.js"></script>
    <script>
        function editCategory(categoryId, categoryName, description) {
            document.getElementById('edit_category_id').value = categoryId;
            document.getElementById('edit_category_name').value = categoryName;
            document.getElementById('edit_description').value = description;
            
            const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editModal.show();
        }
        
        function deleteCategory(categoryId, categoryName) {
            if (confirm('<?= __('confirm_delete_category') ?>: ' + categoryName + '?\n<?= __('this_action_cannot_be_undone') ?>')) {
                document.getElementById('deleteCategoryId').value = categoryId;
                document.getElementById('deleteForm').submit();
            }
        }
        
        // Form validation
        document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
            const categoryName = document.getElementById('add_category_name').value.trim();
            
            if (!categoryName) {
                e.preventDefault();
                alert('<?= __('category_name_required') ?>');
            }
        });
        
        document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
            const categoryName = document.getElementById('edit_category_name').value.trim();
            
            if (!categoryName) {
                e.preventDefault();
                alert('<?= __('category_name_required') ?>');
            }
        });
    </script>
</body>
</html>