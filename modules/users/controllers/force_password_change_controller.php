<?php
/**
 * Force Password Change Controller - CORREGIDO
 * Handles mandatory password changes for users with force_password_change flag
 */

// Configurar headers primero
header('Content-Type: application/json');

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos necesarios
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../config/db.php';

// Validaciones básicas
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Direct access not allowed']);
    exit;
}

// Verificar login
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Authentication required']);
    exit;
}

$user = $_SESSION['user'];
$db = Database::getInstance();

// Validar CSRF
if (!isset($_POST['csrf_token']) || 
    !isset($_SESSION['csrf_token']) || 
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
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
    // Verificar que es el usuario correcto
    if ($userId !== $user['user_id']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
        exit;
    }

    // Obtener datos del usuario
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

    // Validaciones
    if (empty($currentPassword)) {
        $errors['current_password'] = 'La contraseña actual es requerida';
    }

    if (empty($newPassword)) {
        $errors['new_password'] = 'La nueva contraseña es requerida';
    }

    if (empty($confirmPassword)) {
        $errors['confirm_password'] = 'La confirmación de contraseña es requerida';
    }

    // Verificar contraseña actual
    if (!empty($currentPassword) && !password_verify($currentPassword, $userData['password_hash'])) {
        $errors['current_password'] = 'La contraseña actual es incorrecta';
    }

    // Validar nueva contraseña
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 8) {
            $errors['new_password'] = 'La contraseña debe tener al menos 8 caracteres';
        } else if (!preg_match('/[A-Z]/', $newPassword)) {
            $errors['new_password'] = 'La contraseña debe contener al menos una mayúscula';
        } else if (!preg_match('/[a-z]/', $newPassword)) {
            $errors['new_password'] = 'La contraseña debe contener al menos una minúscula';
        } else if (!preg_match('/[0-9]/', $newPassword)) {
            $errors['new_password'] = 'La contraseña debe contener al menos un número';
        } else if (!preg_match('/[^a-zA-Z0-9]/', $newPassword)) {
            $errors['new_password'] = 'La contraseña debe contener al menos un carácter especial';
        }
    }

    // Verificar que las contraseñas coincidan
    if (!empty($newPassword) && !empty($confirmPassword) && $newPassword !== $confirmPassword) {
        $errors['confirm_password'] = 'Las contraseñas no coinciden';
    }

    // Verificar que sea diferente a la actual
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

    // Actualizar contraseña
    $db->beginTransaction();
    
    $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    
    $sql = "UPDATE users SET 
                password_hash = ?, 
                force_password_change = FALSE,
                updated_at = NOW()
            WHERE user_id = ?";
    
    $result = $db->execute($sql, [$newPasswordHash, $userId]);
    
    if ($result->rowCount() === 0) {
        throw new Exception("Failed to update user password");
    }
    
    // Log de auditoría
    $auditSql = "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent, created_at) 
                 VALUES (?, 'FORCE_PASSWORD_CHANGE', 'USER', ?, 
                         JSON_OBJECT('force_password_change', true), 
                         JSON_OBJECT('force_password_change', false), 
                         ?, ?, NOW())";
    
    $clientIP = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $db->execute($auditSql, [
        $userId, 
        $userId, 
        $clientIP, 
        $userAgent
    ]);
    
    $db->commit();
    
    // Actualizar sesión
    $_SESSION['user']['force_password_change'] = false;
    
    // Log de seguridad
    $logDir = __DIR__ . '/../../../logs/';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . 'app_' . date('Y-m-d') . '.log';
    $logMessage = '[' . date('Y-m-d H:i:s') . '] [SECURITY] FORCE_PASSWORD_CHANGE_COMPLETED: user_id=' . $userId . ', username=' . ($user['username'] ?? 'unknown') . ', ip=' . $clientIP . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    $response['success'] = true;
    $response['message'] = 'Contraseña cambiada exitosamente';
    
    http_response_code(200);
    echo json_encode($response);
    
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollback();
    }
    
    // Log error
    $logDir = __DIR__ . '/../../../logs/';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . 'app_' . date('Y-m-d') . '.log';
    $logMessage = '[' . date('Y-m-d H:i:s') . '] [ERROR] Force password change error: ' . $e->getMessage() . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update password']);
}

exit;