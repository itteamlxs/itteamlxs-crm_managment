<?php
/**
 * Dashboard Controller
 * Handles dashboard data loading and profile editing
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';

requireLogin();
$user = getCurrentUser();

// Initialize dashboard variables
$forcePasswordChange = $user['force_password_change'] ?? false;
$salesTrends = [];
$statusDistribution = [];
$companyName = 'CRM System';
$dashboardStats = [];
$recentActivity = [];
$expiringQuotes = [];
$topClients = [];
$topProducts = [];

// Handle AJAX password change request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjaxRequest()) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'change_password') {
        try {
            // Validate CSRF token
            if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
                jsonResponse(['error' => __('invalid_security_token')], 400);
            }
            
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate inputs
            if (empty($newPassword) || empty($confirmPassword)) {
                jsonResponse(['error' => __('all_fields_required')], 400);
            }
            
            if ($newPassword !== $confirmPassword) {
                jsonResponse(['error' => __('passwords_do_not_match')], 400);
            }
            
            // Validate new password strength
            $passwordValidation = validatePassword($newPassword);
            if (!$passwordValidation['valid']) {
                jsonResponse(['error' => implode('. ', $passwordValidation['errors'])], 400);
            }
            
            // Update password in database
            $db = Database::getInstance();
            $newPasswordHash = hashPassword($newPassword);
            
            $db->beginTransaction();
            
            try {
                // Update password and remove force_password_change flag
                $sql = "UPDATE users SET 
                        password_hash = ?, 
                        force_password_change = FALSE,
                        updated_at = NOW()
                        WHERE user_id = ?";
                
                $db->execute($sql, [$newPasswordHash, $user['user_id']]);
                
                // Log security event
                logSecurityEvent('PASSWORD_CHANGED', [
                    'user_id' => $user['user_id'],
                    'first_time' => $user['force_password_change']
                ]);
                
                $db->commit();
                
                // Update session data
                $_SESSION['user']['force_password_change'] = false;
                
                jsonResponse([
                    'success' => true,
                    'message' => __('password_changed_successfully')
                ]);
                
            } catch (Exception $e) {
                $db->rollback();
                logError("Password change failed for user {$user['user_id']}: " . $e->getMessage());
                jsonResponse(['error' => __('password_change_failed')], 500);
            }
            
        } catch (Exception $e) {
            logError("Password change error: " . $e->getMessage());
            jsonResponse(['error' => __('password_change_failed')], 500);
        }
        
        return; // Exit after handling AJAX request
    }
}

// Handle regular form submission for profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isAjaxRequest()) {
    try {
        // Validate CSRF token
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            $error = __('invalid_security_token');
        } else {
            $username = sanitizeInput($_POST['username'] ?? '');
            $email = sanitizeInput($_POST['email'] ?? '');
            $displayName = sanitizeInput($_POST['display_name'] ?? '');
            $language = sanitizeInput($_POST['language'] ?? 'es');
            
            // Validate required fields
            if (empty($username) || empty($email) || empty($displayName)) {
                $error = __('please_fill_required_fields');
            } elseif (!validateEmail($email)) {
                $error = __('invalid_email_format');
            } elseif (!validateUsername($username)) {
                $error = __('invalid_username_format');
            } else {
                $db = Database::getInstance();
                
                // Check if username/email already exists (excluding current user)
                $existingUser = $db->fetch(
                    "SELECT user_id FROM users WHERE (username = ? OR email = ?) AND user_id != ?",
                    [$username, $email, $user['user_id']]
                );
                
                if ($existingUser) {
                    $error = __('username_or_email_already_exists');
                } else {
                    // Handle file upload if provided
                    $profilePicture = $user['profile_picture']; // Keep existing by default
                    
                    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                        $uploadResult = validateFileUpload($_FILES['profile_picture']);
                        
                        if ($uploadResult['valid']) {
                            $uploadPath = UPLOAD_DIR . $uploadResult['filename'];
                            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                                // Remove old profile picture
                                if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                                    unlink($user['profile_picture']);
                                }
                                $profilePicture = 'public/uploads/' . $uploadResult['filename'];
                            }
                        } else {
                            $error = $uploadResult['error'];
                        }
                    }
                    
                    if (empty($error)) {
                        $db->beginTransaction();
                        
                        try {
                            // Update user profile
                            $sql = "UPDATE users SET 
                                    username = ?, 
                                    email = ?, 
                                    display_name = ?, 
                                    profile_picture = ?,
                                    language = ?,
                                    updated_at = NOW()
                                    WHERE user_id = ?";
                            
                            $db->execute($sql, [
                                $username, 
                                $email, 
                                $displayName, 
                                $profilePicture,
                                $language,
                                $user['user_id']
                            ]);
                            
                            // Update session data
                            $_SESSION['user']['username'] = $username;
                            $_SESSION['user']['email'] = $email;
                            $_SESSION['user']['display_name'] = $displayName;
                            $_SESSION['user']['profile_picture'] = $profilePicture;
                            $_SESSION['user']['language'] = $language;
                            
                            // Log security event
                            logSecurityEvent('USER_UPDATED', ['target_user_id' => $user['user_id']]);
                            
                            $db->commit();
                            $success = __('profile_updated_successfully');
                            
                        } catch (Exception $e) {
                            $db->rollback();
                            logError("Profile update failed for user {$user['user_id']}: " . $e->getMessage());
                            $error = __('profile_update_failed');
                        }
                    }
                }
            }
        }
        
    } catch (Exception $e) {
        logError("Profile update error: " . $e->getMessage());
        $error = __('profile_update_failed');
    }
}

// Load Dashboard Data
try {
    $db = Database::getInstance();
    
    // Get company name
    $result = $db->fetch("SELECT setting_value FROM vw_settings WHERE setting_key = 'company_display_name'");
    if ($result) {
        $companyName = $result['setting_value'];
    }
    
    // Dashboard Statistics
    $statsQueries = [
        'total_quotes' => "SELECT COUNT(*) as count FROM quotes",
        'total_clients' => "SELECT COUNT(*) as count FROM vw_clients",
        'pending_quotes' => "SELECT COUNT(*) as count FROM quotes WHERE status = 'SENT'",
        'total_revenue' => "SELECT COALESCE(SUM(total_amount), 0) as amount FROM quotes WHERE status = 'APPROVED' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())",
        'conversion_rate' => "SELECT ROUND((SELECT COUNT(*) FROM quotes WHERE status = 'APPROVED') * 100.0 / NULLIF((SELECT COUNT(*) FROM quotes WHERE status IN ('SENT', 'APPROVED', 'REJECTED')), 0), 1) as rate",
        'avg_deal_size' => "SELECT COALESCE(AVG(total_amount), 0) as avg_amount FROM quotes WHERE status = 'APPROVED'"
    ];
    
    foreach ($statsQueries as $key => $query) {
        $result = $db->fetch($query);
        $dashboardStats[$key] = $result[array_key_first($result)] ?? 0;
    }
    
    // Recent Activity from audit_logs
    $recentActivity = $db->fetchAll("
        SELECT a.action, a.entity_type, a.entity_id, a.created_at, u.username, u.display_name,
               CASE 
                   WHEN a.action = 'INSERT' AND a.entity_type = 'QUOTE' THEN 'Nueva cotización creada'
                   WHEN a.action = 'INSERT' AND a.entity_type = 'USER' THEN 'Nuevo cliente registrado'
                   WHEN a.action = 'UPDATE' AND a.entity_type = 'QUOTE' THEN 'Cotización actualizada'
                   WHEN a.action = 'STOCK_UPDATE' THEN 'Stock actualizado'
                   ELSE CONCAT(a.action, ' en ', a.entity_type)
               END as activity_description,
               CASE 
                   WHEN a.action = 'INSERT' THEN 'success'
                   WHEN a.action = 'UPDATE' THEN 'primary'
                   WHEN a.action = 'STOCK_UPDATE' THEN 'warning'
                   ELSE 'secondary'
               END as activity_type
        FROM audit_logs a
        LEFT JOIN users u ON a.user_id = u.user_id
        ORDER BY a.created_at DESC
        LIMIT 10
    ");
    
    // Expiring Quotes
    $expiringQuotes = $db->fetchAll("
        SELECT quote_id, quote_number, company_name as client_name, expiry_date,
               DATEDIFF(expiry_date, CURDATE()) as days_until_expiry
        FROM vw_expiring_quotes
        ORDER BY days_until_expiry ASC
        LIMIT 5
    ");
    
    // Top Clients by total spend
    $topClients = $db->fetchAll("
        SELECT client_id, company_name, total_spend, purchase_count
        FROM vw_top_clients
        ORDER BY total_spend DESC
        LIMIT 5
    ");
    
    // Top Products by quantity sold
    $topProducts = $db->fetchAll("
        SELECT product_id, product_name, sku, total_sold, 
               (total_sold * (SELECT AVG(unit_price) FROM quote_items qi WHERE qi.product_id = pp.product_id)) as revenue
        FROM vw_product_performance pp
        WHERE total_sold IS NOT NULL
        ORDER BY total_sold DESC
        LIMIT 5
    ");
    
    // Sales Trends for chart (last 6 months)
    $salesTrends = $db->fetchAll("
        SELECT month, total_amount, total_quotes
        FROM vw_sales_trends
        ORDER BY month DESC
        LIMIT 6
    ");
    $salesTrends = array_reverse($salesTrends);
    
    // Status Distribution for pie chart
    $statusDistribution = $db->fetchAll("
        SELECT status,
               COUNT(*) as count,
               CASE 
                   WHEN status = 'APPROVED' THEN 'Aprobadas'
                   WHEN status = 'SENT' THEN 'Pendientes'
                   WHEN status = 'REJECTED' THEN 'Rechazadas'
                   WHEN status = 'DRAFT' THEN 'Borrador'
                   ELSE status
               END as status_label
        FROM quotes
        GROUP BY status
    ");
    
} catch (Exception $e) {
    logError("Dashboard data loading failed: " . $e->getMessage());
    // Initialize with empty arrays to prevent errors
    $dashboardStats = [
        'total_quotes' => 0,
        'total_clients' => 0,
        'pending_quotes' => 0,
        'total_revenue' => 0,
        'conversion_rate' => 0,
        'avg_deal_size' => 0
    ];
    $recentActivity = [];
    $expiringQuotes = [];
    $topClients = [];
    $topProducts = [];
    $salesTrends = [];
    $statusDistribution = [];
}

// Get user ID from URL for editing (admin function)
$editUserId = (int)($_GET['id'] ?? $user['user_id']);
$isEditingOther = $editUserId !== $user['user_id'];

// Check permissions for editing other users
if ($isEditingOther && !$user['is_admin']) {
    redirect(url('users', 'profile'));
}

// Get user data to edit
try {
    if ($isEditingOther) {
        $editUser = $db->fetch("SELECT * FROM vw_user_profile WHERE user_id = ?", [$editUserId]);
        if (!$editUser) {
            redirect(url('users', 'list'));
        }
    } else {
        $editUser = $user;
        // Get role name for current user
        $roleResult = $db->fetch("SELECT role_name FROM roles WHERE role_id = ?", [$user['role_id']]);
        $editUser['role_name'] = $roleResult['role_name'] ?? '';
    }
    
    // Get available roles for admin
    $availableRoles = [];
    if ($user['is_admin']) {
        $availableRoles = $db->fetchAll("SELECT role_id, role_name FROM roles ORDER BY role_name");
    }
    
} catch (Exception $e) {
    logError("Failed to load user data: " . $e->getMessage());
    $error = __('failed_to_load_user_data');
}

// Include dashboard view
include __DIR__ . '/../views/dashboard.php';
?>