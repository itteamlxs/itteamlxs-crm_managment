<?php
/**
 * User List Controller
 * Handles user listing with pagination and search
 */

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Require login and admin access or reset_user_password permission
requireLogin();
if (!hasPermission('reset_user_password') && !getCurrentUser()['is_admin']) {
    redirect('/crm-project/public/?module=dashboard&action=index');
}

$userModel = new UserModel();
$error = '';
$success = '';

// Get pagination and search parameters
$pagination = validatePagination($_GET['page'] ?? 1, $_GET['limit'] ?? 10, 50);
$search = sanitizeInput($_GET['search'] ?? '');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        $action = $_POST['action'] ?? '';
        $userId = (int)($_POST['user_id'] ?? 0);
        
        switch ($action) {
            case 'deactivate':
                if ($userId && $userId !== getCurrentUser()['user_id']) {
                    if ($userModel->deactivateUser($userId)) {
                        $success = __('user_deactivated_successfully');
                        logSecurityEvent('USER_DEACTIVATED', ['target_user_id' => $userId]);
                    } else {
                        $error = __('error_deactivating_user');
                    }
                } else {
                    $error = __('cannot_deactivate_own_account');
                }
                break;
                
            case 'reset_password':
                if ($userId) {
                    $newPassword = generateRandomPassword(12);
                    $passwordHash = hashPassword($newPassword);
                    
                    if ($userModel->updatePassword($userId, $passwordHash, true)) {
                        $success = __('password_reset_successfully') . ': ' . $newPassword;
                        logSecurityEvent('PASSWORD_RESET', ['target_user_id' => $userId]);
                        
                        // TODO: Send email notification in production
                    } else {
                        $error = __('error_resetting_password');
                    }
                }
                break;
        }
    }
}

// Get users data
$users = $userModel->getAllUsers($pagination['limit'], $pagination['offset'], $search);
$totalUsers = $userModel->getUsersCount($search);
$totalPages = ceil($totalUsers / $pagination['limit']);

// Include view
include __DIR__ . '/../views/list.php';