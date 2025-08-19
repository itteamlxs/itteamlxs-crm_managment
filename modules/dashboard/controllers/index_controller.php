<?php
/**
 * Dashboard Controller
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';

// Require login
requireLogin();

$user = getCurrentUser();

// Include dashboard view
include __DIR__ . '/../views/index.php';