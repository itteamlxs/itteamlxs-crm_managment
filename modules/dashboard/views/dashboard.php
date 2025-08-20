<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';

requireLogin();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('dashboard') ?: 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3><?php echo __('dashboard') ?: 'Dashboard'; ?></h3>
            </div>
            <div class="card-body">
                <h5><?php echo __('welcome') ?: 'Welcome'; ?>, <?php echo sanitizeOutput($user['display_name']); ?>!</h5>
                <p><strong><?php echo __('role') ?: 'Role'; ?>:</strong> <?php echo sanitizeOutput(getUserRole()); ?></p>
                <p><strong><?php echo __('login_date') ?: 'Login Date'; ?>:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                <a href="/crm-project/public/index.php?module=auth&action=logout" class="btn btn-danger"><?php echo __('logout') ?: 'Logout'; ?></a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>