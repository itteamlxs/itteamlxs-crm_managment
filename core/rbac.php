<?php
/**
 * Role-Based Access Control (RBAC)
 * Permission checks and access control functions
 */

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../config/db.php';

/**
 * Check if current user has specific permission
 * @param string $permissionName
 * @return bool
 */
function hasPermission($permissionName) {
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    
    // Admin has all permissions
    if ($user['is_admin']) {
        return true;
    }
    
    try {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) as count
                FROM role_permissions rp
                JOIN permissions p ON rp.permission_id = p.permission_id
                WHERE rp.role_id = ? AND p.permission_name = ?";
        
        $result = $db->fetch($sql, [$user['role_id'], $permissionName]);
        return ($result['count'] ?? 0) > 0;
        
    } catch (Exception $e) {
        logError("RBAC permission check failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if current user has any of the specified permissions
 * @param array $permissions
 * @return bool
 */
function hasAnyPermission($permissions) {
    foreach ($permissions as $permission) {
        if (hasPermission($permission)) {
            return true;
        }
    }
    return false;
}

/**
 * Check if current user has all specified permissions
 * @param array $permissions
 * @return bool
 */
function hasAllPermissions($permissions) {
    foreach ($permissions as $permission) {
        if (!hasPermission($permission)) {
            return false;
        }
    }
    return true;
}

/**
 * Require specific permission or redirect/exit
 * @param string $permissionName
 * @param string $redirectUrl
 */
function requirePermission($permissionName, $redirectUrl = '/') {
    if (!hasPermission($permissionName)) {
        if (isAjaxRequest()) {
            jsonResponse(['error' => 'Access denied'], 403);
        } else {
            logError("Access denied for permission: {$permissionName}, User: " . (getCurrentUser()['username'] ?? 'Unknown'));
            redirect($redirectUrl);
        }
    }
}

/**
 * Require user to be logged in
 * @param string $redirectUrl
 */
function requireLogin($redirectUrl = '/?module=auth&action=login') {
    if (!isLoggedIn()) {
        if (isAjaxRequest()) {
            jsonResponse(['error' => 'Authentication required'], 401);
        } else {
            redirect($redirectUrl);
        }
    }
}

/**
 * Require admin access
 * @param string $redirectUrl
 */
function requireAdmin($redirectUrl = '/') {
    $user = getCurrentUser();
    if (!$user || !$user['is_admin']) {
        if (isAjaxRequest()) {
            jsonResponse(['error' => 'Admin access required'], 403);
        } else {
            logError("Admin access denied, User: " . ($user['username'] ?? 'Unknown'));
            redirect($redirectUrl);
        }
    }
}

/**
 * Check if user can access specific module
 * @param string $module
 * @return bool
 */
function canAccessModule($module) {
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    
    // Admin can access all modules
    if ($user['is_admin']) {
        return true;
    }
    
    try {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) as count
                FROM role_permissions rp
                JOIN permissions p ON rp.permission_id = p.permission_id
                WHERE rp.role_id = ? AND p.module = ?";
        
        $result = $db->fetch($sql, [$user['role_id'], $module]);
        return ($result['count'] ?? 0) > 0;
        
    } catch (Exception $e) {
        logError("Module access check failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Get user's permissions for a specific module
 * @param string $module
 * @return array
 */
function getUserModulePermissions($module) {
    $user = getCurrentUser();
    if (!$user) {
        return [];
    }
    
    try {
        $db = Database::getInstance();
        
        // Admin gets all permissions for the module
        if ($user['is_admin']) {
            $sql = "SELECT permission_name FROM permissions WHERE module = ?";
            $results = $db->fetchAll($sql, [$module]);
        } else {
            $sql = "SELECT p.permission_name
                    FROM role_permissions rp
                    JOIN permissions p ON rp.permission_id = p.permission_id
                    WHERE rp.role_id = ? AND p.module = ?";
            $results = $db->fetchAll($sql, [$user['role_id'], $module]);
        }
        
        return array_column($results, 'permission_name');
        
    } catch (Exception $e) {
        logError("Get module permissions failed: " . $e->getMessage());
        return [];
    }
}

/**
 * Get all user permissions
 * @return array
 */
function getAllUserPermissions() {
    $user = getCurrentUser();
    if (!$user) {
        return [];
    }
    
    try {
        $db = Database::getInstance();
        
        // Admin gets all permissions
        if ($user['is_admin']) {
            $sql = "SELECT permission_name FROM permissions";
            $results = $db->fetchAll($sql);
        } else {
            $sql = "SELECT p.permission_name
                    FROM role_permissions rp
                    JOIN permissions p ON rp.permission_id = p.permission_id
                    WHERE rp.role_id = ?";
            $results = $db->fetchAll($sql, [$user['role_id']]);
        }
        
        return array_column($results, 'permission_name');
        
    } catch (Exception $e) {
        logError("Get all permissions failed: " . $e->getMessage());
        return [];
    }
}

/**
 * Check if user owns a resource (for edit_own_profile type permissions)
 * @param string $entityType
 * @param int $entityId
 * @param int $userId
 * @return bool
 */
function ownsResource($entityType, $entityId, $userId = null) {
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    
    $userId = $userId ?? $user['user_id'];
    
    // Admin can access any resource
    if ($user['is_admin']) {
        return true;
    }
    
    try {
        $db = Database::getInstance();
        
        switch ($entityType) {
            case 'user':
                return (int)$entityId === (int)$userId;
                
            case 'quote':
                $sql = "SELECT user_id FROM quotes WHERE quote_id = ?";
                $result = $db->fetch($sql, [$entityId]);
                return $result && (int)$result['user_id'] === (int)$userId;
                
            case 'client':
                $sql = "SELECT created_by FROM clients WHERE client_id = ?";
                $result = $db->fetch($sql, [$entityId]);
                return $result && (int)$result['created_by'] === (int)$userId;
                
            default:
                return false;
        }
        
    } catch (Exception $e) {
        logError("Resource ownership check failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Prevent privilege escalation
 * @param int $targetRoleId
 * @return bool
 */
function canAssignRole($targetRoleId) {
    $user = getCurrentUser();
    if (!$user || !$user['is_admin']) {
        return false;
    }
    
    try {
        $db = Database::getInstance();
        
        // Get target role info
        $sql = "SELECT role_name FROM roles WHERE role_id = ?";
        $targetRole = $db->fetch($sql, [$targetRoleId]);
        
        if (!$targetRole) {
            return false;
        }
        
        // Prevent non-super-admin from creating other admins
        // (This would need additional logic if you have super-admin concept)
        return true;
        
    } catch (Exception $e) {
        logError("Role assignment check failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Log security event
 * @param string $event
 * @param array $details
 */
function logSecurityEvent($event, $details = []) {
    $user = getCurrentUser();
    $logData = [
        'event' => $event,
        'user_id' => $user['user_id'] ?? null,
        'username' => $user['username'] ?? 'Anonymous',
        'ip_address' => getClientIP(),
        'user_agent' => getUserAgent(),
        'timestamp' => date('Y-m-d H:i:s'),
        'details' => $details
    ];
    
    logError("SECURITY_EVENT: " . json_encode($logData), 'SECURITY');
}

/**
 * Check session timeout
 * @return bool
 */
function checkSessionTimeout() {
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return true;
}