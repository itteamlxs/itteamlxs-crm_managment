<?php
/**
 * Authentication Model
 * Handles user authentication queries
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';

class AuthModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Authenticate user login
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function login($username, $password) {
        try {
            $sql = "SELECT u.user_id, u.username, u.email, u.password_hash, u.display_name, 
                           u.profile_picture, u.language, u.role_id, u.is_admin, u.is_active,
                           u.failed_login_attempts, u.locked_until, u.force_password_change,
                           r.role_name
                    FROM users u 
                    JOIN roles r ON u.role_id = r.role_id
                    WHERE u.username = ? OR u.email = ?";
            
            $user = $this->db->fetch($sql, [$username, $username]);
            
            if (!$user) {
                return false;
            }
            
            // Check if account is active
            if (!$user['is_active']) {
                return false;
            }
            
            // Check if account is locked
            if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                return false;
            }
            
            // Verify password
            if (!verifyPassword($password, $user['password_hash'])) {
                $this->incrementFailedAttempts($user['user_id']);
                return false;
            }
            
            // Reset failed attempts on successful login
            $this->resetFailedAttempts($user['user_id']);
            
            // Update last login
            $this->updateLastLogin($user['user_id']);
            
            // Remove sensitive data
            unset($user['password_hash']);
            
            return $user;
            
        } catch (Exception $e) {
            logError("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Increment failed login attempts
     * @param int $userId
     */
    private function incrementFailedAttempts($userId) {
        try {
            $sql = "UPDATE users 
                    SET failed_login_attempts = failed_login_attempts + 1,
                        locked_until = CASE 
                            WHEN failed_login_attempts + 1 >= ? 
                            THEN DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                            ELSE locked_until 
                        END
                    WHERE user_id = ?";
            
            $this->db->execute($sql, [MAX_LOGIN_ATTEMPTS, $userId]);
            
        } catch (Exception $e) {
            logError("Failed to increment login attempts: " . $e->getMessage());
        }
    }
    
    /**
     * Reset failed login attempts
     * @param int $userId
     */
    private function resetFailedAttempts($userId) {
        try {
            $sql = "UPDATE users 
                    SET failed_login_attempts = 0, 
                        locked_until = NULL 
                    WHERE user_id = ?";
            
            $this->db->execute($sql, [$userId]);
            
        } catch (Exception $e) {
            logError("Failed to reset login attempts: " . $e->getMessage());
        }
    }
    
    /**
     * Update last login timestamp
     * @param int $userId
     */
    private function updateLastLogin($userId) {
        try {
            $sql = "UPDATE users SET last_login_at = NOW() WHERE user_id = ?";
            $this->db->execute($sql, [$userId]);
            
        } catch (Exception $e) {
            logError("Failed to update last login: " . $e->getMessage());
        }
    }
    
    /**
     * Get user by ID for session
     * @param int $userId
     * @return array|false
     */
    public function getUserById($userId) {
        try {
            $sql = "SELECT u.user_id, u.username, u.email, u.display_name, 
                           u.profile_picture, u.language, u.role_id, u.is_admin,
                           r.role_name
                    FROM users u 
                    JOIN roles r ON u.role_id = r.role_id
                    WHERE u.user_id = ? AND u.is_active = 1";
            
            return $this->db->fetch($sql, [$userId]);
            
        } catch (Exception $e) {
            logError("Get user by ID error: " . $e->getMessage());
            return false;
        }
    }
}