<?php
// Prevenir acceso directo
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../../config/app.php';
    require_once __DIR__ . '/../../../core/helpers.php';
    require_once __DIR__ . '/../../../core/rbac.php';
}

// Cargar dependencias necesarias para nav.php
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../config/db.php';
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
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>
                    <i class="bi bi-person-badge"></i>
                    <?= sanitizeOutput($pageTitle) ?>
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= dashboardUrl() ?>"><?= __('dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= url('roles', 'list') ?>"><?= __('roles_management') ?></a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?= isset($isEdit) && $isEdit ? __('edit') : __('add') ?>
                        </li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?= url('roles', 'list') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> <?= __('back_to_roles') ?>
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= sanitizeOutput($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-check"></i> 
                            <?= isset($isEdit) && $isEdit ? __('edit_role') : __('add_role') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" novalidate id="roleForm">
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
                                    <?= __('invalid_role_name_format') ?>
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
                                          maxlength="255"
                                          placeholder="<?= __('description') ?>"><?= sanitizeOutput($formData['description'] ?? '') ?></textarea>
                                <div class="form-text">
                                    Describe el propósito y alcance de este rol.
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between pt-3">
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

                <!-- Additional Info Card -->
                <?php if (isset($isEdit) && $isEdit): ?>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0 text-info">
                                <i class="bi bi-info-circle"></i> <?= __('additional_actions') ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><?= __('assign_permissions') ?></h6>
                                    <p class="text-muted small">
                                        Configura los permisos específicos para este rol.
                                    </p>
                                    <a href="<?= url('roles', 'assign', ['role_id' => $formData['role_id'] ?? '']) ?>" class="btn btn-info">
                                        <i class="bi bi-key"></i> <?= __('assign_permissions') ?>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <h6>Vista de Roles</h6>
                                    <p class="text-muted small">
                                        Regresar a la lista completa de roles del sistema.
                                    </p>
                                    <a href="<?= url('roles', 'list') ?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-list"></i> <?= __('roles_list') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Form validation
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            const roleName = document.getElementById('role_name').value.trim();
            const description = document.getElementById('description').value.trim();
            
            if (roleName.length < 3 || roleName.length > 50) {
                e.preventDefault();
                alert('<?= __('invalid_role_name_format') ?>');
                return false;
            }
            
            if (description.length < 3) {
                e.preventDefault();
                alert('<?= __('description_required') ?>');
                return false;
            }
        });
        
        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
        
        // Character count for description
        const descriptionField = document.getElementById('description');
        const maxLength = 255;
        
        descriptionField.addEventListener('input', function() {
            const remaining = maxLength - this.value.length;
            const formText = this.nextElementSibling;
            
            if (remaining < 50) {
                formText.textContent = `Caracteres restantes: ${remaining}`;
                formText.className = remaining < 20 ? 'form-text text-warning' : 'form-text text-info';
            } else {
                formText.textContent = 'Describe el propósito y alcance de este rol.';
                formText.className = 'form-text';
            }
        });
    </script>
</body>
</html>