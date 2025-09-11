<?php
/**
 * Universal Lateral Navigation Component
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
        'permission' => null,
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
    if (isset($item['admin_only']) && $item['admin_only'] && !$currentUser['is_admin']) {
        return false;
    }
    
    if (isset($item['admin_override']) && $item['admin_override']) {
        return $currentUser['is_admin'] || hasPermission($item['permission']);
    }
    
    if (isset($item['check_module']) && $item['check_module']) {
        return canAccessModule($item['module']);
    }
    
    if (isset($item['permission']) && $item['permission']) {
        return hasPermission($item['permission']);
    }
    
    return true;
}
?>

<style>
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 280px;
    background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
    box-shadow: 2px 0 15px rgba(0,0,0,0.1);
    z-index: 1000;
    overflow-y: auto;
    transition: transform 0.3s ease;
}

.sidebar.collapsed {
    transform: translateX(-100%);
}

.sidebar-brand {
    padding: 1.5rem 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    font-size: 1.25rem;
    font-weight: bold;
}

.sidebar-brand:hover {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
}

.sidebar-nav {
    padding: 1rem 0;
}

.nav-item {
    margin: 0.25rem 1rem;
}

.nav-link {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.nav-link:hover {
    color: white;
    background: rgba(59, 130, 246, 0.2);
    text-decoration: none;
    transform: translateX(5px);
}

.nav-link.active {
    background: rgba(59, 130, 246, 0.3);
    color: white;
    font-weight: 500;
    border-left: 3px solid #3b82f6;
}

.nav-link i {
    width: 24px;
    margin-right: 12px;
    text-align: center;
}

.nav-section {
    margin: 1.5rem 0 0.5rem;
    padding: 0 1rem;
    color: rgba(255,255,255,0.6);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.user-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(15, 23, 42, 0.8);
    padding: 1rem;
    border-top: 1px solid rgba(59, 130, 246, 0.2);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid rgba(59, 130, 246, 0.5);
    object-fit: cover;
}

.user-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.main-content {
    margin-left: 280px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
    padding: 2rem;
}

.main-content.expanded {
    margin-left: 0;
}

.sidebar-toggle {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    background: #1e3a8a;
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: none;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.sidebar-toggle:hover {
    background: #1e40af;
    transform: scale(1.05);
}

.sidebar-toggle.active {
    left: 300px;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
        padding: 1rem;
        padding-top: 80px;
    }
    
    .sidebar-toggle {
        display: flex;
    }
    
    .sidebar-toggle.active {
        left: 20px;
        background: #dc3545;
    }
}

.dropdown-menu {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.submenu {
    margin-left: 1rem;
    border-left: 2px solid rgba(59, 130, 246, 0.3);
    padding-left: 0.5rem;
}

.submenu .nav-link {
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
}
</style>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="<?php echo url('dashboard', 'index'); ?>" class="sidebar-brand">
        <i class="bi bi-diamond-fill me-2"></i>
        <?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?>
    </a>
    
    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Main Navigation -->
        <?php foreach ($navItems as $item): ?>
            <?php if (checkNavAccess($item, $currentUser)): ?>
                <div class="nav-item">
                    <a href="<?php echo url($item['module'], $item['action']); ?>" 
                       class="nav-link <?php echo $item['active'] ? 'active' : ''; ?>">
                        <i class="<?php echo $item['icon']; ?>"></i>
                        <?php echo $item['label']; ?>
                    </a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Reports Section -->
        <?php if ($hasReports): ?>
            <div class="nav-section"><?php echo __('reports') ?: 'Reports'; ?></div>
            <?php foreach ($reportItems as $report): ?>
                <?php if (hasPermission($report['permission'])): ?>
                    <div class="nav-item">
                        <a href="<?php echo url($report['module'], $report['action']); ?>" 
                           class="nav-link <?php echo $currentModule === 'reports' && $currentAction === $report['action'] ? 'active' : ''; ?>">
                            <i class="<?php echo $report['icon']; ?>"></i>
                            <?php echo $report['label']; ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Admin Section -->
        <?php if ($hasAdmin): ?>
            <div class="nav-section"><?php echo __('administration') ?: 'Administration'; ?></div>
            <?php foreach ($adminItems as $admin): ?>
                <?php if (hasPermission($admin['permission'])): ?>
                    <div class="nav-item">
                        <a href="<?php echo url($admin['module'], $admin['action']); ?>" 
                           class="nav-link <?php echo $currentModule === $admin['module'] ? 'active' : ''; ?>">
                            <i class="<?php echo $admin['icon']; ?>"></i>
                            <?php echo $admin['label']; ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>

    <!-- User Info -->
    <div class="user-info">
        <div class="d-flex align-items-center mb-2">
            <?php if (!empty($currentUser['profile_picture'])): ?>
                <img src="/<?php echo sanitizeOutput($currentUser['profile_picture']); ?>" 
                     alt="Profile" class="user-avatar me-3">
            <?php else: ?>
                <div class="user-avatar-placeholder me-3">
                    <i class="bi bi-person"></i>
                </div>
            <?php endif; ?>
            <div class="flex-grow-1">
                <div class="text-white fw-bold small">
                    <?php echo sanitizeOutput($currentUser['display_name']); ?>
                </div>
                <div class="text-white-50 small">
                    <?php echo sanitizeOutput($currentUser['role_name'] ?? getUserRole()); ?>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo userEditUrl($currentUser['user_id']); ?>" 
               class="btn btn-outline-light btn-sm flex-fill">
                <i class="bi bi-person-gear"></i> <?php echo __('profile') ?: 'Profile'; ?>
            </a>
            <a href="<?php echo logoutUrl(); ?>" 
               class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Sidebar Toggle Button -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="bi bi-list"></i>
</button>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');
    
    toggle.addEventListener('click', function() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            sidebar.classList.toggle('show');
            toggle.classList.toggle('active');
            
            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
                toggle.innerHTML = '<i class="bi bi-x"></i>';
            } else {
                overlay.style.display = 'none';
                toggle.innerHTML = '<i class="bi bi-list"></i>';
            }
        } else {
            sidebar.classList.toggle('collapsed');
            if (mainContent) {
                mainContent.classList.toggle('expanded');
            }
            
            toggle.classList.toggle('active');
            if (sidebar.classList.contains('collapsed')) {
                toggle.innerHTML = '<i class="bi bi-arrow-right"></i>';
            } else {
                toggle.innerHTML = '<i class="bi bi-arrow-left"></i>';
            }
        }
    });
    
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        toggle.classList.remove('active');
        overlay.style.display = 'none';
        toggle.innerHTML = '<i class="bi bi-list"></i>';
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            overlay.style.display = 'none';
            sidebar.classList.remove('show');
            toggle.classList.remove('active');
            toggle.innerHTML = '<i class="bi bi-list"></i>';
        }
    });
});
</script>