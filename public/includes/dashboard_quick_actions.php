<?php
/**
 * Dashboard Quick Actions Component
 * Displays shortcuts to common tasks with permission checks
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../core/url_helper.php';
}

requireLogin();
$user = getCurrentUser();

// Define quick actions with permission requirements
$quickActions = [
    [
        'url' => url('clients', 'add'),
        'icon' => 'bi-person-plus',
        'label' => __('add_client') ?: 'Add Client',
        'permission' => 'add_client',
        'show' => hasPermission('add_client')
    ],
    [
        'url' => url('quotes', 'create'),
        'icon' => 'bi-file-plus',
        'label' => __('create_quote') ?: 'Create Quote',
        'permission' => 'create_quotes',
        'show' => hasPermission('create_quotes')
    ],
    [
        'url' => url('products', 'add'),
        'icon' => 'bi-box-seam',
        'label' => __('add_product') ?: 'Add Product',
        'permission' => 'add_products',
        'show' => hasPermission('add_products')
    ],
    [
        'url' => url('reports', 'sales'),
        'icon' => 'bi-bar-chart',
        'label' => __('view_reports') ?: 'View Reports',
        'permission' => 'view_sales_reports',
        'show' => hasPermission('view_sales_reports')
    ],
    [
        'url' => url('users', 'edit', ['id' => $user['user_id']]),
        'icon' => 'bi-person-gear',
        'label' => __('my_profile') ?: 'My Profile',
        'permission' => null,
        'show' => true
    ],
    [
        'url' => url('settings', 'edit'),
        'icon' => 'bi-gear',
        'label' => __('settings') ?: 'Settings',
        'permission' => 'manage_settings',
        'show' => hasPermission('manage_settings')
    ]
];

// Filter actions based on permissions
$visibleActions = array_filter($quickActions, function($action) {
    return $action['show'];
});
?>

<div class="quick-actions">
    <h5 class="quick-actions-title">
        <i class="bi bi-lightning"></i>
        <?php echo __('quick_actions') ?: 'Quick Actions'; ?>
    </h5>
    <div class="quick-actions-grid">
        <?php foreach ($visibleActions as $action): ?>
            <a href="<?php echo $action['url']; ?>" class="quick-action-btn">
                <i class="<?php echo $action['icon']; ?>"></i>
                <span><?php echo $action['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>