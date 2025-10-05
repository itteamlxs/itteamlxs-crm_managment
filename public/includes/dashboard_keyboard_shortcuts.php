<?php
/**
 * Dashboard Keyboard Shortcuts Component (UI Only)
 * Displays keyboard shortcuts list - Logic is in keyboard_shortcuts_global.php
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
}

requireLogin();

// Define shortcuts for display only
$shortcuts = [
    [
        'label' => __('new_client') ?: 'New Client',
        'key' => 'Ctrl Alt C',
        'show' => hasPermission('add_client')
    ],
    [
        'label' => __('new_quote') ?: 'New Quote',
        'key' => 'Ctrl Alt Q',
        'show' => hasPermission('create_quotes')
    ],
    [
        'label' => __('new_product') ?: 'New Product',
        'key' => 'Ctrl Alt N',
        'show' => hasPermission('add_products')
    ],
    [
        'label' => __('dashboard') ?: 'Dashboard',
        'key' => 'Ctrl Alt D',
        'show' => true
    ],
    [
        'label' => __('profile') ?: 'Profile',
        'key' => 'Ctrl Alt P',
        'show' => true
    ],
    [
        'label' => __('All Shortcuts') ?: 'shortcuts',
        'key' => 'Ctrl  /',
        'show' => true
    ]
];

$visibleShortcuts = array_filter($shortcuts, function($s) { return $s['show']; });
?>

<div class="sidebar-card mb-3">
    <h6 class="sidebar-card-title">
        <i class="bi bi-keyboard"></i>
        <?php echo __('keyboard_shortcuts') ?: 'Keyboard Shortcuts'; ?>
    </h6>
    <div class="shortcuts-list">
        <?php foreach ($visibleShortcuts as $shortcut): ?>
            <div class="shortcut-item d-flex justify-content-between align-items-center mb-2">
                <span class="shortcut-label"><?php echo $shortcut['label']; ?></span>
                <kbd class="shortcut-key"><?php echo $shortcut['key']; ?></kbd>
            </div>
        <?php endforeach; ?>
    </div>
</div>