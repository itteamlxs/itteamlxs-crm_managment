<?php
/**
 * Role Model
 * Database operations for roles and permissions
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class RoleModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all roles using the view to avoid duplicates
     * @return array
     */
    public function getAllRoles() {
        try {
            $sql = "SELECT role_id, role_name, description, created_at FROM vw_user_roles ORDER BY role_id";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Failed to get all roles: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get role by ID using the view
     * @param int $roleId
     * @return array|false
     */
    public function getRoleById($roleId) {
        try {
            $sql = "SELECT role_id, role_name, description, created_at FROM vw_user_roles WHERE role_id = ?";
            return $this->db->fetch($sql, [$roleId]);
        } catch (Exception $e) {
            logError("Failed to get role by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new role
     * @param array $data
     * @return bool
     */
    public function createRole($data) {
        try {
            $sql = "INSERT INTO roles (role_name, description, created_at) VALUES (?, ?, NOW())";
            $this->db->execute($sql, [$data['role_name'], $data['description']]);
            return true;
        } catch (Exception $e) {
            logError("Failed to create role: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update role
     * @param int $roleId
     * @param array $data
     * @return bool
     */
    public function updateRole($roleId, $data) {
        try {
            $sql = "UPDATE roles SET role_name = ?, description = ? WHERE role_id = ?";
            $this->db->execute($sql, [$data['role_name'], $data['description'], $roleId]);
            return true;
        } catch (Exception $e) {
            logError("Failed to update role: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all permissions
     * @return array
     */
    public function getAllPermissions() {
        try {
            $sql = "SELECT permission_id, permission_name, module, description FROM permissions ORDER BY module, permission_name";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Failed to get all permissions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get permissions grouped by module
     * @return array
     */
    public function getPermissionsByModule() {
        try {
            $permissions = $this->getAllPermissions();
            $grouped = [];
            
            foreach ($permissions as $permission) {
                $module = $permission['module'];
                if (!isset($grouped[$module])) {
                    $grouped[$module] = [];
                }
                $grouped[$module][] = $permission;
            }
            
            return $grouped;
        } catch (Exception $e) {
            logError("Failed to get permissions by module: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get role permissions
     * @param int $roleId
     * @return array
     */
    public function getRolePermissions($roleId) {
        try {
            $sql = "SELECT p.permission_id, p.permission_name, p.module, p.description 
                    FROM permissions p 
                    JOIN role_permissions rp ON p.permission_id = rp.permission_id 
                    WHERE rp.role_id = ? 
                    ORDER BY p.module, p.permission_name";
            return $this->db->fetchAll($sql, [$roleId]);
        } catch (Exception $e) {
            logError("Failed to get role permissions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get role permission IDs
     * @param int $roleId
     * @return array
     */
    public function getRolePermissionIds($roleId) {
        try {
            $sql = "SELECT permission_id FROM role_permissions WHERE role_id = ?";
            $results = $this->db->fetchAll($sql, [$roleId]);
            return array_column($results, 'permission_id');
        } catch (Exception $e) {
            logError("Failed to get role permission IDs: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update role permissions
     * @param int $roleId
     * @param array $permissionIds
     * @return bool
     */
    public function updateRolePermissions($roleId, $permissionIds = []) {
        try {
            $this->db->beginTransaction();
            
            // Delete existing permissions
            $sql = "DELETE FROM role_permissions WHERE role_id = ?";
            $this->db->execute($sql, [$roleId]);
            
            // Insert new permissions
            if (!empty($permissionIds)) {
                $sql = "INSERT INTO role_permissions (role_id, permission_id, created_at) VALUES (?, ?, NOW())";
                foreach ($permissionIds as $permissionId) {
                    $this->db->execute($sql, [$roleId, $permissionId]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Failed to update role permissions: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if role name exists (still use direct table for validation)
     * @param string $roleName
     * @param int $excludeRoleId
     * @return bool
     */
    public function roleNameExists($roleName, $excludeRoleId = null) {
        try {
            if ($excludeRoleId) {
                $sql = "SELECT COUNT(DISTINCT role_id) as count FROM roles WHERE role_name = ? AND role_id != ?";
                $result = $this->db->fetch($sql, [$roleName, $excludeRoleId]);
            } else {
                $sql = "SELECT COUNT(DISTINCT role_id) as count FROM roles WHERE role_name = ?";
                $result = $this->db->fetch($sql, [$roleName]);
            }
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            logError("Failed to check role name existence: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Count users assigned to role
     * @param int $roleId
     * @return int
     */
    public function countUsersInRole($roleId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE role_id = ? AND is_active = 1";
            $result = $this->db->fetch($sql, [$roleId]);
            return (int)($result['count'] ?? 0);
        } catch (Exception $e) {
            logError("Failed to count users in role: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Check if role can be deleted
     * @param int $roleId
     * @return bool
     */
    public function canDeleteRole($roleId) {
        return $this->countUsersInRole($roleId) === 0;
    }
    
    /**
     * Delete role
     * @param int $roleId
     * @return bool
     */
    public function deleteRole($roleId) {
        if (!$this->canDeleteRole($roleId)) {
            return false;
        }
        
        try {
            $sql = "DELETE FROM roles WHERE role_id = ?";
            $this->db->execute($sql, [$roleId]);
            return true;
        } catch (Exception $e) {
            logError("Failed to delete role: " . $e->getMessage());
            return false;
        }
    }
} 