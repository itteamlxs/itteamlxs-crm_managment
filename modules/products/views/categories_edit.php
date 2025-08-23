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
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('edit_category') ?: 'Edit Category'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-pencil-square"></i> <?php echo __('edit_category') ?: 'Edit Category'; ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>"><?php echo __('dashboard') ?: 'Dashboard'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('products', 'products'); ?>"><?php echo __('products') ?: 'Products'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('products', 'categories'); ?>"><?php echo __('categories') ?: 'Categories'; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo __('edit_category') ?: 'Edit Category'; ?></li>
                    </ol>
                </nav>
            </div>
            <a href="<?php echo url('products', 'categories'); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> <?php echo __('back_to_categories') ?: 'Back to Categories'; ?>
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

        <!-- Category Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo __('category_details') ?: 'Category Details'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="categoryForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="update_category">

                            <div class="mb-4">
                                <label for="category_name" class="form-label">
                                    <?php echo __('category_name') ?: 'Category Name'; ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="category_name" name="category_name" 
                                       value="<?php echo sanitizeOutput($category['category_name']); ?>" 
                                       required maxlength="100">
                                <div class="form-text"><?php echo __('category_name_help') ?: 'Choose a descriptive name for this product category'; ?></div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label"><?php echo __('description') ?: 'Description'; ?></label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                          placeholder="<?php echo __('category_description_placeholder') ?: 'Describe what types of products belong to this category...'; ?>" 
                                          maxlength="500"><?php echo sanitizeOutput($category['description'] ?? ''); ?></textarea>
                                <div class="form-text d-flex justify-content-between">
                                    <span><?php echo __('category_description_help') ?: 'Optional: Provide details about this category'; ?></span>
                                    <span class="character-count">0/500</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="<?php echo url('products', 'categories'); ?>" class="btn btn-secondary me-2">
                                    <?php echo __('cancel') ?: 'Cancel'; ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> <?php echo __('update_category') ?: 'Update Category'; ?>
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
                                <h6><?php echo __('delete_category') ?: 'Delete Category'; ?></h6>
                                <p class="text-muted mb-0"><?php echo __('delete_category_warning') ?: 'This will permanently delete the category. Categories with products cannot be deleted.'; ?></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deleteCategory(<?php echo $category['category_id']; ?>, '<?php echo sanitizeOutput($category['category_name']); ?>')">
                                    <i class="bi bi-trash"></i> <?php echo __('delete_category') ?: 'Delete Category'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <!-- Current Category Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> <?php echo __('current_info') ?: 'Current Information'; ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-tag text-primary me-2" style="font-size: 1.5rem;"></i>
                            <h6 class="mb-0"><?php echo sanitizeOutput($category['category_name']); ?></h6>
                        </div>
                        
                        <?php if (!empty($category['description'])): ?>
                            <p class="text-muted small"><?php echo sanitizeOutput($category['description']); ?></p>
                        <?php endif; ?>
                        
                        <hr>
                        <p class="small mb-1">
                            <strong><?php echo __('created') ?: 'Created'; ?>:</strong><br>
                            <span class="text-muted"><?php echo formatDate($category['created_at'] ?? '', 'M d, Y H:i'); ?></span>
                        </p>
                        
                        <?php if (!empty($category['updated_at'])): ?>
                        <p class="small mb-0">
                            <strong><?php echo __('last_updated') ?: 'Last Updated'; ?>:</strong><br>
                            <span class="text-muted"><?php echo formatDate($category['updated_at'], 'M d, Y H:i'); ?></span>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Category Stats -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-graph-up"></i> <?php echo __('statistics') ?: 'Statistics'; ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-12 mb-3">
                                <h4 class="text-primary mb-0">
                                    <?php 
                                    // Get product count for this category
                                    try {
                                        $db = Database::getInstance();
                                        $productCount = $db->fetch("SELECT COUNT(*) as count FROM products WHERE category_id = ?", [$category['category_id']]);
                                        echo $productCount['count'];
                                    } catch (Exception $e) {
                                        echo '0';
                                    }
                                    ?>
                                </h4>
                                <small class="text-muted"><?php echo __('products') ?: 'Products'; ?></small>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <a href="<?php echo url('products', 'products', ['category_id' => $category['category_id']]); ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> <?php echo __('view_products') ?: 'View Products'; ?>
                            </a>
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
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                <?php echo __('help_category_edit') ?: 'Changes will apply to all products in this category'; ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                                <?php echo __('help_category_delete') ?: 'Categories with products cannot be deleted'; ?>
                            </li>
                            <li>
                                <i class="bi bi-info-circle text-info"></i>
                                <?php echo __('help_category_organization') ?: 'Well-organized categories improve product discoverability'; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <input type="hidden" name="action" value="delete_category">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('categoryForm');
            const nameInput = document.getElementById('category_name');
            const descInput = document.getElementById('description');
            const charCount = document.querySelector('.character-count');
            
            // Store original values for change detection
            const originalName = nameInput.value;
            const originalDesc = descInput.value;
            
            // Character count
            function updateCharCount() {
                const count = descInput.value.length;
                charCount.textContent = count + '/500';
                charCount.className = 'character-count ' + (count > 450 ? 'text-warning' : count > 480 ? 'text-danger' : 'text-muted');
            }
            
            // Highlight changes
            function highlightChanges() {
                if (nameInput.value !== originalName) {
                    nameInput.classList.add('border-warning');
                } else {
                    nameInput.classList.remove('border-warning');
                }
                
                if (descInput.value !== originalDesc) {
                    descInput.classList.add('border-warning');
                } else {
                    descInput.classList.remove('border-warning');
                }
            }
            
            // Event listeners
            nameInput.addEventListener('input', highlightChanges);
            descInput.addEventListener('input', function() {
                updateCharCount();
                highlightChanges();
            });
            
            // Form validation
            form.addEventListener('submit', function(e) {
                const name = nameInput.value.trim();
                
                if (name.length < 2) {
                    e.preventDefault();
                    alert('<?php echo __('category_name_too_short') ?: 'Category name must be at least 2 characters long'; ?>');
                    nameInput.focus();
                    return false;
                }
                
                if (name.length > 100) {
                    e.preventDefault();
                    alert('<?php echo __('category_name_too_long') ?: 'Category name cannot exceed 100 characters'; ?>');
                    nameInput.focus();
                    return false;
                }
            });
            
            // Initial updates
            updateCharCount();
        });
        
        function deleteCategory(id, name) {
            if (confirm('<?php echo __('confirm_delete_category') ?: 'Are you sure you want to delete category'; ?> "' + name + '"?\n\n<?php echo __('delete_category_confirmation') ?: 'This action cannot be undone. Categories with products cannot be deleted.'; ?>')) {
                const form = document.getElementById('deleteForm');
                form.action = '<?php echo url('products', 'categories'); ?>&sub_action=edit&id=' + id;
                form.submit();
            }
        }
    </script>
</body>
</html>