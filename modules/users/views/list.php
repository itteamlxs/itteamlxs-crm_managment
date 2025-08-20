<?php
// Prevenir acceso directo
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../../config/app.php';
    require_once __DIR__ . '/../../../core/helpers.php';
    require_once __DIR__ . '/../../../core/rbac.php';
}
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('users_management') ?: 'Users Management'); ?> - <?php echo sanitizeOutput(APP_NAME); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-people"></i> <?php echo __('users_management') ?: 'Users Management'; ?></h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/crm-project/public/?module=dashboard&action=index"><?php echo __('dashboard') ?: 'Dashboard'; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo __('users') ?: 'Users'; ?></li>
                    </ol>
                </nav>
            </div>
            <div>
                <?php if (hasPermission('reset_user_password') || getCurrentUser()['is_admin']): ?>
                    <a href="/crm-project/public/?module=users&action=edit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> <?php echo __('add_user') ?: 'Add User'; ?>
                    </a>
                <?php endif; ?>
                <a href="/crm-project/public/?module=users&action=edit&id=<?php echo getCurrentUser()['user_id']; ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-person-gear"></i> <?php echo __('my_profile') ?: 'My Profile'; ?>
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Search and Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="/crm-project/public/" class="row g-3">
                    <input type="hidden" name="module" value="users">
                    <input type="hidden" name="action" value="list">
                    <div class="col-md-6">
                        <label for="search" class="form-label"><?php echo __('search') ?: 'Search'; ?></label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="<?php echo sanitizeOutput($search); ?>"
                               placeholder="<?php echo __('search_users_placeholder') ?: 'Username, email, or display name...'; ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="limit" class="form-label"><?php echo __('per_page') ?: 'Per Page'; ?></label>
                        <select class="form-select" id="limit" name="limit">
                            <option value="10" <?php echo $pagination['limit'] == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo $pagination['limit'] == 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo $pagination['limit'] == 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search"></i> <?php echo __('search') ?: 'Search'; ?>
                        </button>
                        <a href="/crm-project/public/?module=users&action=list" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> <?php echo __('clear') ?: 'Clear'; ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table"></i> <?php echo __('users_list') ?: 'Users List'; ?>
                    <span class="badge bg-secondary ms-2"><?php echo $totalUsers; ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($users)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><?php echo __('profile') ?: 'Profile'; ?></th>
                                    <th><?php echo __('username') ?: 'Username'; ?></th>
                                    <th><?php echo __('email') ?: 'Email'; ?></th>
                                    <th><?php echo __('display_name') ?: 'Display Name'; ?></th>
                                    <th><?php echo __('role') ?: 'Role'; ?></th>
                                    <th><?php echo __('language') ?: 'Language'; ?></th>
                                    <th><?php echo __('status') ?: 'Status'; ?></th>
                                    <th><?php echo __('last_login') ?: 'Last Login'; ?></th>
                                    <th><?php echo __('actions') ?: 'Actions'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $userItem): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($userItem['profile_picture'])): ?>
                                                <img src="/<?php echo sanitizeOutput($userItem['profile_picture']); ?>" 
                                                     alt="Profile" 
                                                     class="rounded-circle" 
                                                     width="32" height="32">
                                            <?php else: ?>
                                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="bi bi-person text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo sanitizeOutput($userItem['username']); ?></strong>
                                            <?php if ($userItem['is_admin']): ?>
                                                <span class="badge bg-danger ms-1">Admin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo sanitizeOutput($userItem['email']); ?></td>
                                        <td><?php echo sanitizeOutput($userItem['display_name']); ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo sanitizeOutput($userItem['role_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo strtoupper(sanitizeOutput($userItem['language'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($userItem['is_active']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> <?php echo __('active') ?: 'Active'; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> <?php echo __('inactive') ?: 'Inactive'; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($userItem['last_login_at']): ?>
                                                <small class="text-muted">
                                                    <?php echo formatDate($userItem['last_login_at'], 'M j, Y H:i'); ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted"><?php echo __('never') ?: 'Never'; ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                                                                            <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Edit Button -->
                                                    <?php if ($userItem['user_id'] === getCurrentUser()['user_id'] || 
                                                             hasPermission('reset_user_password') || 
                                                             getCurrentUser()['is_admin']): ?>
                                                        <a href="/crm-project/public/?module=users&action=edit&id=<?php echo $userItem['user_id']; ?>" 
                                                           class="btn btn-outline-primary" 
                                                           title="<?php echo __('edit') ?: 'Edit'; ?>">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Admin Actions -->
                                                    <?php if ((hasPermission('reset_user_password') || getCurrentUser()['is_admin']) && 
                                                             $userItem['user_id'] !== getCurrentUser()['user_id']): ?>
                                                        
                                                        <!-- Reset Password Button -->
                                                        <button type="button" 
                                                                class="btn btn-outline-warning" 
                                                                title="<?php echo __('reset_password') ?: 'Reset Password'; ?>"
                                                                onclick="resetPassword(<?php echo $userItem['user_id']; ?>, '<?php echo sanitizeOutput($userItem['username']); ?>')">
                                                            <i class="bi bi-key"></i>
                                                        </button>
                                                        
                                                        <!-- Deactivate Button -->
                                                        <?php if ($userItem['is_active']): ?>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger" 
                                                                    title="<?php echo __('deactivate') ?: 'Deactivate'; ?>"
                                                                    onclick="deactivateUser(<?php echo $userItem['user_id']; ?>, '<?php echo sanitizeOutput($userItem['username']); ?>')">
                                                                <i class="bi bi-person-x"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="Users pagination" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <!-- Previous -->
                                    <?php if ($pagination['page'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="/crm-project/public/?module=users&action=list&page=<?php echo $pagination['page'] - 1; ?>&limit=<?php echo $pagination['limit']; ?>&search=<?php echo urlencode($search); ?>">
                                                <i class="bi bi-chevron-left"></i> <?php echo __('previous') ?: 'Previous'; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <!-- Page Numbers -->
                                    <?php
                                    $startPage = max(1, $pagination['page'] - 2);
                                    $endPage = min($totalPages, $pagination['page'] + 2);
                                    
                                    for ($i = $startPage; $i <= $endPage; $i++):
                                    ?>
                                        <li class="page-item <?php echo $i === $pagination['page'] ? 'active' : ''; ?>">
                                            <a class="page-link" href="/crm-project/public/?module=users&action=list&page=<?php echo $i; ?>&limit=<?php echo $pagination['limit']; ?>&search=<?php echo urlencode($search); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <!-- Next -->
                                    <?php if ($pagination['page'] < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="/crm-project/public/?module=users&action=list&page=<?php echo $pagination['page'] + 1; ?>&limit=<?php echo $pagination['limit']; ?>&search=<?php echo urlencode($search); ?>">
                                                <?php echo __('next') ?: 'Next'; ?> <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people display-1 text-muted"></i>
                            <h4 class="mt-3"><?php echo __('no_users_found') ?: 'No users found'; ?></h4>
                            <p class="text-muted">
                                <?php if (!empty($search)): ?>
                                    <?php echo __('no_users_match_search') ?: 'No users match your search criteria.'; ?>
                                <?php else: ?>
                                    <?php echo __('no_users_available') ?: 'No users are currently available.'; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Hidden Forms for Actions -->
        <form id="actionForm" method="POST" style="display: none;">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="action" id="actionType">
            <input type="hidden" name="user_id" id="actionUserId">
        </form>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        function resetPassword(userId, username) {
            if (confirm('<?php echo __('confirm_reset_password') ?: 'Are you sure you want to reset the password for'; ?> ' + username + '?')) {
                document.getElementById('actionType').value = 'reset_password';
                document.getElementById('actionUserId').value = userId;
                document.getElementById('actionForm').submit();
            }
        }
        
        function deactivateUser(userId, username) {
            if (confirm('<?php echo __('confirm_deactivate_user') ?: 'Are you sure you want to deactivate'; ?> ' + username + '?')) {
                document.getElementById('actionType').value = 'deactivate';
                document.getElementById('actionUserId').value = userId;
                document.getElementById('actionForm').submit();
            }
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