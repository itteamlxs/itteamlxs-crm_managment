<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('assign_permissions') ?: 'Assign Permissions'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-key"></i> <?php echo __('assign_permissions') ?: 'Assign Permissions'; ?></h2>
                <?php if ($role): ?>
                <p class="text-muted mb-0"><?php echo __('role') ?: 'Role'; ?>: <strong><?php echo sanitizeOutput($role['role_name']); ?></strong></p>
                <?php endif; ?>
            </div>
            <a href="<?php echo url('roles', 'list'); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <?php echo __('back_to_roles') ?: 'Back to Roles'; ?>
            </a>
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

        <?php if ($role): ?>
        <!-- Permissions Form -->
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo __('permissions') ?: 'Permissions'; ?></h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="selectAll()">
                            <i class="bi bi-check-all"></i> <?php echo __('select_all') ?: 'Select All'; ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectNone()">
                            <i class="bi bi-x-square"></i> <?php echo __('select_none') ?: 'Select None'; ?>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($permissionsByModule)): ?>
                        <?php foreach ($permissionsByModule as $module => $permissions): ?>
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="bi bi-folder"></i> <?php echo ucfirst($module); ?>
                            </h6>
                            <div class="row">
                                <?php foreach ($permissions as $permission): ?>
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input permission-check" 
                                               type="checkbox" 
                                               id="perm_<?php echo $permission['permission_id']; ?>"
                                               name="permissions[]" 
                                               value="<?php echo $permission['permission_id']; ?>"
                                               <?php echo in_array($permission['permission_id'], $rolePermissions) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="perm_<?php echo $permission['permission_id']; ?>">
                                            <strong><?php echo sanitizeOutput($permission['permission_name']); ?></strong>
                                            <?php if (!empty($permission['description'])): ?>
                                            <br><small class="text-muted"><?php echo sanitizeOutput($permission['description']); ?></small>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="d-flex justify-content-end pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> <?php echo __('save_permissions') ?: 'Save Permissions'; ?>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-key text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2"><?php echo __('no_permissions_available') ?: 'No permissions available'; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <?php else: ?>
        <!-- Role not found -->
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> <?php echo __('role_not_found') ?: 'Role not found'; ?>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectAll() {
            document.querySelectorAll('.permission-check').forEach(checkbox => {
                checkbox.checked = true;
            });
        }
        
        function selectNone() {
            document.querySelectorAll('.permission-check').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    </script>
</body>
</html>