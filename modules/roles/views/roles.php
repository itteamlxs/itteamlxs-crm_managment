<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('roles_management') ?: 'Roles Management'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-shield-check"></i> <?php echo __('roles_management') ?: 'Roles Management'; ?></h2>
            <div>
                <a href="<?php echo dashboardUrl(); ?>" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> <?php echo __('back_to_dashboard') ?: 'Dashboard'; ?>
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="bi bi-plus-circle"></i> <?php echo __('add_role') ?: 'Add Role'; ?>
                </button>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?php echo sanitizeOutput($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo sanitizeOutput($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Roles Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?php echo __('roles_list') ?: 'Roles List'; ?></h5>
            </div>
            <div class="card-body">
                <?php if (!empty($roles)): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo __('role_name') ?: 'Role Name'; ?></th>
                                <th><?php echo __('description') ?: 'Description'; ?></th>
                                <th><?php echo __('created_at') ?: 'Created'; ?></th>
                                <th><?php echo __('actions') ?: 'Actions'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roles as $role): ?>
                            <tr>
                                <td><strong><?php echo sanitizeOutput($role['role_name']); ?></strong></td>
                                <td><?php echo sanitizeOutput($role['description'] ?? ''); ?></td>
                                <td><?php echo formatDate($role['created_at'], 'Y-m-d'); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo url('roles', 'assign', ['id' => $role['role_id']]); ?>" 
                                           class="btn btn-outline-primary" title="<?php echo __('assign_permissions') ?: 'Assign Permissions'; ?>">
                                            <i class="bi bi-key"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-secondary" 
                                                onclick="editRole(<?php echo $role['role_id']; ?>, '<?php echo sanitizeOutput($role['role_name']); ?>', '<?php echo sanitizeOutput($role['description'] ?? ''); ?>')"
                                                title="<?php echo __('edit') ?: 'Edit'; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="bi bi-shield-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2"><?php echo __('no_roles_available') ?: 'No roles available'; ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo __('add_role') ?: 'Add Role'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-3">
                            <label for="role_name" class="form-label"><?php echo __('role_name') ?: 'Role Name'; ?> *</label>
                            <input type="text" class="form-control" id="role_name" name="role_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label"><?php echo __('description') ?: 'Description'; ?></label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('cancel') ?: 'Cancel'; ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo __('create_role') ?: 'Create Role'; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo __('edit_role') ?: 'Edit Role'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="role_id" id="edit_role_id">
                        
                        <div class="mb-3">
                            <label for="edit_role_name" class="form-label"><?php echo __('role_name') ?: 'Role Name'; ?> *</label>
                            <input type="text" class="form-control" id="edit_role_name" name="role_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label"><?php echo __('description') ?: 'Description'; ?></label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('cancel') ?: 'Cancel'; ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo __('update_role') ?: 'Update Role'; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editRole(roleId, roleName, description) {
            document.getElementById('edit_role_id').value = roleId;
            document.getElementById('edit_role_name').value = roleName;
            document.getElementById('edit_description').value = description;
            
            const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            editModal.show();
        }
    </script>
</body>
</html>