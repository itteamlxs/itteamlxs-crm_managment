<?php
/**
 * Login Controller
 * Handles login form display and processing
 */

require_once __DIR__ . '/../models/AuthModel.php';

$authModel = new AuthModel();
$error = '';
$success = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token';
    } else {
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Basic validation
        if (empty($username) || empty($password)) {
            $error = 'Username and password are required';
        } else {
            // Rate limiting
            if (!checkRateLimit('login_' . getClientIP(), 5, 300)) {
                $error = 'Too many login attempts. Please try again later.';
            } else {
                $user = $authModel->login($username, $password);
                
                if ($user) {
                    // Start session and store user data
                    $_SESSION['user'] = $user;
                    $_SESSION['last_activity'] = time();
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    // Redirect to dashboard
                    header("Location: http://localhost/crm-project/modules/dashboard/views/dashboard.php");
                    exit;
                } else {
                    $error = 'Invalid username or password';
                }
            }
        }
    }
}

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('/?module=dashboard&action=index');
}

// Include login view
include __DIR__ . '/../views/login.php';