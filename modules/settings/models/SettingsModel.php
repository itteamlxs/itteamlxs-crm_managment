<?php
/**
 * Settings Model
 * Handles system settings operations
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';

class SettingsModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all settings
     * @return array
     */
    public function getAllSettings() {
        try {
            $sql = "SELECT setting_id, setting_key, setting_value FROM vw_settings ORDER BY setting_key";
            $results = $this->db->fetchAll($sql);
            
            // Convert to key-value array
            $settings = [];
            foreach ($results as $result) {
                $settings[$result['setting_key']] = $result['setting_value'];
            }
            
            return $settings;
        } catch (Exception $e) {
            logError("Get all settings error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get setting by key
     * @param string $key
     * @return string|null
     */
    public function getSetting($key) {
        try {
            $sql = "SELECT setting_value FROM settings WHERE setting_key = ?";
            $result = $this->db->fetch($sql, [$key]);
            
            return $result['setting_value'] ?? null;
        } catch (Exception $e) {
            logError("Get setting error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update setting
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function updateSetting($key, $value) {
        try {
            // Check if setting exists
            $existingSetting = $this->getSetting($key);
            
            if ($existingSetting !== null) {
                // Update existing setting
                $sql = "UPDATE settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?";
                $this->db->execute($sql, [$value, $key]);
            } else {
                // Insert new setting
                $sql = "INSERT INTO settings (setting_key, setting_value, created_at) VALUES (?, ?, NOW())";
                $this->db->execute($sql, [$key, $value]);
            }
            
            return true;
        } catch (Exception $e) {
            logError("Update setting error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update multiple settings
     * @param array $settings
     * @return bool
     */
    public function updateSettings($settings) {
        try {
            $this->db->beginTransaction();
            
            foreach ($settings as $key => $value) {
                $this->updateSetting($key, $value);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            logError("Update settings error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get available languages from settings
     * @return array
     */
    public function getAvailableLanguages() {
        try {
            $languagesJson = $this->getSetting('available_languages');
            return json_decode($languagesJson, true) ?? ['es', 'en'];
        } catch (Exception $e) {
            logError("Get available languages error: " . $e->getMessage());
            return ['es', 'en'];
        }
    }
    
    /**
     * Get SMTP settings for email configuration
     * @return array
     */
    public function getSmtpSettings() {
        try {
            return [
                'smtp_host' => $this->getSetting('smtp_host') ?? '',
                'smtp_port' => $this->getSetting('smtp_port') ?? '587',
                'smtp_username' => $this->getSetting('smtp_username') ?? '',
                'smtp_password' => $this->getSetting('smtp_password') ?? '',
                'smtp_encryption' => $this->getSetting('smtp_encryption') ?? 'TLS',
                'from_email' => $this->getSetting('from_email') ?? '',
                'from_name' => $this->getSetting('from_name') ?? ''
            ];
        } catch (Exception $e) {
            logError("Get SMTP settings error: " . $e->getMessage());
            return [];
        }
    }
}