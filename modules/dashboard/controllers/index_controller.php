<?php
/**
 * User Profile Controller
 * Handles profile editing and first-time password changes
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';

requireLogin();
$user = getCurrentUser();

// Handle AJAX password change request (for first login)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'change_password') {
        try {
            // Validate CSRF token
            if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
                jsonResponse(['error' => __('invalid_security_token')], 400);
            }
            
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate inputs
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                jsonResponse(['error' => __('all_fields_required')], 400);
            }
            
            if ($newPassword !== $confirmPassword) {
                jsonResponse(['error' => __('passwords_do_not_match')], 400);
            }
            
            // Validate current password
            if (!verifyPassword($currentPassword, $user['password_hash'])) {
                jsonResponse(['error' => __('current_password_incorrect')], 400);
            }
            
            // Validate new password strength
            $passwordValidation = validatePassword($newPassword);
            if (!$passwordValidation['valid']) {
                jsonResponse(['error' => implode('. ', $passwordValidation['errors'])], 400);
            }
            
            // Update password in database
            $db = Database::getInstance();
            $newPasswordHash = hashPassword($newPassword);
            
            $db->beginTransaction();
            
            try {
                // Update password and remove force_password_change flag
                $sql = "UPDATE users SET 
                        password_hash = ?, 
                        force_password_change = FALSE,
                        updated_at = NOW()
                        WHERE user_id = ?";
                
                $db->execute($sql, [$newPasswordHash, $user['user_id']]);
                
                // Log security event
                logSecurityEvent('PASSWORD_CHANGED', [
                    'user_id' => $user['user_id'],
                    'first_time' => $user['force_password_change']
                ]);
                
                $db->commit();
                
                // Update session data
                $_SESSION['user']['force_password_change'] = false;
                
                jsonResponse([
                    'success' => true,
                    'message' => __('password_changed_successfully')
                ]);
                
            } catch (Exception $e) {
                $db->rollback();
                logError("Password change failed for user {$user['user_id']}: " . $e->getMessage());
                jsonResponse(['error' => __('password_change_failed')], 500);
            }
            
        } catch (Exception $e) {
            logError("Password change error: " . $e->getMessage());
            jsonResponse(['error' => __('password_change_failed')], 500);
        }
        
        return; // Exit after handling AJAX request
    }
}

// Handle regular form submission for profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isAjaxRequest()) {
    try {
        // Validate CSRF token
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $error = __('invalid_security_token');
        } else {
            $username = sanitizeInput($_POST['username'] ?? '');
            $email = sanitizeInput($_POST['email'] ?? '');
            $displayName = sanitizeInput($_POST['display_name'] ?? '');
            $language = sanitizeInput($_POST['language'] ?? 'es');
            
            // Validate required fields
            if (empty($username) || empty($email) || empty($displayName)) {
                $error = __('please_fill_required_fields');
            } elseif (!validateEmail($email)) {
                $error = __('invalid_email_format');
            } elseif (!validateUsername($username)) {
                $error = __('invalid_username_format');
            } else {
                $db = Database::getInstance();
                
                // Check if username/email already exists (excluding current user)
                $existingUser = $db->fetch(
                    "SELECT user_id FROM users WHERE (username = ? OR email = ?) AND user_id != ?",
                    [$username, $email, $user['user_id']]
                );
                
                if ($existingUser) {
                    $error = __('username_or_email_already_exists');
                } else {
                    // Handle file upload if provided
                    $profilePicture = $user['profile_picture']; // Keep existing by default
                    
                    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                        $uploadResult = validateFileUpload($_FILES['profile_picture']);
                        
                        if ($uploadResult['valid']) {
                            $uploadPath = UPLOAD_DIR . $uploadResult['filename'];
                            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                                // Remove old profile picture
                                if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                                    unlink($user['profile_picture']);
                                }
                                $profilePicture = 'public/uploads/' . $uploadResult['filename'];
                            }
                        } else {
                            $error = $uploadResult['error'];
                        }
                    }
                    
                    if (empty($error)) {
                        $db->beginTransaction();
                        
                        try {
                            // Update user profile
                            $sql = "UPDATE users SET 
                                    username = ?, 
                                    email = ?, 
                                    display_name = ?, 
                                    profile_picture = ?,
                                    language = ?,
                                    updated_at = NOW()
                                    WHERE user_id = ?";
                            
                            $db->execute($sql, [
                                $username, 
                                $email, 
                                $displayName, 
                                $profilePicture,
                                $language,
                                $user['user_id']
                            ]);
                            
                            // Update session data
                            $_SESSION['user']['username'] = $username;
                            $_SESSION['user']['email'] = $email;
                            $_SESSION['user']['display_name'] = $displayName;
                            $_SESSION['user']['profile_picture'] = $profilePicture;
                            $_SESSION['user']['language'] = $language;
                            
                            // Log security event
                            logSecurityEvent('USER_UPDATED', ['target_user_id' => $user['user_id']]);
                            
                            $db->commit();
                            $success = __('profile_updated_successfully');
                            
                        } catch (Exception $e) {
                            $db->rollback();
                            logError("Profile update failed for user {$user['user_id']}: " . $e->getMessage());
                            $error = __('profile_update_failed');
                        }
                    }
                }
            }
        }
        
    } catch (Exception $e) {
        logError("Profile update error: " . $e->getMessage());
        $error = __('profile_update_failed');
    }
}

// Get user ID from URL for editing (admin function)
$editUserId = (int)($_GET['id'] ?? $user['user_id']);
$isEditingOther = $editUserId !== $user['user_id'];

// Check permissions for editing other users
if ($isEditingOther && !$user['is_admin']) {
    redirect(url('users', 'profile'));
}

// Get user data to edit
try {
    $db = Database::getInstance();
    
    if ($isEditingOther) {
        $editUser = $db->fetch("SELECT * FROM vw_user_profile WHERE user_id = ?", [$editUserId]);
        if (!$editUser) {
            redirect(url('users', 'list'));
        }
    } else {
        $editUser = $user;
        // Get role name for current user
        $roleResult = $db->fetch("SELECT role_name FROM roles WHERE role_id = ?", [$user['role_id']]);
        $editUser['role_name'] = $roleResult['role_name'] ?? '';
    }
    
    // Get available roles for admin
    $availableRoles = [];
    if ($user['is_admin']) {
        $availableRoles = $db->fetchAll("SELECT role_id, role_name FROM roles ORDER BY role_name");
    }
    
} catch (Exception $e) {
    logError("Failed to load user data: " . $e->getMessage());
    $error = __('failed_to_load_user_data');
}

// Include dashboard view
include __DIR__ . '/../views/dashboard.php';
?>