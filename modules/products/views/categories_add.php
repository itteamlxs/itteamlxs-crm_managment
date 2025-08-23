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
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('add_category') ?: 'Add Category'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-plus-square"></i> <?php echo __('add_category') ?: 'Add Category'; ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo dashboardUrl(); ?>"><?php echo __('dashboard') ?: 'Dashboard'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('products', 'products'); ?>"><?php echo __('products') ?: 'Products'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo url('products', 'categories'); ?>"><?php echo __('categories') ?: 'Categories'; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo __('add_category') ?: 'Add Category'; ?></li>
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

        <!-- Category Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo __('category_details') ?: 'Category Details'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="categoryForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="action" value="create_category">

                            <div class="mb-4">
                                <label for="category_name" class="form-label">
                                    <?php echo __('category_name') ?: 'Category Name'; ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="category_name" name="category_name" 
                                       value="<?php echo sanitizeOutput($_POST['category_name'] ?? ''); ?>" 
                                       placeholder="<?php echo __('category_name_placeholder') ?: 'e.g., Electronics, Clothing, Books...'; ?>" 
                                       required maxlength="100">
                                <div class="form-text"><?php echo __('category_name_help') ?: 'Choose a descriptive name for this product category'; ?></div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label"><?php echo __('description') ?: 'Description'; ?></label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                          placeholder="<?php echo __('category_description_placeholder') ?: 'Describe what types of products belong to this category...'; ?>" 
                                          maxlength="500"><?php echo sanitizeOutput($_POST['description'] ?? ''); ?></textarea>
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
                                    <i class="bi bi-save"></i> <?php echo __('create_category') ?: 'Create Category'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Category Preview -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-eye"></i> <?php echo __('preview') ?: 'Preview'; ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="categoryPreview">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-tag text-primary me-2" style="font-size: 1.2rem;"></i>
                                <h6 class="category-name text-muted mb-0"><?php echo __('category_name') ?: 'Category Name'; ?></h6>
                            </div>
                            <p class="category-description text-muted small mb-3"><?php echo __('category_description') ?: 'Category description will appear here...'; ?></p>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary mb-0">0</h4>
                                    <small class="text-muted"><?php echo __('products') ?: 'Products'; ?></small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success mb-0">$0.00</h4>
                                    <small class="text-muted"><?php echo __('value') ?: 'Value'; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> <?php echo __('tips') ?: 'Tips'; ?></h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <?php echo __('tip_category_name') ?: 'Use clear, descriptive names that users will easily understand'; ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <?php echo __('tip_category_organization') ?: 'Organize products logically to make searching easier'; ?>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-lightbulb text-warning"></i>
                                <?php echo __('tip_category_description') ?: 'Good descriptions help with product classification'; ?>
                            </li>
                            <li>
                                <i class="bi bi-info-circle text-info"></i>
                                <?php echo __('tip_category_edit') ?: 'You can always edit or reorganize categories later'; ?>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Common Categories -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-list-ul"></i> <?php echo __('common_categories') ?: 'Common Categories'; ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary category-suggestion" data-name="Electronics">Electronics</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary category-suggestion" data-name="Clothing">Clothing</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary category-suggestion" data-name="Books">Books</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary category-suggestion" data-name="Home & Garden">Home & Garden</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary category-suggestion" data-name="Sports">Sports</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary category-suggestion" data-name="Health & Beauty">Health & Beauty</button>
                        </div>
                        <small class="text-muted mt-2 d-block"><?php echo __('click_to_use') ?: 'Click any suggestion to use it as your category name'; ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('categoryForm');
            const nameInput = document.getElementById('category_name');
            const descInput = document.getElementById('description');
            const charCount = document.querySelector('.character-count');
            
            // Real-time preview updates
            function updatePreview() {
                const name = nameInput.value.trim() || '<?php echo __('category_name') ?: 'Category Name'; ?>';
                const description = descInput.value.trim() || '<?php echo __('category_description') ?: 'Category description will appear here...'; ?>';
                
                document.querySelector('.category-name').textContent = name;
                document.querySelector('.category-description').textContent = description;
            }
            
            // Character count
            function updateCharCount() {
                const count = descInput.value.length;
                charCount.textContent = count + '/500';
                charCount.className = 'character-count ' + (count > 450 ? 'text-warning' : count > 480 ? 'text-danger' : 'text-muted');
            }
            
            // Event listeners
            nameInput.addEventListener('input', updatePreview);
            descInput.addEventListener('input', function() {
                updatePreview();
                updateCharCount();
            });
            
            // Category suggestions
            document.querySelectorAll('.category-suggestion').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.dataset.name;
                    nameInput.value = name;
                    nameInput.focus();
                    updatePreview();
                });
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
            updatePreview();
            updateCharCount();
        });
    </script>
</body>
</html>