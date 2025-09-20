<?php
/**
 * Force Password Change Controller
 * Handles mandatory password changes for users with force_password_change flag
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../config/db.php';

// Require login
requireLogin();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

// Only allow AJAX requests
if (!isAjaxRequest()) {
    jsonResponse(['error' => 'Direct access not allowed'], 403);
}

$user = getCurrentUser();
$db = Database::getInstance();

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
    jsonResponse(['error' => 'Invalid CSRF token'], 403);
}

// Get form data
$userId = (int)($_POST['user_id'] ?? 0);
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$errors = [];
$response = ['success' => false, 'message' => '', 'errors' => []];

// Validate user ID matches current user
if ($userId !== $user['user_id']) {
    jsonResponse(['error' => 'Unauthorized access'], 403);
}

// Verify user actually needs to change password
try {
    $sql = "SELECT password_hash, force_password_change FROM users WHERE user_id = ?";
    $userData = $db->fetch($sql, [$userId]);
    
    if (!$userData) {
        jsonResponse(['error' => 'User not found'], 404);
    }
    
    if (!$userData['force_password_change']) {
        jsonResponse(['error' => 'Password change not required'], 400);
    }
    
} catch (Exception $e) {
    logError("Force password change - user lookup error: " . $e->getMessage());
    jsonResponse(['error' => 'Database error'], 500);
}

// Validate input data
if (empty($currentPassword)) {
    $errors['current_password'] = __('current_password_required') ?: 'La contraseña actual es requerida';
}

if (empty($newPassword)) {
    $errors['new_password'] = __('new_password_required') ?: 'La nueva contraseña es requerida';
}

if (empty($confirmPassword)) {
    $errors['confirm_password'] = __('confirm_password_required') ?: 'La confirmación de contraseña es requerida';
}

// Verify current password
if (!empty($currentPassword) && !verifyPassword($currentPassword, $userData['password_hash'])) {
    $errors['current_password'] = __('current_password_incorrect') ?: 'La contraseña actual es incorrecta';
}

// Validate new password strength
if (!empty($newPassword)) {
    $passwordValidation = validatePassword($newPassword);
    if (!$passwordValidation['valid']) {
        $errors['new_password'] = implode('. ', $passwordValidation['errors']);
    }
}

// Verify passwords match
if (!empty($newPassword) && !empty($confirmPassword) && $newPassword !== $confirmPassword) {
    $errors['confirm_password'] = __('passwords_do_not_match') ?: 'Las contraseñas no coinciden';
}

// Prevent using the same password
if (!empty($currentPassword) && !empty($newPassword) && $currentPassword === $newPassword) {
    $errors['new_password'] = __('new_password_must_be_different') ?: 'La nueva contraseña debe ser diferente a la actual';
}

// If there are validation errors, return them
if (!empty($errors)) {
    $response['errors'] = $errors;
    $response['message'] = __('validation_errors_found') ?: 'Se encontraron errores de validación';
    jsonResponse($response, 400);
}

// Update password and remove force change flag
try {
    $db->beginTransaction();
    
    // Hash new password
    $newPasswordHash = hashPassword($newPassword);
    
    // Update user password and remove force_password_change flag
    $sql = "UPDATE users SET 
                password_hash = ?, 
                force_password_change = FALSE,
                updated_at = NOW()
            WHERE user_id = ?";
    
    $result = $db->execute($sql, [$newPasswordHash, $userId]);
    
    if ($result->rowCount() === 0) {
        throw new Exception("Failed to update user password");
    }
    
    // Log the password change in audit logs
    $auditSql = "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at) 
                 VALUES (?, 'FORCE_PASSWORD_CHANGE', 'USER', ?, 
                         JSON_OBJECT('force_password_change', true), 
                         JSON_OBJECT('force_password_change', false), 
                         ?, ?, NOW())";
    
    $db->execute($auditSql, [
        $userId, 
        $userId, 
        getClientIP(), 
        getUserAgent()
    ]);
    
    $db->commit();
    
    // Update session user data to reflect the change
    $_SESSION['user']['force_password_change'] = false;
    
    // Log security event
    logSecurityEvent('FORCE_PASSWORD_CHANGE_COMPLETED', [
        'user_id' => $userId,
        'username' => $user['username']
    ]);
    
    $response['success'] = true;
    $response['message'] = __('password_changed_successfully') ?: 'Contraseña cambiada exitosamente';
    
    jsonResponse($response, 200);
    
} catch (Exception $e) {
    $db->rollback();
    
    logError("Force password change error: " . $e->getMessage());
    logSecurityEvent('FORCE_PASSWORD_CHANGE_FAILED', [
        'user_id' => $userId,
        'username' => $user['username'],
        'error' => $e->getMessage()
    ]);
    
    jsonResponse(['error' => 'Failed to update password'], 500);
}