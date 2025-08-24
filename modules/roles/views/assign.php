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
        <div class="row">
            <div class="col-12">
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

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?= __('permissions_updated_successfully') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-key"></i> <?= __('assign_permissions') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= __('role') ?></h6>
                                            <p class="card-text">
                                                <strong><?= sanitizeOutput($role['role_name']) ?></strong><br>
                                                <small class="text-muted"><?= sanitizeOutput($role['description']) ?></small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-2 mb-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                            <i class="bi bi-check-all"></i> <?= __('select_all') ?>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectNone()">
                                            <i class="bi bi-x-square"></i> <?= __('select_none') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($permissionsByModule)): ?>
                                <div class="row">
                                    <div class="col-12">
                                        <h6><?= __('permissions') ?></h6>
                                        
                                        <?php foreach ($permissionsByModule as $module => $permissions): ?>
                                            <div class="card mb-3">
                                                <div class="card-header bg-primary text-white">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 text-capitalize">
                                                            <i class="bi bi-folder"></i> <?= sanitizeOutput(ucfirst($module)) ?>
                                                        </h6>
                                                        <div class="form-check">
                                                            <input class="form-check-input module-checkbox" 
                                                                   type="checkbox" 
                                                                   id="module_<?= sanitizeOutput($module) ?>"
                                                                   data-module="<?= sanitizeOutput($module) ?>"
                                                                   onchange="toggleModule('<?= sanitizeOutput($module) ?>')">
                                                            <label class="form-check-label text-white" for="module_<?= sanitizeOutput($module) ?>">
                                                                <?= __('select_all') ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <?php foreach ($permissions as $permission): ?>
                                                            <div class="col-md-6 col-lg-4 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[]" 
                                                                           value="<?= $permission['permission_id'] ?>"
                                                                           id="permission_<?= $permission['permission_id'] ?>"
                                                                           data-module="<?= sanitizeOutput($module) ?>"
                                                                           <?= in_array($permission['permission_id'], $currentPermissionIds) ? 'checked' : '' ?>>
                                                                    <label class="form-check-label" for="permission_<?= $permission['permission_id'] ?>">
                                                                        <strong><?= sanitizeOutput($permission['permission_name']) ?></strong>
                                                                        <?php if (!empty($permission['description'])): ?>
                                                                            <br><small class="text-muted"><?= sanitizeOutput($permission['description']) ?></small>
                                                                        <?php endif; ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-key display-1 text-muted"></i>
                                    <h5 class="text-muted mt-3"><?= __('no_permissions_available') ?></h5>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="<?= url('roles', 'list') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> <?= __('cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> <?= __('save_permissions') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectAll() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
            
            checkboxes.forEach(checkbox => checkbox.checked = true);
            moduleCheckboxes.forEach(checkbox => checkbox.checked = true);
        }

        function selectNone() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
            
            checkboxes.forEach(checkbox => checkbox.checked = false);
            moduleCheckboxes.forEach(checkbox => checkbox.checked = false);
        }

        function toggleModule(module) {
            const moduleCheckbox = document.getElementById('module_' + module);
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]');
            
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = moduleCheckbox.checked;
            });
        }

        // Update module checkboxes when individual permissions change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('permission-checkbox')) {
                const module = e.target.getAttribute('data-module');
                const moduleCheckbox = document.getElementById('module_' + module);
                const modulePermissions = document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]');
                
                let allChecked = true;
                let noneChecked = true;
                
                modulePermissions.forEach(checkbox => {
                    if (checkbox.checked) {
                        noneChecked = false;
                    } else {
                        allChecked = false;
                    }
                });
                
                if (allChecked) {
                    moduleCheckbox.checked = true;
                    moduleCheckbox.indeterminate = false;
                } else if (noneChecked) {
                    moduleCheckbox.checked = false;
                    moduleCheckbox.indeterminate = false;
                } else {
                    moduleCheckbox.checked = false;
                    moduleCheckbox.indeterminate = true;
                }
            }
        });

        // Initialize module checkbox states
        document.addEventListener('DOMContentLoaded', function() {
            const modules = document.querySelectorAll('.module-checkbox');
            modules.forEach(moduleCheckbox => {
                const module = moduleCheckbox.getAttribute('data-module');
                const event = new Event('change');
                const firstPermission = document.querySelector('.permission-checkbox[data-module="' + module + '"]');
                if (firstPermission) {
                    firstPermission.dispatchEvent(event);
                }
            });
        });
    </script>
</body>
</html>