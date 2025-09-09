<?php
/**
 * Universal Navigation Component
 * Include this in all views for consistent navigation
 */

// Ensure required dependencies are loaded
if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../core/url_helper.php';
}

$currentUser = getCurrentUser();
if (!$currentUser) return;

// Get current module for active states
$currentModule = $_GET['module'] ?? 'dashboard';
$currentAction = $_GET['action'] ?? 'index';

// Navigation items with permissions
$navItems = [
    [
        'module' => 'dashboard',
        'action' => 'index',
        'icon' => 'bi-speedometer2',
        'label' => __('dashboard'),
        'permission' => null, // Always visible
        'active' => $currentModule === 'dashboard'
    ],
    [
        'module' => 'users',
        'action' => 'list',
        'icon' => 'bi-people',
        'label' => __('users'),
        'permission' => 'reset_user_password',
        'admin_override' => true,
        'active' => $currentModule === 'users'
    ],
    [
        'module' => 'roles',
        'action' => 'list',
        'icon' => 'bi-shield-check',
        'label' => __('roles_management'),
        'permission' => null,
        'admin_only' => true,
        'active' => $currentModule === 'roles'
    ],
    [
        'module' => 'clients',
        'action' => 'list',
        'icon' => 'bi-building',
        'label' => __('clients'),
        'permission' => null,
        'check_module' => true,
        'active' => $currentModule === 'clients'
    ],
    [
        'module' => 'products',
        'action' => 'list',
        'icon' => 'bi-box',
        'label' => __('products'),
        'permission' => null,
        'check_module' => true,
        'active' => $currentModule === 'products'
    ],
    [
        'module' => 'quotes',
        'action' => 'list',
        'icon' => 'bi-file-text',
        'label' => __('quotes'),
        'permission' => null,
        'check_module' => true,
        'active' => $currentModule === 'quotes'
    ]
];

// Reports submenu
$reportItems = [
    [
        'module' => 'reports',
        'action' => 'sales',
        'icon' => 'bi-graph-up',
        'label' => __('sales_reports'),
        'permission' => 'view_sales_reports'
    ],
    [
        'module' => 'reports',
        'action' => 'clients',
        'icon' => 'bi-people',
        'label' => __('client_reports'),
        'permission' => 'view_client_reports'
    ],
    [
        'module' => 'reports',
        'action' => 'products',
        'icon' => 'bi-box',
        'label' => __('product_reports'),
        'permission' => 'view_product_reports'
    ],
    [
        'module' => 'reports',
        'action' => 'compliance',
        'icon' => 'bi-shield-check',
        'label' => __('compliance_reports'),
        'permission' => 'view_compliance_reports'
    ]
];

// Check if user has access to any reports
$hasReports = false;
foreach ($reportItems as $report) {
    if (hasPermission($report['permission'])) {
        $hasReports = true;
        break;
    }
}

// Admin items
$adminItems = [
    [
        'module' => 'settings',
        'action' => 'edit',
        'icon' => 'bi-gear',
        'label' => __('settings'),
        'permission' => 'manage_settings'
    ],
    [
        'module' => 'backups',
        'action' => 'list',
        'icon' => 'bi-archive',
        'label' => __('backups'),
        'permission' => 'manage_backups'
    ],
    [
        'module' => 'access_requests',
        'action' => 'list',
        'icon' => 'bi-key',
        'label' => __('access_requests'),
        'permission' => 'manage_access_requests'
    ]
];

$hasAdmin = false;
foreach ($adminItems as $admin) {
    if (hasPermission($admin['permission'])) {
        $hasAdmin = true;
        break;
    }
}

function checkNavAccess($item, $currentUser) {
    // Admin only items
    if (isset($item['admin_only']) && $item['admin_only'] && !$currentUser['is_admin']) {
        return false;
    }
    
    // Admin override (admin or specific permission)
    if (isset($item['admin_override']) && $item['admin_override']) {
        return $currentUser['is_admin'] || hasPermission($item['permission']);
    }
    
    // Check module access
    if (isset($item['check_module']) && $item['check_module']) {
        return canAccessModule($item['module']);
    }
    
    // Check specific permission
    if (isset($item['permission']) && $item['permission']) {
        return hasPermission($item['permission']);
    }
    
    return true;
}
?>

<style>
.navbar-brand img {
    height: 32px;
}
.nav-link.active {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    color: var(--bs-primary) !important;
    border-radius: 0.375rem;
}
.dropdown-item.active {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    color: var(--bs-primary);
}
.user-avatar {
    width: 32px;
    height: 32px;
    object-fit: cover;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="<?php echo url('dashboard', 'index'); ?>">
            <i class="bi bi-diamond-fill me-2"></i>
            <strong><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?></strong>
        </a>

        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php foreach ($navItems as $item): ?>
                    <?php if (checkNavAccess($item, $currentUser)): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $item['active'] ? 'active' : ''; ?>" 
                               href="<?php echo url($item['module'], $item['action']); ?>">
                                <i class="<?php echo $item['icon']; ?> me-1"></i>
                                <?php echo $item['label']; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Reports Dropdown 2 -->
                <?php if ($hasReports): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo $currentModule === 'reports' ? 'active' : ''; ?>" 
                           href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bar-chart me-1"></i>
                            <?php echo __('reports') ?: 'Reports'; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($reportItems as $report): ?>
                                <?php if (hasPermission($report['permission'])): ?>
                                    <li>
                                        <a class="dropdown-item <?php echo $currentModule === 'reports' && $currentAction === $report['action'] ? 'active' : ''; ?>" 
                                           href="<?php echo url($report['module'], $report['action']); ?>">
                                            <i class="<?php echo $report['icon']; ?> me-2"></i>
                                            <?php echo $report['label']; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Admin Dropdown -->
                <?php if ($hasAdmin): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-tools me-1"></i>
                            <?php echo __('administration') ?: 'Admin'; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($adminItems as $admin): ?>
                                <?php if (hasPermission($admin['permission'])): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo url($admin['module'], $admin['action']); ?>">
                                            <i class="<?php echo $admin['icon']; ?> me-2"></i>
                                            <?php echo $admin['label']; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- User Menu -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <?php if (!empty($currentUser['profile_picture'])): ?>
                            <img src="/<?php echo sanitizeOutput($currentUser['profile_picture']); ?>" 
                                 alt="Profile" class="rounded-circle user-avatar me-2">
                        <?php else: ?>
                            <div class="bg-light rounded-circle user-avatar me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                        <?php endif; ?>
                        <span class="d-none d-md-inline"><?php echo sanitizeOutput($currentUser['display_name']); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-header">
                                <strong><?php echo sanitizeOutput($currentUser['display_name']); ?></strong><br>
                                <small class="text-muted"><?php echo sanitizeOutput($currentUser['role_name'] ?? getUserRole()); ?></small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?php echo userEditUrl($currentUser['user_id']); ?>">
                                <i class="bi bi-person-gear me-2"></i>
                                <?php echo __('my_profile') ?: 'My Profile'; ?>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?php echo logoutUrl(); ?>">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                <?php echo __('logout') ?: 'Logout'; ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>