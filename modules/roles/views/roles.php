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
    <title><?= __('roles_management') ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-shield-check"></i> <?= __('roles_management') ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= dashboardUrl() ?>"><?= __('dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= __('roles_management') ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?= url('roles', 'list', ['sub_action' => 'add']) ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> <?= __('add_role') ?>
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?= sanitizeOutput($_SESSION['success_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?= sanitizeOutput($_SESSION['error_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Roles Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table"></i> <?= __('roles_list') ?>
                    <?php if (!empty($roles)): ?>
                        <span class="badge bg-secondary ms-2"><?= count($roles) ?></span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($roles)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><?= __('role_name') ?></th>
                                    <th><?= __('description') ?></th>
                                    <th><?= __('users') ?></th>
                                    <th><?= __('created_at') ?></th>
                                    <th width="200"><?= __('actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td>
                                            <strong><?= sanitizeOutput($role['role_name']) ?></strong>
                                        </td>
                                        <td><?= sanitizeOutput($role['description'] ?? '') ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= (int)$role['user_count'] ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= formatDate($role['created_at'], 'M j, Y H:i') ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= url('roles', 'list', ['sub_action' => 'edit', 'id' => $role['role_id']]) ?>" 
                                                   class="btn btn-outline-primary" title="<?= __('edit') ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= url('roles', 'assign', ['role_id' => $role['role_id']]) ?>" 
                                                   class="btn btn-outline-info" title="<?= __('assign_permissions') ?>">
                                                    <i class="bi bi-key"></i>
                                                </a>
                                                <?php if (isset($role['can_delete']) && $role['can_delete']): ?>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="confirmDelete(<?= (int)$role['role_id'] ?>, '<?= sanitizeOutput(addslashes($role['role_name'])) ?>')"
                                                            title="<?= __('delete') ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-outline-secondary" 
                                                            disabled title="<?= __('cannot_delete_role_with_users') ?>">
                                                        <i class="bi bi-lock"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-person-badge display-1 text-muted"></i>
                        <h4 class="mt-3"><?= __('no_roles_available') ?></h4>
                        <p class="text-muted">
                            <?= __('no_roles_available') ?>
                        </p>
                        <a href="<?= url('roles', 'list', ['sub_action' => 'add']) ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> <?= __('add_role') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle text-danger"></i>
                            <?= __('confirm_delete') ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><?= __('confirm_delete_role') ?> <strong id="roleName"></strong>?</p>
                        <p class="text-muted small"><?= __('this_action_cannot_be_undone') ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= __('cancel') ?>
                        </button>
                        <form method="POST" action="<?= url('roles', 'list', ['sub_action' => 'delete']) ?>" class="d-inline">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            <input type="hidden" name="role_id" id="deleteRoleId">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> <?= __('delete') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        function confirmDelete(roleId, roleName) {
            document.getElementById('deleteRoleId').value = roleId;
            document.getElementById('roleName').textContent = roleName;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>