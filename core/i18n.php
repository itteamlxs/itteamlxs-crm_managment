<?php
/**
 * Internationalization System
 * Multi-language support using user.language from DB
 */

class I18n {
    private static $instance = null;
    private $currentLang = 'es';
    private $translations = [];
    private $fallbackLang = 'es';
    
    private function __construct() {
        $this->currentLang = $this->getCurrentLanguage();
        $this->loadTranslations();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get current user language or default
     */
    private function getCurrentLanguage() {
        // If user is logged in, use their language preference
        if (isLoggedIn()) {
            return getUserLanguage();
        }
        
        // Check session language (for non-logged users)
        if (isset($_SESSION['temp_language'])) {
            return $_SESSION['temp_language'];
        }
        
        // Default to Spanish
        return 'es';
    }
    
    /**
     * Load translation files
     */
    private function loadTranslations() {
        $langFile = __DIR__ . "/lang/{$this->currentLang}.php";
        $fallbackFile = __DIR__ . "/lang/{$this->fallbackLang}.php";
        
        // Load fallback first
        if (file_exists($fallbackFile)) {
            $this->translations = include $fallbackFile;
        }
        
        // Override with current language
        if (file_exists($langFile) && $this->currentLang !== $this->fallbackLang) {
            $currentTranslations = include $langFile;
            $this->translations = array_merge($this->translations, $currentTranslations);
        }
    }
    
    /**
     * Get translation for a key
     * @param string $key
     * @param array $params Parameters for string replacement
     * @return string
     */
    public function get($key, $params = []) {
        $translation = $this->translations[$key] ?? $key;
        
        // Replace parameters
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $translation = str_replace("{{$param}}", $value, $translation);
            }
        }
        
        return $translation;
    }
    
    /**
     * Set temporary language for non-logged users
     * @param string $lang
     */
    public function setTempLanguage($lang) {
        $availableLanguages = ['es', 'en', 'fr', 'zh'];
        if (in_array($lang, $availableLanguages)) {
            $_SESSION['temp_language'] = $lang;
            $this->currentLang = $lang;
            $this->loadTranslations();
        }
    }
    
    /**
     * Get available languages from database or fallback
     * @return array
     */
    public function getAvailableLanguages() {
        try {
            $db = Database::getInstance();
            $result = $db->fetch("SELECT setting_value FROM settings WHERE setting_key = 'available_languages'");
            
            if ($result) {
                return json_decode($result['setting_value'], true);
            }
        } catch (Exception $e) {
            logError("Failed to get available languages: " . $e->getMessage());
        }
        
        // Fallback
        return ['es', 'en', 'fr', 'zh'];
    }
    
    /**
     * Get current language
     * @return string
     */
    public function getCurrentLang() {
        return $this->currentLang;
    }
}

/**
 * Helper function for translations
 * @param string $key
 * @param array $params
 * @return string
 */
function __($key, $params = []) {
    return I18n::getInstance()->get($key, $params);
}