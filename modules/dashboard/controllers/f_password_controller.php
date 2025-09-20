<?php
/**
 * Force Password Change Controller - Sin JSON
 * Handles mandatory password changes with direct redirect
 */

// Include required files
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../config/db.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('/?module=auth&action=login');
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/?module=dashboard&action=index');
}

$user = getCurrentUser();
$db = Database::getInstance();

// Set error message in session for display
$_SESSION['password_change_error'] = '';
$_SESSION['password_change_success'] = '';

try {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $_SESSION['password_change_error'] = 'Token de seguridad inválido';
        redirect('/?module=dashboard&action=index');
    }

    // Get user data
    $sql = "SELECT password_hash, force_password_change FROM users WHERE user_id = ?";
    $userData = $db->fetch($sql, [$user['user_id']]);
    
    if (!$userData) {
        $_SESSION['password_change_error'] = 'Usuario no encontrado';
        redirect('/?module=dashboard&action=index');
    }
    
    if (!$userData['force_password_change']) {
        // User no longer needs to change password, just redirect
        redirect('/?module=dashboard&action=index');
    }

    // Get form data
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = [];

    // Validate current password
    if (empty($currentPassword)) {
        $errors[] = 'La contraseña actual es requerida';
    } elseif (!verifyPassword($currentPassword, $userData['password_hash'])) {
        $errors[] = 'La contraseña actual es incorrecta';
    }

    // Validate new password
    if (empty($newPassword)) {
        $errors[] = 'La nueva contraseña es requerida';
    } else {
        $passwordValidation = validatePassword($newPassword);
        if (!$passwordValidation['valid']) {
            $errors = array_merge($errors, $passwordValidation['errors']);
        }
    }

    // Validate password confirmation
    if (empty($confirmPassword)) {
        $errors[] = 'La confirmación de contraseña es requerida';
    } elseif (!empty($newPassword) && $newPassword !== $confirmPassword) {
        $errors[] = 'Las contraseñas no coinciden';
    }

    // Check if new password is different from current
    if (!empty($currentPassword) && !empty($newPassword) && $currentPassword === $newPassword) {
        $errors[] = 'La nueva contraseña debe ser diferente a la actual';
    }

    // If there are errors, store them and redirect back
    if (!empty($errors)) {
        $_SESSION['password_change_error'] = implode('. ', $errors);
        redirect('/?module=dashboard&action=index');
    }

    // Update password in database
    $db->beginTransaction();
    
    $newPasswordHash = hashPassword($newPassword);
    
    $sql = "UPDATE users SET 
                password_hash = ?, 
                force_password_change = FALSE,
                updated_at = NOW()
            WHERE user_id = ?";
    
    $result = $db->execute($sql, [$newPasswordHash, $user['user_id']]);
    
    if ($result->rowCount() === 0) {
        throw new Exception("Failed to update user password");
    }
    
    // Log audit entry
    $auditSql = "INSERT INTO audit_logs (
                    user_id, action, entity_type, entity_id, 
                    old_value, new_value, ip_address, user_agent, created_at
                 ) VALUES (?, 'FORCE_PASSWORD_CHANGE', 'USER', ?, 
                    JSON_OBJECT('force_password_change', true), 
                    JSON_OBJECT('force_password_change', false), 
                    ?, ?, NOW())";
    
    $clientIP = getClientIP();
    $userAgent = getUserAgent();
    
    $db->execute($auditSql, [
        $user['user_id'], 
        $user['user_id'], 
        $clientIP, 
        $userAgent
    ]);
    
    $db->commit();
    
    // Update session data
    $_SESSION['user']['force_password_change'] = false;
    
    // Log security event
    logError("FORCE_PASSWORD_CHANGE_COMPLETED: user_id={$user['user_id']}, username={$user['username']}, ip={$clientIP}", 'SECURITY');
    
    // Set success message
    $_SESSION['password_change_success'] = 'Contraseña cambiada exitosamente';
    
    // Redirect to dashboard
    redirect('/?module=dashboard&action=index');
    
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollback();
    }
    
    logError("Force password change error: " . $e->getMessage());
    
    $_SESSION['password_change_error'] = 'Error al cambiar la contraseña';
    redirect('/?module=dashboard&action=index');
}

exit;