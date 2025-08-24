<?php
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/url_helper.php';
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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0"><?= __('roles_management') ?></h1>
                    <div>
                        <a href="<?= url('roles', 'list', ['sub_action' => 'add']) ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> <?= __('add_role') ?>
                        </a>
                        <a href="<?= dashboardUrl() ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-house"></i> <?= __('back_to_dashboard') ?>
                        </a>
                    </div>
                </div>

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

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= __('roles_list') ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($roles)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
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
                                                <td><?= formatDate($role['created_at'], 'Y-m-d H:i') ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?= url('roles', 'list', ['sub_action' => 'edit', 'id' => $role['role_id']]) ?>" 
                                                           class="btn btn-outline-primary btn-sm" title="<?= __('edit') ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="<?= url('roles', 'assign', ['role_id' => $role['role_id']]) ?>" 
                                                           class="btn btn-outline-info btn-sm" title="<?= __('assign_permissions') ?>">
                                                            <i class="bi bi-key"></i>
                                                        </a>
                                                        <?php if (isset($role['can_delete']) && $role['can_delete']): ?>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                    onclick="confirmDelete(<?= (int)$role['role_id'] ?>, '<?= sanitizeOutput(addslashes($role['role_name'])) ?>')"
                                                                    title="<?= __('delete') ?>">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
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
                                <h5 class="text-muted mt-3"><?= __('no_roles_available') ?></h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('confirm_delete') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><?= __('confirm_delete_role') ?> <strong id="roleName"></strong>?</p>
                    <p class="text-muted small"><?= __('this_action_cannot_be_undone') ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('cancel') ?></button>
                    <form method="POST" action="<?= url('roles', 'list', ['sub_action' => 'delete']) ?>" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <input type="hidden" name="role_id" id="deleteRoleId">
                        <button type="submit" class="btn btn-danger"><?= __('delete') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(roleId, roleName) {
            document.getElementById('deleteRoleId').value = roleId;
            document.getElementById('roleName').textContent = roleName;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
</body>
</html>