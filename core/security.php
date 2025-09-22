<?php
/**
 * Security Functions
 * Anti-XSS, anti-LFI, input validation, sanitization
 */

require_once __DIR__ . '/helpers.php';

/**
 * Sanitize input to prevent XSS
 * @param mixed $input
 * @return mixed
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    
    if (is_string($input)) {
        // Remove null bytes and trim
        $input = str_replace(chr(0), '', $input);
        $input = trim($input);
        
        // Basic XSS prevention
        $input = strip_tags($input, '<p><br><strong><em><ul><ol><li>');
        return $input;
    }
    
    return $input;
}

/**
 * Validate file upload and prevent LFI
 * @param array $file $_FILES array element
 * @param array $allowedTypes
 * @return array ['valid' => bool, 'error' => string, 'filename' => string]
 */
function validateFileUpload($file, $allowedTypes = ALLOWED_EXTENSIONS) {
    $result = ['valid' => false, 'error' => '', 'filename' => ''];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'File upload error: ' . $file['error'];
        return $result;
    }
    
    // Check file size
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        $result['error'] = 'File too large. Maximum size: ' . formatBytes(UPLOAD_MAX_SIZE);
        return $result;
    }
    
    // Validate file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedTypes)) {
        $result['error'] = 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes);
        return $result;
    }
    
    // Validate MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
    ];
    
    if (!isset($allowedMimes[$extension]) || $mimeType !== $allowedMimes[$extension]) {
        $result['error'] = 'Invalid file content';
        return $result;
    }
    
    // Generate secure filename
    $result['filename'] = generateUniqueFilename($file['name']);
    $result['valid'] = true;
    
    return $result;
}

/**
 * Secure file path validation to prevent LFI
 * @param string $path
 * @param string $allowedDir
 * @return bool
 */
function validateFilePath($path, $allowedDir) {
    $realPath = realpath($path);
    $allowedPath = realpath($allowedDir);
    
    return $realPath !== false && 
           $allowedPath !== false && 
           strpos($realPath, $allowedPath) === 0;
}

/**
 * Validate and sanitize SQL ORDER BY clause
 * @param string $orderBy
 * @param array $allowedColumns
 * @return string
 */
function sanitizeOrderBy($orderBy, $allowedColumns = []) {
    if (empty($allowedColumns)) {
        return 'created_at DESC';
    }
    
    $parts = explode(' ', trim($orderBy));
    $column = $parts[0] ?? '';
    $direction = strtoupper($parts[1] ?? 'ASC');
    
    if (!in_array($column, $allowedColumns)) {
        $column = $allowedColumns[0];
    }
    
    if (!in_array($direction, ['ASC', 'DESC'])) {
        $direction = 'ASC';
    }
    
    return $column . ' ' . $direction;
}

/**
 * Validate pagination parameters
 * @param int $page
 * @param int $limit
 * @param int $maxLimit
 * @return array
 */
function validatePagination($page = 1, $limit = 10, $maxLimit = 100) {
    $page = max(1, (int)$page);
    $limit = min($maxLimit, max(1, (int)$limit));
    $offset = ($page - 1) * $limit;
    
    return [
        'page' => $page,
        'limit' => $limit,
        'offset' => $offset
    ];
}

/**
 * Rate limiting check
 * @param string $key
 * @param int $maxAttempts
 * @param int $timeWindow
 * @return bool
 */
function checkRateLimit($key, $maxAttempts = 5, $timeWindow = 300) {
    $sessionKey = 'rate_limit_' . $key;
    $now = time();
    
    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = ['count' => 1, 'first_attempt' => $now];
        return true;
    }
    
    $data = $_SESSION[$sessionKey];
    
    // Reset if time window expired
    if ($now - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$sessionKey] = ['count' => 1, 'first_attempt' => $now];
        return true;
    }
    
    // Check if limit exceeded
    if ($data['count'] >= $maxAttempts) {
        return false;
    }
    
    // Increment counter
    $_SESSION[$sessionKey]['count']++;
    return true;
}

/**
 * Validate username format
 * @param string $username
 * @return bool
 */
function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username);
}

/**
 * Validate password strength
 * @param string $password
 * @return array ['valid' => bool, 'errors' => array]
 */
function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Clean string for filename use
 * @param string $string
 * @return string
 */
function cleanFilename($string) {
    $string = preg_replace('/[^a-zA-Z0-9._-]/', '_', $string);
    return substr($string, 0, 100);
}

/**
 * Generate secure token
 * @param int $length
 * @return string
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}