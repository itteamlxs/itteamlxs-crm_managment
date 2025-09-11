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
    <style>
        .permission-module {
            transition: all 0.3s ease;
        }
        .permission-module:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .permission-checkbox {
            transform: scale(1.1);
        }
        .module-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stats-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>
                    <i class="bi bi-key"></i>
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
                        <li class="breadcrumb-item active"><?= __('assign_permissions') ?></li>
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
                <strong>¡Error!</strong> Se encontraron los siguientes problemas:
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?= sanitizeOutput($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success ?? false): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> 
                <strong>¡Éxito!</strong> <?= __('permissions_updated_successfully') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Role Info & Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-person-badge text-primary"></i>
                            <?= __('role') ?>: <?= sanitizeOutput($role['role_name']) ?>
                        </h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-info-circle"></i>
                            <?= sanitizeOutput($role['description']) ?>
                        </p>
                        <small class="text-muted">
                            <i class="bi bi-calendar3"></i>
                            Creado: <?= formatDate($role['created_at'] ?? date('Y-m-d'), 'M j, Y') ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check display-4 mb-2 opacity-75"></i>
                        <h4 class="mb-1" id="selectedCount">
                            <?= count($currentPermissionIds ?? []) ?>
                        </h4>
                        <p class="mb-0">
                            Permisos Asignados
                            <br>
                            <small class="opacity-75" id="totalCount">
                                de <?= count($permissionsByModule ?? []) > 0 ? array_sum(array_map('count', $permissionsByModule)) : 0 ?> totales
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Assignment Form -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-key"></i> <?= __('assign_permissions') ?>
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                            <i class="bi bi-check-all"></i> <?= __('select_all') ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectNone()">
                            <i class="bi bi-x-square"></i> <?= __('select_none') ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleCollapse()">
                            <i class="bi bi-arrows-collapse" id="collapseIcon"></i> <span id="collapseText">Colapsar Todo</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($permissionsByModule)): ?>
                    <form method="POST" action="" id="permissionsForm">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        
                        <div class="accordion" id="permissionsAccordion">
                            <?php $moduleIndex = 0; ?>
                            <?php foreach ($permissionsByModule as $module => $permissions): ?>
                                <?php $moduleIndex++; ?>
                                <div class="card permission-module mb-3">
                                    <div class="card-header module-header text-white p-0" id="heading<?= $moduleIndex ?>">
                                        <div class="d-flex justify-content-between align-items-center p-3">
                                            <button class="btn btn-link text-white text-decoration-none fw-bold fs-6" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#collapse<?= $moduleIndex ?>" 
                                                    aria-expanded="true" 
                                                    aria-controls="collapse<?= $moduleIndex ?>">
                                                <i class="bi bi-folder2-open me-2"></i>
                                                <?= sanitizeOutput(ucfirst($module)) ?> 
                                                <span class="badge bg-light text-dark ms-2"><?= count($permissions) ?></span>
                                            </button>
                                            <div class="form-check">
                                                <input class="form-check-input module-checkbox bg-white" 
                                                       type="checkbox" 
                                                       id="module_<?= sanitizeOutput($module) ?>"
                                                       data-module="<?= sanitizeOutput($module) ?>"
                                                       onchange="toggleModule('<?= sanitizeOutput($module) ?>')">
                                                <label class="form-check-label text-white fw-bold" for="module_<?= sanitizeOutput($module) ?>">
                                                    Todo el Módulo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapse<?= $moduleIndex ?>" 
                                         class="accordion-collapse collapse show" 
                                         aria-labelledby="heading<?= $moduleIndex ?>" 
                                         data-bs-parent="#permissionsAccordion">
                                        <div class="card-body bg-light">
                                            <div class="row">
                                                <?php foreach ($permissions as $permission): ?>
                                                    <div class="col-lg-4 col-md-6 mb-3">
                                                        <div class="card h-100 border-0 shadow-sm">
                                                            <div class="card-body p-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[]" 
                                                                           value="<?= $permission['permission_id'] ?>"
                                                                           id="permission_<?= $permission['permission_id'] ?>"
                                                                           data-module="<?= sanitizeOutput($module) ?>"
                                                                           <?= in_array($permission['permission_id'], $currentPermissionIds) ? 'checked' : '' ?>
                                                                           onchange="updateCounts()">
                                                                    <label class="form-check-label w-100" for="permission_<?= $permission['permission_id'] ?>">
                                                                        <div class="d-flex align-items-start">
                                                                            <div>
                                                                                <strong class="text-primary">
                                                                                    <?= sanitizeOutput($permission['permission_name']) ?>
                                                                                </strong>
                                                                                <?php if (!empty($permission['description'])): ?>
                                                                                    <br>
                                                                                    <small class="text-muted">
                                                                                        <?= sanitizeOutput($permission['description']) ?>
                                                                                    </small>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                <a href="<?= url('roles', 'list') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> <?= __('cancel') ?>
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-info" onclick="previewChanges()">
                                    <i class="bi bi-eye"></i> Vista Previa
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-lg"></i> 
                                    <?= __('save_permissions') ?>
                                </button>
                            </div>
                        </div>
                    </form>

                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-key display-1 text-muted"></i>
                        <h4 class="text-muted mt-3"><?= __('no_permissions_available') ?></h4>
                        <p class="text-muted">
                            No hay permisos configurados en el sistema.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Preview Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-eye"></i>
                            Vista Previa de Permisos - <?= sanitizeOutput($role['role_name']) ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="previewContent"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('permissionsForm').submit()">
                            <i class="bi bi-check-lg"></i> Confirmar y Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        let isCollapsed = false;

        function selectAll() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
            
            checkboxes.forEach(checkbox => checkbox.checked = true);
            moduleCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
                checkbox.indeterminate = false;
            });
            
            updateCounts();
        }

        function selectNone() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
            
            checkboxes.forEach(checkbox => checkbox.checked = false);
            moduleCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.indeterminate = false;
            });
            
            updateCounts();
        }

        function toggleModule(module) {
            const moduleCheckbox = document.getElementById('module_' + module);
            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox[data-module="' + module + '"]');
            
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = moduleCheckbox.checked;
            });
            
            updateCounts();
        }

        function toggleCollapse() {
            const collapses = document.querySelectorAll('.accordion-collapse');
            const icon = document.getElementById('collapseIcon');
            const text = document.getElementById('collapseText');
            
            if (isCollapsed) {
                collapses.forEach(collapse => {
                    new bootstrap.Collapse(collapse, { show: true });
                });
                icon.className = 'bi bi-arrows-collapse';
                text.textContent = 'Colapsar Todo';
                isCollapsed = false;
            } else {
                collapses.forEach(collapse => {
                    new bootstrap.Collapse(collapse, { hide: true });
                });
                icon.className = 'bi bi-arrows-expand';
                text.textContent = 'Expandir Todo';
                isCollapsed = true;
            }
        }

        function updateCounts() {
            const selectedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
            const totalCheckboxes = document.querySelectorAll('.permission-checkbox');
            
            document.getElementById('selectedCount').textContent = selectedCheckboxes.length;
            document.getElementById('totalCount').innerHTML = `de ${totalCheckboxes.length} totales`;
            
            // Update module checkboxes
            const modules = document.querySelectorAll('.module-checkbox');
            modules.forEach(moduleCheckbox => {
                const module = moduleCheckbox.getAttribute('data-module');
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
                
                if (allChecked && modulePermissions.length > 0) {
                    moduleCheckbox.checked = true;
                    moduleCheckbox.indeterminate = false;
                } else if (noneChecked) {
                    moduleCheckbox.checked = false;
                    moduleCheckbox.indeterminate = false;
                } else {
                    moduleCheckbox.checked = false;
                    moduleCheckbox.indeterminate = true;
                }
            });
        }

        function previewChanges() {
            const selectedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
            const previewContent = document.getElementById('previewContent');
            
            if (selectedCheckboxes.length === 0) {
                previewContent.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> No hay permisos seleccionados.</div>';
            } else {
                let html = '<div class="row">';
                const permissionsByModule = {};
                
                selectedCheckboxes.forEach(checkbox => {
                    const module = checkbox.getAttribute('data-module');
                    const label = checkbox.nextElementSibling.querySelector('strong').textContent;
                    
                    if (!permissionsByModule[module]) {
                        permissionsByModule[module] = [];
                    }
                    permissionsByModule[module].push(label);
                });
                
                for (const [module, permissions] of Object.entries(permissionsByModule)) {
                    html += `<div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="bi bi-folder"></i> ${module}</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="mb-0">
                                            ${permissions.map(p => `<li>${p}</li>`).join('')}
                                        </ul>
                                    </div>
                                </div>
                            </div>`;
                }
                html += '</div>';
                previewContent.innerHTML = html;
            }
            
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCounts();
            
            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 7000);
            });
            
            // Add form validation
            document.getElementById('permissionsForm').addEventListener('submit', function(e) {
                const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked');
                if (selectedPermissions.length === 0) {
                    e.preventDefault();
                    alert('Por favor seleccione al menos un permiso antes de guardar.');
                    return false;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
                
                // Re-enable after 5 seconds (fallback)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 5000);
            });
        });

        // Add permission checkbox change listeners
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('permission-checkbox')) {
                updateCounts();
            }
        });
    </script>
</body>
</html>