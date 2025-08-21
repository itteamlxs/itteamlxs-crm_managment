<?php
/**
 * Role Model
 * Handles role and permission database operations
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';

class RoleModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all roles
     * @return array
     */
    public function getAllRoles() {
        try {
            $sql = "SELECT role_id, role_name, description, created_at FROM roles ORDER BY role_name";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Get all roles error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get role by ID
     * @param int $roleId
     * @return array|false
     */
    public function getRoleById($roleId) {
        try {
            $sql = "SELECT role_id, role_name, description, created_at FROM roles WHERE role_id = ?";
            return $this->db->fetch($sql, [$roleId]);
        } catch (Exception $e) {
            logError("Get role by ID error: " . $e->getMessage());
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
            logError("Get all permissions error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get permissions by role ID
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
            logError("Get role permissions error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Assign permissions to role
     * @param int $roleId
     * @param array $permissionIds
     * @return bool
     */
    public function assignPermissions($roleId, $permissionIds) {
        try {
            $this->db->beginTransaction();
            
            // Remove existing permissions
            $sql = "DELETE FROM role_permissions WHERE role_id = ?";
            $this->db->execute($sql, [$roleId]);
            
            // Add new permissions
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
            logError("Assign permissions error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new role
     * @param array $roleData
     * @return int|false
     */
    public function createRole($roleData) {
        try {
            $sql = "INSERT INTO roles (role_name, description, created_at) VALUES (?, ?, NOW())";
            $this->db->execute($sql, [$roleData['role_name'], $roleData['description']]);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            logError("Create role error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update role
     * @param int $roleId
     * @param array $roleData
     * @return bool
     */
    public function updateRole($roleId, $roleData) {
        try {
            $sql = "UPDATE roles SET role_name = ?, description = ? WHERE role_id = ?";
            $this->db->execute($sql, [$roleData['role_name'], $roleData['description'], $roleId]);
            return true;
        } catch (Exception $e) {
            logError("Update role error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if role name exists
     * @param string $roleName
     * @param int $excludeRoleId
     * @return bool
     */
    public function roleNameExists($roleName, $excludeRoleId = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM roles WHERE role_name = ?";
            $params = [$roleName];
            
            if ($excludeRoleId) {
                $sql .= " AND role_id != ?";
                $params[] = $excludeRoleId;
            }
            
            $result = $this->db->fetch($sql, $params);
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            logError("Check role name exists error: " . $e->getMessage());
            return true;
        }
    }
}