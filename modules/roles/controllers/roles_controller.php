<?php
/**
 * Roles Controller
 * Handle role listing, creation, and editing
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../models/RoleModel.php';

requireLogin();
requireAdmin();

$roleModel = new RoleModel();
$action = $_GET['sub_action'] ?? 'list';

switch ($action) {
    case 'add':
        handleAddRole();
        break;
    case 'edit':
        handleEditRole();
        break;
    case 'delete':
        handleDeleteRole();
        break;
    case 'list':
    default:
        handleListRoles();
        break;
}

function handleListRoles() {
    global $roleModel;
    
    $roles = $roleModel->getAllRoles();
    
    foreach ($roles as &$role) {
        $role['user_count'] = $roleModel->countUsersInRole($role['role_id']);
        $role['can_delete'] = $roleModel->canDeleteRole($role['role_id']);
    }
    
    unset($role);
    include __DIR__ . '/../views/roles.php';
}

function handleAddRole() {
    global $roleModel;
    
    $errors = [];
    $formData = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $errors[] = __('invalid_security_token');
        } else {
            $formData = [
                'role_name' => sanitizeInput($_POST['role_name'] ?? ''),
                'description' => sanitizeInput($_POST['description'] ?? '')
            ];
            
            // Validate role name
            if (empty($formData['role_name'])) {
                $errors[] = __('role_name_required');
            } elseif (!preg_match('/^[a-zA-Z0-9_\s]{3,50}$/', $formData['role_name'])) {
                $errors[] = __('invalid_role_name_format');
            } elseif ($roleModel->roleNameExists($formData['role_name'])) {
                $errors[] = __('role_name_already_exists');
            }
            
            if (empty($formData['description'])) {
                $errors[] = __('description_required');
            }
            
            if (empty($errors)) {
                if ($roleModel->createRole($formData)) {
                    $_SESSION['success_message'] = __('role_created_successfully');
                    redirect('/?module=roles&action=list');
                } else {
                    $errors[] = __('error_creating_role');
                }
            }
        }
    }
    
    $pageTitle = __('add_role');
    include __DIR__ . '/../views/add_edit_role.php';
}

function handleEditRole() {
    global $roleModel;
    
    $roleId = (int)($_GET['id'] ?? 0);
    if (!$roleId) {
        redirect('/?module=roles&action=list');
    }
    
    $role = $roleModel->getRoleById($roleId);
    if (!$role) {
        $_SESSION['error_message'] = __('role_not_found');
        redirect('/?module=roles&action=list');
    }
    
    $errors = [];
    $formData = $role;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $errors[] = __('invalid_security_token');
        } else {
            $formData = [
                'role_name' => sanitizeInput($_POST['role_name'] ?? ''),
                'description' => sanitizeInput($_POST['description'] ?? '')
            ];
            
            // Validate role name
            if (empty($formData['role_name'])) {
                $errors[] = __('role_name_required');
            } elseif (!preg_match('/^[a-zA-Z0-9_\s]{3,50}$/', $formData['role_name'])) {
                $errors[] = __('invalid_role_name_format');
            } elseif ($roleModel->roleNameExists($formData['role_name'], $roleId)) {
                $errors[] = __('role_name_already_exists');
            }
            
            if (empty($formData['description'])) {
                $errors[] = __('description_required');
            }
            
            if (empty($errors)) {
                if ($roleModel->updateRole($roleId, $formData)) {
                    $_SESSION['success_message'] = __('role_updated_successfully');
                    redirect('/?module=roles&action=list');
                } else {
                    $errors[] = __('error_updating_role');
                }
            }
        }
    }
    
    $pageTitle = __('edit_role');
    $isEdit = true;
    include __DIR__ . '/../views/add_edit_role.php';
}

function handleDeleteRole() {
    global $roleModel;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('/?module=roles&action=list');
    }
    
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['error_message'] = __('invalid_security_token');
        redirect('/?module=roles&action=list');
    }
    
    $roleId = (int)($_POST['role_id'] ?? 0);
    if (!$roleId) {
        $_SESSION['error_message'] = __('role_not_found');
        redirect('/?module=roles&action=list');
    }
    
    $role = $roleModel->getRoleById($roleId);
    if (!$role) {
        $_SESSION['error_message'] = __('role_not_found');
        redirect('/?module=roles&action=list');
    }
    
    if (!$roleModel->canDeleteRole($roleId)) {
        $_SESSION['error_message'] = __('cannot_delete_role_with_users');
        redirect('/?module=roles&action=list');
    }
    
    if ($roleModel->deleteRole($roleId)) {
        $_SESSION['success_message'] = __('role_deleted_successfully');
    } else {
        $_SESSION['error_message'] = __('error_deleting_role');
    }
    
    redirect('/?module=roles&action=list');
}