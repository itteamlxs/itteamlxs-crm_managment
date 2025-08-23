<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['language'] ?? 'es'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/assets/css/custom.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Add Product Category</h4>
                        <a href="/public/index.php?module=products&action=list_categories" class="btn btn-secondary btn-sm">Back to Categories</a>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Category created successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                            
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?php echo !empty($errors) && in_array('Category name is required.', $errors) || in_array('Category name must be 100 characters or less.', $errors) || in_array('Category name already exists.', $errors) ? 'is-invalid' : ''; ?>" 
                                       id="category_name" 
                                       name="category_name" 
                                       value="<?php echo htmlspecialchars($_POST['category_name'] ?? ''); ?>"
                                       maxlength="100"
                                       required>
                                <div class="form-text">Maximum 100 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control <?php echo !empty($errors) && in_array('Description must be 1000 characters or less.', $errors) ? 'is-invalid' : ''; ?>" 
                                          id="description" 
                                          name="description" 
                                          rows="3"
                                          maxlength="1000"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                <div class="form-text">Maximum 1000 characters</div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="/public/index.php?module=products&action=list_categories" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/assets/js/products.js"></script>
</body>
</html>