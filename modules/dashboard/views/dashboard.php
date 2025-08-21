<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><?php echo __('dashboard') ?: 'Dashboard'; ?></h3>
                <a href="<?php echo logoutUrl(); ?>" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> <?php echo __('logout') ?: 'Logout'; ?>
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <h5><?php echo __('welcome') ?: 'Welcome'; ?>, <?php echo sanitizeOutput($user['display_name']); ?>!</h5>
                        <p class="mb-1"><strong><?php echo __('role') ?: 'Role'; ?>:</strong> <?php echo sanitizeOutput(getUserRole()); ?></p>
                        <p><strong><?php echo __('login_date') ?: 'Login Date'; ?>:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3"><?php echo __('navigation') ?: 'Navigation'; ?></h6>
                    </div>
                </div>

                <div class="row g-3">
                    <?php if (hasPermission('reset_user_password') || $user['is_admin']): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2"><?php echo __('users_management') ?: 'Users Management'; ?></h6>
                                <p class="card-text small"><?php echo __('manage_users_description') ?: 'Manage users, roles and permissions'; ?></p>
                                <a href="<?php echo usersListUrl(); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-arrow-right"></i> <?php echo __('access') ?: 'Access'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <i class="bi bi-person-circle text-info" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2"><?php echo __('my_profile') ?: 'My Profile'; ?></h6>
                                <p class="card-text small"><?php echo __('edit_profile_description') ?: 'Edit your personal information'; ?></p>
                                <a href="<?php echo userEditUrl($user['user_id']); ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-arrow-right"></i> <?php echo __('access') ?: 'Access'; ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if (canAccessModule('clients')): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <i class="bi bi-building text-success" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2"><?php echo __('clients') ?: 'Clients'; ?></h6>
                                <p class="card-text small"><?php echo __('manage_clients_description') ?: 'Manage client information'; ?></p>
                                <a href="<?php echo url('clients', 'list'); ?>" class="btn btn-success btn-sm">
                                    <i class="bi bi-arrow-right"></i> <?php echo __('access') ?: 'Access'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (canAccessModule('quotes')): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-warning">
                            <div class="card-body text-center">
                                <i class="bi bi-file-text text-warning" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2"><?php echo __('quotes') ?: 'Quotes'; ?></h6>
                                <p class="card-text small"><?php echo __('manage_quotes_description') ?: 'Create and manage quotes'; ?></p>
                                <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-right"></i> <?php echo __('access') ?: 'Access'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (canAccessModule('reports')): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-secondary">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up text-secondary" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2"><?php echo __('reports') ?: 'Reports'; ?></h6>
                                <p class="card-text small"><?php echo __('view_reports_description') ?: 'View sales and compliance reports'; ?></p>
                                <a href="<?php echo url('reports', 'sales'); ?>" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-arrow-right"></i> <?php echo __('access') ?: 'Access'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (hasPermission('manage_settings')): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-dark">
                            <div class="card-body text-center">
                                <i class="bi bi-gear text-dark" style="font-size: 2rem;"></i>
                                <h6 class="card-title mt-2"><?php echo __('settings') ?: 'Settings'; ?></h6>
                                <p class="card-text small"><?php echo __('manage_settings_description') ?: 'Manage system settings'; ?></p>
                                <a href="<?php echo url('settings', 'edit'); ?>" class="btn btn-dark btn-sm">
                                    <i class="bi bi-arrow-right"></i> <?php echo __('access') ?: 'Access'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>