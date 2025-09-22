<?php
/**
 * Auth Controller
 * Handles password reset (disabled) and other auth functions
 */

require_once __DIR__ . '/../../../core/helpers.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('/crm-project/public/index.php?module=dashboard&action=index');
}

// Include reset view (shows contact admin message)
include __DIR__ . '/../views/reset.php';