<?php
/**
 * Main Entry Point
 * Dynamic module loading and routing
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../core/security.php';
require_once __DIR__ . '/../core/rbac.php';

// Check session timeout
if (isLoggedIn() && !checkSessionTimeout()) {
    redirect('/?module=auth&action=login');
}

// Get module and action from URL parameters
$module = sanitizeInput($_GET['module'] ?? '');
$action = sanitizeInput($_GET['action'] ?? '');

// Whitelist allowed modules and actions for security
$allowedModules = [
    'auth' => ['login', 'logout', 'reset'],
    'users' => ['list', 'edit', 'profile'],
    'roles' => ['list', 'assign', 'delete'],
    'clients' => ['list', 'add', 'edit', 'delete'],
    'products' => ['list', 'categories', 'add', 'edit'],
    'quotes' => ['list', 'create', 'view', 'edit', 'approve', 'renew', 'send', 'pdf', 'duplicate'],
    'reports' => ['sales', 'clients', 'products', 'compliance'],
    'settings' => ['edit'],
    'access_requests' => ['list', 'review'],
    'backups' => ['list', 'request'],
    'dashboard' => ['index']
];

// Default routing
if (empty($module) || empty($action)) {
    if (isLoggedIn()) {
        $module = 'dashboard';
        $action = 'index';
    } else {
        $module = 'auth';
        $action = 'login';
    }
}

// Validate module and action
if (!isset($allowedModules[$module]) || !in_array($action, $allowedModules[$module])) {
    http_response_code(404);
    die('Page not found');
}

// Authentication check (except for auth module)
if ($module !== 'auth' && !isLoggedIn()) {
    redirect('/?module=auth&action=login');
}

// Module access check (except for auth and dashboard)
if (!in_array($module, ['auth', 'dashboard']) && !canAccessModule($module)) {
    if (isAjaxRequest()) {
        jsonResponse(['error' => 'Access denied to module'], 403);
    } else {
        redirect('/?module=dashboard&action=index');
    }
}

// Load module controller
$controllerPath = __DIR__ . "/../modules/{$module}/controllers/";

try {
    switch ($module) {
        case 'auth':
            switch ($action) {
                case 'login':
                    require_once $controllerPath . 'login_controller.php';
                    break;
                case 'logout':
                    require_once $controllerPath . 'logout_controller.php';
                    break;
                case 'reset':
                    require_once $controllerPath . 'auth_controller.php';
                    break;
            }
            break;
            
        case 'users':
            switch ($action) {
                case 'list':
                    require_once $controllerPath . 'list_controller.php';
                    break;
                case 'edit':
                case 'profile':
                    require_once $controllerPath . 'edit_controller.php';
                    break;
            }
            break;
            
        case 'roles':
            switch ($action) {
                case 'list':
                    require_once $controllerPath . 'roles_controller.php';
                    break;
                case 'assign':
                    require_once $controllerPath . 'assign_controller.php';
                    break;
                case 'delete':
                    require_once $controllerPath . 'roles_controller.php';
                    break;
            }
            break;
            
        case 'clients':
            switch ($action) {
                case 'list':
                    require_once $controllerPath . 'list_controller.php';
                    break;
                case 'add':
                    require_once $controllerPath . 'add_controller.php';
                    break;
                case 'edit':
                    require_once $controllerPath . 'edit_controller.php';
                    break;
                case 'delete':
                    require_once $controllerPath . 'delete_controller.php';
                    break;
            }
            break;
            
        case 'products':
            switch ($action) {
                case 'list':
                    require_once $controllerPath . 'products_controller.php';
                    break;
                case 'categories':
                    require_once $controllerPath . 'categories_controller.php';
                    break;
                case 'add':
                    require_once $controllerPath . 'add_product_controller.php';
                    break;
                case 'edit':
                    require_once $controllerPath . 'edit_product_controller.php';
                    break;
            }
            break;
            
        case 'quotes':
            switch ($action) {
                case 'list':
                    require_once $controllerPath . 'list_controller.php';
                    break;
                case 'create':
                    require_once $controllerPath . 'create_controller.php';
                    break;
                case 'view':
                    require_once $controllerPath . 'view_controller.php';
                    break;
                case 'edit':
                    require_once $controllerPath . 'edit_controller.php';
                    break;
                case 'approve':
                    require_once $controllerPath . 'approve_controller.php';
                    break;
                case 'renew':
                    require_once $controllerPath . 'renew_controller.php';
                    break;
                case 'send':
                    require_once $controllerPath . 'send_controller.php';
                    break;
                case 'pdf':
                    require_once $controllerPath . 'pdf_controller.php';
                    break;
                case 'duplicate':
                    require_once $controllerPath . 'duplicate_controller.php';
                    break;
            }
            break;
            
        case 'reports':
            switch ($action) {
                case 'sales':
                    require_once $controllerPath . 'sales_reports_controller.php';
                    break;
                case 'clients':
                    require_once $controllerPath . 'client_reports_controller.php';
                    break;
                case 'products':
                    require_once $controllerPath . 'product_reports_controller.php';
                    break;
                case 'compliance':
                    require_once $controllerPath . 'compliance_reports_controller.php';
                    break;
            }
            break;
            
        case 'settings':
            require_once $controllerPath . 'edit_controller.php';
            break;
            
        case 'access_requests':
            switch ($action) {
                case 'list':
                    require_once $controllerPath . 'requests_controller.php';
                    break;
                case 'review':
                    require_once $controllerPath . 'review_controller.php';
                    break;
            }
            break;
            
        case 'backups':
            switch ($action) {
                case 'list':
                    require_once $controllerPath . 'list_controller.php';
                    break;
                case 'request':
                    require_once $controllerPath . 'request_controller.php';
                    break;
            }
            break;
            
        case 'dashboard':
            require_once __DIR__ . '/../modules/dashboard/controllers/index_controller.php';
            break;
            
        default:
            http_response_code(404);
            die('Module not found');
    }
    
} catch (Exception $e) {
    if (APP_DEBUG) {
        die("Error loading module: " . $e->getMessage());
    } else {
        logError("Module loading error: " . $e->getMessage());
        http_response_code(500);
        die('Internal server error');
    }
}