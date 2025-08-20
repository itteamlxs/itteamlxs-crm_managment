<?php
/**
 * Logout Controller
 * Handles user logout and session cleanup
 */

require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('/crm-project/public/index.php?module=auth&action=login');
}

// Log security event
logSecurityEvent('USER_LOGOUT', [
    'user_id' => getCurrentUser()['user_id'] ?? null,
    'username' => getCurrentUser()['username'] ?? null
]);

// Destroy session
session_unset();
session_destroy();

// Start new session for flash message
session_start();
$_SESSION['logout_success'] = __('logout_success');

// Redirect to login
redirect('/crm-project/public/index.php?module=auth&action=login');