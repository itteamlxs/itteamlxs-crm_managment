<?php
/**
 * URL Helper Functions
 * Centralized URL generation to prevent routing issues
 */

/**
 * Generate application URL
 * @param string $module
 * @param string $action
 * @param array $params
 * @return string
 */
function url($module = '', $action = '', $params = []) {
    // Base path - adjust this based on your server configuration
    $basePath = '/crm-project/public/index.php';
    
    // If accessed from root, use different path
    if (isset($_SERVER['SCRIPT_NAME']) && strpos($_SERVER['SCRIPT_NAME'], 'index.php') !== false) {
        $basePath = $_SERVER['SCRIPT_NAME'];
    }
    
    $url = $basePath;
    
    if (!empty($module)) {
        $url .= '?module=' . urlencode($module);
        
        if (!empty($action)) {
            $url .= '&action=' . urlencode($action);
        }
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= '&' . urlencode($key) . '=' . urlencode($value);
            }
        }
    }
    
    return $url;
}

/**
 * Generate dashboard URL
 * @return string
 */
function dashboardUrl() {
    return url('dashboard', 'index');
}

/**
 * Generate login URL
 * @return string
 */
function loginUrl() {
    return url('auth', 'login');
}

/**
 * Generate logout URL
 * @return string
 */
function logoutUrl() {
    return url('auth', 'logout');
}

/**
 * Generate users list URL
 * @return string
 */
function usersListUrl() {
    return url('users', 'list');
}

/**
 * Generate user edit URL
 * @param int $userId
 * @return string
 */
function userEditUrl($userId) {
    return url('users', 'edit', ['id' => $userId]);
}