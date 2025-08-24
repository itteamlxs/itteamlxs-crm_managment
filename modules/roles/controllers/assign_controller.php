<?php
/**
 * Assign Permissions Controller
 * Handle permission assignment to roles
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../models/RoleModel.php';

requireLogin();
requireAdmin();

$roleModel = new RoleModel();

$roleId = (int)($_GET['role_id'] ?? 0);
if (!$roleId) {
    redirect('/?module=roles&action=list');
}

$role = $roleModel->getRoleById($roleId);
if (!$role) {
    $_SESSION['error_message'] = __('role_not_found');
    redirect('/?module=roles&action=list');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = __('invalid_security_token');
    } else {
        $permissionIds = [];
        
        if (isset($_POST['permissions']) && is_array($_POST['permissions'])) {
            foreach ($_POST['permissions'] as $permissionId) {
                $permissionId = (int)$permissionId;
                if ($permissionId > 0) {
                    $permissionIds[] = $permissionId;
                }
            }
        }
        
        if ($roleModel->updateRolePermissions($roleId, $permissionIds)) {
            $_SESSION['success_message'] = __('permissions_updated_successfully');
            $success = true;
        } else {
            $errors[] = __('error_updating_permissions');
        }
    }
}

// Get all permissions grouped by module
$permissionsByModule = $roleModel->getPermissionsByModule();

// Get current role permissions
$currentPermissionIds = $roleModel->getRolePermissionIds($roleId);

$pageTitle = __('assign_permissions') . ' - ' . sanitizeOutput($role['role_name']);

include __DIR__ . '/../views/assign.php';