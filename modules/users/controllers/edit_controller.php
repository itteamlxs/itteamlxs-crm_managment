<?php
/**
 * User Edit Controller
 * Handles user profile editing and creation
 */

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Require login
requireLogin();

$userModel = new UserModel();
$error = '';
$success = '';
$isEdit = false;
$targetUserId = (int)($_GET['id'] ?? 0);
$currentUser = getCurrentUser();

// Determine if editing or creating
if ($targetUserId > 0) {
    $isEdit = true;
    $user = $userModel->getUserById($targetUserId);
    
    if (!$user) {
        redirect('/crm-project/public/?module=users&action=list');
    }
    
    // Check permissions - can edit own profile or has admin/reset_user_password
    if ($targetUserId !== $currentUser['user_id'] && 
        !hasPermission('reset_user_password') && 
        !$currentUser['is_admin']) {
        redirect('/crm-project/public/?module=dashboard&action=index');
    }
} else {
    // Creating new user - requires admin or reset_user_password permission
    if (!hasPermission('reset_user_password') && !$currentUser['is_admin']) {
        redirect('/crm-project/public/?module=users&action=list');
    }
    
    $user = [
        'user_id' => 0,
        'username' => '',
        'email' => '',
        'display_name' => '',
        'language' => 'es',
        'role_id' => '',
        'is_admin' => 0,
        'is_active' => 1,
        'profile_picture' => ''
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = __('invalid_security_token');
    } else {
        // Collect and validate form data
        $formData = [
            'username' => sanitizeInput($_POST['username'] ?? ''),
            'email' => sanitizeInput($_POST['email'] ?? ''),
            'display_name' => sanitizeInput($_POST['display_name'] ?? ''),
            'language' => sanitizeInput($_POST['language'] ?? 'es')
        ];
        
        // Validation
        $errors = [];
        
        if (empty($formData['username'])) {
            $errors[] = __('username_required');
        } elseif (!validateUsername($formData['username'])) {
            $errors[] = __('invalid_username_format');
        } elseif ($userModel->usernameExists($formData['username'], $targetUserId ?: null)) {
            $errors[] = __('username_already_exists');
        }
        
        if (empty($formData['email'])) {
            $errors[] = __('email_required');
        } elseif (!validateEmail($formData['email'])) {
            $errors[] = __('invalid_email_format');
        } elseif ($userModel->emailExists($formData['email'], $targetUserId ?: null)) {
            $errors[] = __('email_already_exists');
        }
        
        if (empty($formData['display_name'])) {
            $errors[] = __('display_name_required');
        }
        
        // Admin-only fields
        if (hasPermission('reset_user_password') || $currentUser['is_admin']) {
            if (isset($_POST['role_id'])) {
                $formData['role_id'] = (int)$_POST['role_id'];
            }
            if (isset($_POST['is_admin'])) {
                $formData['is_admin'] = (int)$_POST['is_admin'];
            }
            if (isset($_POST['is_active'])) {
                $formData['is_active'] = (int)$_POST['is_active'];
            }
        }
        
        // Handle profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
            $uploadResult = validateFileUpload($_FILES['profile_picture'], ['jpg', 'jpeg', 'png']);
            
            if (!$uploadResult['valid']) {
                $errors[] = $uploadResult['error'];
            } else {
                $uploadPath = UPLOAD_DIR . $uploadResult['filename'];
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                    $formData['profile_picture'] = 'uploads/' . $uploadResult['filename'];
                } else {
                    $errors[] = __('file_upload_failed');
                }
            }
        }
        
        // Handle password for new users
        if (!$isEdit) {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($password)) {
                $errors[] = __('password_required');
            } else {
                $passwordValidation = validatePassword($password);
                if (!$passwordValidation['valid']) {
                    $errors = array_merge($errors, $passwordValidation['errors']);
                }
                
                if ($password !== $confirmPassword) {
                    $errors[] = __('passwords_do_not_match');
                }
                
                if (empty($errors)) {
                    $formData['password_hash'] = hashPassword($password);
                }
            }
        }
        
        // Process if no errors
        if (empty($errors)) {
            if ($isEdit) {
                if ($userModel->updateUser($targetUserId, $formData)) {
                    $success = __('user_updated_successfully');
                    
                    // Update session if editing own profile
                    if ($targetUserId === $currentUser['user_id']) {
                        $updatedUser = $userModel->getUserById($targetUserId);
                        $_SESSION['user'] = array_merge($_SESSION['user'], $updatedUser);
                    }
                    
                    logSecurityEvent('USER_UPDATED', ['target_user_id' => $targetUserId]);
                } else {
                    $error = __('error_updating_user');
                }
            } else {
                $newUserId = $userModel->createUser($formData);
                if ($newUserId) {
                    $success = __('user_created_successfully');
                    logSecurityEvent('USER_CREATED', ['new_user_id' => $newUserId]);
                    
                    // Redirect to edit the new user
                    redirect("/crm-project/public/?module=users&action=edit&id={$newUserId}");
                } else {
                    $error = __('error_creating_user');
                }
            }
        } else {
            $error = implode('<br>', $errors);
        }
        
        // Update form data for redisplay
        $user = array_merge($user, $formData);
    }
}

// Get roles for dropdown (admin only)
$roles = [];
if (hasPermission('reset_user_password') || $currentUser['is_admin']) {
    $roles = $userModel->getAllRoles();
}

// Get available languages
$availableLanguages = $userModel->getAvailableLanguages();

// Include view
include __DIR__ . '/../views/edit.php';