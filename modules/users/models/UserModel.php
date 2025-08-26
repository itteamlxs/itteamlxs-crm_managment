<?php
/**
 * User Model
 * Handles user-related database operations
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';

class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all users with pagination
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public function getAllUsers($limit = 10, $offset = 0, $search = '') {
        try {
            $searchCondition = '';
            $params = [];
            
            if (!empty($search)) {
                $searchCondition = "WHERE username LIKE ? OR email LIKE ? OR display_name LIKE ?";
                $searchTerm = "%{$search}%";
                $params = [$searchTerm, $searchTerm, $searchTerm];
            }
            
            $sql = "SELECT user_id, username, email, display_name, 
                           profile_picture, language, is_admin, is_active,
                           created_at, last_login_at, role_name
                    FROM vw_users
                    {$searchCondition}
                    LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            return $this->db->fetchAll($sql, $params);
            
        } catch (Exception $e) {
            logError("Get all users error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get total users count
     * @param string $search
     * @return int
     */
    public function getUsersCount($search = '') {
        try {
            $searchCondition = '';
            $params = [];
            
            if (!empty($search)) {
                $searchCondition = "WHERE username LIKE ? OR email LIKE ? OR display_name LIKE ?";
                $searchTerm = "%{$search}%";
                $params = [$searchTerm, $searchTerm, $searchTerm];
            }
            
            $sql = "SELECT COUNT(*) as count FROM users {$searchCondition}";
            $result = $this->db->fetch($sql, $params);
            
            return $result['count'] ?? 0;
            
        } catch (Exception $e) {
            logError("Get users count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get user by ID
     * @param int $userId
     * @return array|false
     */
    public function getUserById($userId) {
        try {
            $sql = "SELECT user_id, username, email, display_name, 
                           profile_picture, language, role_id, is_admin, 
                           is_active, force_password_change, created_at, 
                           last_login_at, role_name, role_description
                    FROM vw_user_profile
                    WHERE user_id = ?";
            
            return $this->db->fetch($sql, [$userId]);
            
        } catch (Exception $e) {
            logError("Get user by ID error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new user
     * @param array $userData
     * @return int|false
     */
    public function createUser($userData) {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO users (username, email, password_hash, display_name, 
                                     language, role_id, is_admin, is_active, 
                                     force_password_change, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $params = [
                $userData['username'],
                $userData['email'],
                $userData['password_hash'],
                $userData['display_name'],
                $userData['language'] ?? 'es',
                $userData['role_id'],
                $userData['is_admin'] ?? 0,
                $userData['is_active'] ?? 1,
                $userData['force_password_change'] ?? 1
            ];
            
            $this->db->execute($sql, $params);
            $userId = $this->db->lastInsertId();
            
            $this->db->commit();
            return $userId;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Create user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user
     * @param int $userId
     * @param array $userData
     * @return bool
     */
    public function updateUser($userId, $userData) {
        try {
            $this->db->beginTransaction();
            
            $setParts = [];
            $params = [];
            
            $allowedFields = ['username', 'email', 'display_name', 'language', 
                            'role_id', 'is_admin', 'is_active', 'profile_picture',
                            'password_hash', 'force_password_change'];
            
            foreach ($allowedFields as $field) {
                if (isset($userData[$field])) {
                    $setParts[] = "{$field} = ?";
                    $params[] = $userData[$field];
                }
            }
            
            if (!empty($setParts)) {
                $setParts[] = "updated_at = NOW()";
                
                // Reset failed attempts and unlock if password is being changed
                if (isset($userData['password_hash'])) {
                    $setParts[] = "failed_login_attempts = 0";
                    $setParts[] = "locked_until = NULL";
                }
                
                $sql = "UPDATE users SET " . implode(', ', $setParts) . " WHERE user_id = ?";
                $params[] = $userId;
                
                $this->db->execute($sql, $params);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Update user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user password
     * @param int $userId
     * @param string $newPasswordHash
     * @param bool $forceChange
     * @return bool
     */
    public function updatePassword($userId, $newPasswordHash, $forceChange = false) {
        try {
            $sql = "UPDATE users 
                    SET password_hash = ?, 
                        force_password_change = ?,
                        failed_login_attempts = 0,
                        locked_until = NULL,
                        updated_at = NOW()
                    WHERE user_id = ?";
            
            $this->db->execute($sql, [$newPasswordHash, $forceChange ? 1 : 0, $userId]);
            return true;
            
        } catch (Exception $e) {
            logError("Update password error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify current password for user
     * @param int $userId
     * @param string $currentPassword
     * @return bool
     */
    public function verifyCurrentPassword($userId, $currentPassword) {
        try {
            $sql = "SELECT password_hash FROM users WHERE user_id = ?";
            $result = $this->db->fetch($sql, [$userId]);
            
            if (!$result) {
                return false;
            }
            
            return verifyPassword($currentPassword, $result['password_hash']);
            
        } catch (Exception $e) {
            logError("Verify current password error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if username exists
     * @param string $username
     * @param int $excludeUserId
     * @return bool
     */
    public function usernameExists($username, $excludeUserId = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
            $params = [$username];
            
            if ($excludeUserId) {
                $sql .= " AND user_id != ?";
                $params[] = $excludeUserId;
            }
            
            $result = $this->db->fetch($sql, $params);
            return ($result['count'] ?? 0) > 0;
            
        } catch (Exception $e) {
            logError("Check username exists error: " . $e->getMessage());
            return true; // Assume exists for safety
        }
    }
    
    /**
     * Check if email exists
     * @param string $email
     * @param int $excludeUserId
     * @return bool
     */
    public function emailExists($email, $excludeUserId = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
            $params = [$email];
            
            if ($excludeUserId) {
                $sql .= " AND user_id != ?";
                $params[] = $excludeUserId;
            }
            
            $result = $this->db->fetch($sql, $params);
            return ($result['count'] ?? 0) > 0;
            
        } catch (Exception $e) {
            logError("Check email exists error: " . $e->getMessage());
            return true; // Assume exists for safety
        }
    }
    
    /**
     * Get all roles for dropdown
     * @return array
     */
    public function getAllRoles() {
        try {
            $sql = "SELECT role_id, role_name, description FROM vw_user_roles";
            return $this->db->fetchAll($sql);
            
        } catch (Exception $e) {
            logError("Get all roles error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get available languages
     * @return array
     */
    public function getAvailableLanguages() {
        try {
            $sql = "SELECT setting_value FROM settings WHERE setting_key = 'available_languages'";
            $result = $this->db->fetch($sql);
            
            if ($result) {
                return json_decode($result['setting_value'], true) ?? ['es', 'en'];
            }
            
            return ['es', 'en'];
            
        } catch (Exception $e) {
            logError("Get available languages error: " . $e->getMessage());
            return ['es', 'en'];
        }
    }
    
    /**
     * Deactivate user (soft delete)
     * @param int $userId
     * @return bool
     */
    public function deactivateUser($userId) {
        try {
            $sql = "UPDATE users SET is_active = 0, updated_at = NOW() WHERE user_id = ?";
            $this->db->execute($sql, [$userId]);
            return true;
            
        } catch (Exception $e) {
            logError("Deactivate user error: " . $e->getMessage());
            return false;
        }
    }
}