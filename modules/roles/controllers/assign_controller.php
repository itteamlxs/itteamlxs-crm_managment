<?php
/**
 * Assign Permissions Controller
 * Handles role permission assignments
 */

require_once __DIR__ . '/../models/RoleModel.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Require login and admin access
requireLogin();
requireAdmin();

$roleModel = new RoleModel();
$error = '';
$success = '';
$roleId = (int)($_GET['id'] ?? 0);

// Get role data
$role = null;
if ($roleId > 0) {
    $role = $roleModel->getRoleById($roleId);
    if (!$role) {
        redirect(url('roles', 'list'));
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $permissionIds = $_POST['permissions'] ?? [];
        
        // Validate permission IDs
        $validPermissionIds = [];
        foreach ($permissionIds as $permissionId) {
            $permissionId = (int)$permissionId;
            if ($permissionId > 0) {
                $validPermissionIds[] = $permissionId;
            }
        }
        
        if ($roleModel->assignPermissions($roleId, $validPermissionIds)) {
            $success = 'Permissions assigned successfully';
            logSecurityEvent('PERMISSIONS_ASSIGNED', [
                'role_id' => $roleId,
                'permissions_count' => count($validPermissionIds)
            ]);
        } else {
            $error = 'Error assigning permissions';
        }
    }
}

// Get all permissions grouped by module
$allPermissions = $roleModel->getAllPermissions();
$permissionsByModule = [];
foreach ($allPermissions as $permission) {
    $permissionsByModule[$permission['module']][] = $permission;
}

// Get current role permissions
$rolePermissions = [];
if ($roleId > 0) {
    $currentPermissions = $roleModel->getRolePermissions($roleId);
    foreach ($currentPermissions as $permission) {
        $rolePermissions[] = $permission['permission_id'];
    }
}

// Include view
include __DIR__ . '/../views/assign.php';