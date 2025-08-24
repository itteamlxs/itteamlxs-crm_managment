<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?= sanitizeOutput(getUserLanguage()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput($pageTitle) ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0"><?= sanitizeOutput($pageTitle) ?></h1>
                    <div>
                        <a href="<?= url('roles', 'list') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> <?= __('back_to_roles') ?>
                        </a>
                    </div>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= sanitizeOutput($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-badge"></i> 
                            <?= isset($isEdit) && $isEdit ? __('edit_role') : __('add_role') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            
                            <div class="mb-3">
                                <label for="role_name" class="form-label">
                                    <?= __('role_name') ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= !empty($errors) && strpos(implode(' ', $errors), 'role_name') !== false ? 'is-invalid' : '' ?>" 
                                       id="role_name" 
                                       name="role_name" 
                                       value="<?= sanitizeOutput($formData['role_name'] ?? '') ?>" 
                                       required 
                                       maxlength="50"
                                       placeholder="<?= __('role_name') ?>">
                                <div class="form-text">
                                    3-50 caracteres. Solo letras, números, espacios y guiones bajos.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    <?= __('description') ?> <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control <?= !empty($errors) && strpos(implode(' ', $errors), 'description') !== false ? 'is-invalid' : '' ?>" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          required 
                                          placeholder="<?= __('description') ?>"><?= sanitizeOutput($formData['description'] ?? '') ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= url('roles', 'list') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> <?= __('cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> 
                                    <?= isset($isEdit) && $isEdit ? __('update_role') : __('create_role') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/crm-project/public/assets/js/roles.js"></script>
</body>
</html>