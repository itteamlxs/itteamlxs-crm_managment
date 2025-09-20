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

// Prevent any redirects by disabling session timeout check for this endpoint
ini_set('session.gc_maxlifetime', 0);

header('Content-Type: application/json');

// Disable any potential redirects from helper functions
if (!function_exists('redirect')) {
    function redirect($url) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Redirect attempted in AJAX endpoint']);
        exit;
    }
}

if (!isset($_SESSION) || session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Authentication required']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!isAjaxRequest()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Direct access not allowed']);
    exit;
}

$user = getCurrentUser();
$db = Database::getInstance();

if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

$userId = (int)($_POST['user_id'] ?? 0);
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$errors = [];
$response = ['success' => false, 'message' => '', 'errors' => []];

try {
    if ($userId !== $user['user_id']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
        exit;
    }

    $sql = "SELECT password_hash, force_password_change FROM users WHERE user_id = ?";
    $userData = $db->fetch($sql, [$userId]);
    
    if (!$userData) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'User not found']);
        exit;
    }
    
    if (!$userData['force_password_change']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Password change not required']);
        exit;
    }

    if (empty($currentPassword)) {
        $errors['current_password'] = 'La contraseña actual es requerida';
    }

    if (empty($newPassword)) {
        $errors['new_password'] = 'La nueva contraseña es requerida';
    }

    if (empty($confirmPassword)) {
        $errors['confirm_password'] = 'La confirmación de contraseña es requerida';
    }

    if (!empty($currentPassword) && !verifyPassword($currentPassword, $userData['password_hash'])) {
        $errors['current_password'] = 'La contraseña actual es incorrecta';
    }

    if (!empty($newPassword)) {
        $passwordValidation = validatePassword($newPassword);
        if (!$passwordValidation['valid']) {
            $errors['new_password'] = implode('. ', $passwordValidation['errors']);
        }
    }

    if (!empty($newPassword) && !empty($confirmPassword) && $newPassword !== $confirmPassword) {
        $errors['confirm_password'] = 'Las contraseñas no coinciden';
    }

    if (!empty($currentPassword) && !empty($newPassword) && $currentPassword === $newPassword) {
        $errors['new_password'] = 'La nueva contraseña debe ser diferente a la actual';
    }

    if (!empty($errors)) {
        $response['errors'] = $errors;
        $response['message'] = 'Se encontraron errores de validación';
        http_response_code(400);
        echo json_encode($response);
        exit;
    }

    $db->beginTransaction();
    
    $newPasswordHash = hashPassword($newPassword);
    
    $sql = "UPDATE users SET 
                password_hash = ?, 
                force_password_change = FALSE,
                updated_at = NOW()
            WHERE user_id = ?";
    
    $result = $db->execute($sql, [$newPasswordHash, $userId]);
    
    if ($result->rowCount() === 0) {
        throw new Exception("Failed to update user password");
    }
    
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
    
    $_SESSION['user']['force_password_change'] = false;
    
    logSecurityEvent('FORCE_PASSWORD_CHANGE_COMPLETED', [
        'user_id' => $userId,
        'username' => $user['username']
    ]);
    
    $response['success'] = true;
    $response['message'] = 'Contraseña cambiada exitosamente';
    
    http_response_code(200);
    echo json_encode($response);
    
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollback();
    }
    
    logError("Force password change error: " . $e->getMessage());
    logSecurityEvent('FORCE_PASSWORD_CHANGE_FAILED', [
        'user_id' => $userId ?? 'unknown',
        'username' => $user['username'] ?? 'unknown',
        'error' => $e->getMessage()
    ]);
    
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update password']);
}

exit;