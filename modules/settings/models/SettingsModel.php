<?php
/**
 * Settings Model
 * Manages global application settings using vw_settings view
 */

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../core/helpers.php';

class SettingsModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all settings using view
     * @return array
     */
    public function getAllSettings() {
        try {
            $sql = "SELECT setting_id, setting_key, setting_value, updated_at FROM vw_settings";
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            logError("Failed to get all settings: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get setting by key
     * @param string $key
     * @return array|false
     */
    public function getSettingByKey($key) {
        try {
            $sql = "SELECT setting_id, setting_key, setting_value, updated_at FROM vw_settings WHERE setting_key = ?";
            return $this->db->fetch($sql, [$key]);
        } catch (Exception $e) {
            logError("Failed to get setting by key {$key}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update setting value
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function updateSetting($key, $value) {
        try {
            $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
            $this->db->execute($sql, [$value, $key]);
            return true;
        } catch (Exception $e) {
            logError("Failed to update setting {$key}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Insert new setting
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function insertSetting($key, $value) {
        try {
            $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)";
            $this->db->execute($sql, [$key, $value]);
            return true;
        } catch (Exception $e) {
            logError("Failed to insert setting {$key}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get settings grouped by category
     * @return array
     */
    public function getSettingsByCategory() {
        $settings = $this->getAllSettings();
        $grouped = [];
        
        foreach ($settings as $setting) {
            $category = $this->getCategoryFromKey($setting['setting_key']);
            $grouped[$category][] = $setting;
        }
        
        return $grouped;
    }
    
    /**
     * Get category from setting key
     * @param string $key
     * @return string
     */
    private function getCategoryFromKey($key) {
        $categories = [
            'company_' => 'company',
            'smtp_' => 'email',
            'from_' => 'email',
            'default_tax_rate' => 'quotes',
            'quote_' => 'quotes',
            'low_stock_threshold' => 'products',
            'timezone' => 'system',
            'available_languages' => 'system',
            'backup_' => 'system'
        ];
        
        foreach ($categories as $prefix => $category) {
            if (strpos($key, $prefix) === 0) {
                return $category;
            }
        }
        
        return 'other';
    }
    
    /**
     * Validate setting value
     * @param string $key
     * @param string $value
     * @return array ['valid' => bool, 'error' => string]
     */
    public function validateSetting($key, $value) {
        $validation = ['valid' => true, 'error' => ''];
        
        switch ($key) {
            case 'smtp_port':
                if (!is_numeric($value) || $value < 1 || $value > 65535) {
                    $validation = ['valid' => false, 'error' => 'Puerto SMTP debe ser un número entre 1 y 65535'];
                }
                break;
                
            case 'default_tax_rate':
                if (!is_numeric($value) || $value < 0 || $value > 100) {
                    $validation = ['valid' => false, 'error' => 'Tasa de impuesto debe ser un número entre 0 y 100'];
                }
                break;
                
            case 'quote_expiry_days':
            case 'quote_expiry_notification_days':
            case 'low_stock_threshold':
                if (!is_numeric($value) || $value < 1) {
                    $validation = ['valid' => false, 'error' => 'Debe ser un número mayor a 0'];
                }
                break;
                
            case 'smtp_host':
                if (empty($value) || !filter_var('email@' . $value, FILTER_VALIDATE_EMAIL)) {
                    $validation = ['valid' => false, 'error' => 'Host SMTP inválido'];
                }
                break;
                
            case 'from_email':
            case 'smtp_username':
                if (!validateEmail($value)) {
                    $validation = ['valid' => false, 'error' => 'Formato de email inválido'];
                }
                break;
                
            case 'available_languages':
                $decoded = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $validation = ['valid' => false, 'error' => 'Formato JSON inválido'];
                }
                break;
                
            case 'backup_time':
                if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $value)) {
                    $validation = ['valid' => false, 'error' => 'Formato de hora inválido (HH:MM:SS)'];
                }
                break;
        }
        
        return $validation;
    }
    
    /**
     * Upload and save company logo
     * @param array $file $_FILES array element
     * @return array ['success' => bool, 'filename' => string, 'error' => string]
     */
    public function uploadCompanyLogo($file) {
        $result = ['success' => false, 'filename' => '', 'error' => ''];
        
        // Validate file upload
        $validation = validateFileUpload($file, ['jpg', 'jpeg', 'png', 'gif']);
        if (!$validation['valid']) {
            $result['error'] = $validation['error'];
            return $result;
        }
        
        $uploadDir = __DIR__ . '/../../../public/uploads/';
        $filename = 'company_logo_' . time() . '.' . strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $uploadPath = $uploadDir . $filename;
        
        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Remove old logo if exists
        $currentLogo = $this->getSettingByKey('company_logo');
        if ($currentLogo && !empty($currentLogo['setting_value'])) {
            $oldLogoPath = $uploadDir . basename($currentLogo['setting_value']);
            if (file_exists($oldLogoPath)) {
                unlink($oldLogoPath);
            }
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $result['success'] = true;
            $result['filename'] = $filename;
        } else {
            $result['error'] = 'Error al subir el archivo';
        }
        
        return $result;
    }
    
    /**
     * Get available timezones
     * @return array
     */
    public function getAvailableTimezones() {
        return [
            'America/New_York' => 'Nueva York (EST/EDT)',
            'America/Chicago' => 'Chicago (CST/CDT)',
            'America/Denver' => 'Denver (MST/MDT)',
            'America/Los_Angeles' => 'Los Ángeles (PST/PDT)',
            'America/Mexico_City' => 'Ciudad de México (CST)',
            'America/Bogota' => 'Bogotá (COT)',
            'America/Lima' => 'Lima (PET)',
            'America/Sao_Paulo' => 'São Paulo (BRT)',
            'Europe/London' => 'Londres (GMT/BST)',
            'Europe/Paris' => 'París (CET/CEST)',
            'Europe/Madrid' => 'Madrid (CET/CEST)',
            'Asia/Tokyo' => 'Tokio (JST)',
            'UTC' => 'UTC'
        ];
    }
    
    /**
     * Get SMTP encryption options
     * @return array
     */
    public function getSmtpEncryptionOptions() {
        return [
            'TLS' => 'TLS',
            'SSL' => 'SSL',
            'NONE' => 'Sin cifrado'
        ];
    }
}