<?php
/**
 * Roles Controller
 * Handles role management operations
 */

require_once __DIR__ . '/../models/RoleModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Require login and admin access
requireLogin();
requireAdmin();

$roleModel = new RoleModel();
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'create':
                $roleData = [
                    'role_name' => sanitizeInput($_POST['role_name'] ?? ''),
                    'description' => sanitizeInput($_POST['description'] ?? '')
                ];
                
                // Validation
                if (empty($roleData['role_name'])) {
                    $error = 'Role name is required';
                } elseif ($roleModel->roleNameExists($roleData['role_name'])) {
                    $error = 'Role name already exists';
                } else {
                    $roleId = $roleModel->createRole($roleData);
                    if ($roleId) {
                        $success = 'Role created successfully';
                        logSecurityEvent('ROLE_CREATED', ['role_id' => $roleId]);
                    } else {
                        $error = 'Error creating role';
                    }
                }
                break;
                
            case 'update':
                $roleId = (int)($_POST['role_id'] ?? 0);
                $roleData = [
                    'role_name' => sanitizeInput($_POST['role_name'] ?? ''),
                    'description' => sanitizeInput($_POST['description'] ?? '')
                ];
                
                // Validation
                if (empty($roleData['role_name'])) {
                    $error = 'Role name is required';
                } elseif ($roleModel->roleNameExists($roleData['role_name'], $roleId)) {
                    $error = 'Role name already exists';
                } else {
                    if ($roleModel->updateRole($roleId, $roleData)) {
                        $success = 'Role updated successfully';
                        logSecurityEvent('ROLE_UPDATED', ['role_id' => $roleId]);
                    } else {
                        $error = 'Error updating role';
                    }
                }
                break;
        }
    }
}

// Get all roles
$roles = $roleModel->getAllRoles();

// Include view
include __DIR__ . '/../views/roles.php';