<?php
/**
 * Dashboard Controller
 */

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../config/db.php';

// Require login
requireLogin();

$user = getCurrentUser();
$db = Database::getInstance();

// NUEVO: Verificar si el usuario debe cambiar su contraseña
$forcePasswordChange = false;
try {
    $sql = "SELECT force_password_change FROM users WHERE user_id = ?";
    $result = $db->fetch($sql, [$user['user_id']]);
    if ($result) {
        $forcePasswordChange = (bool)$result['force_password_change'];
    }
} catch (Exception $e) {
    logError("Error checking force_password_change: " . $e->getMessage());
}

// Get dashboard statistics
$stats = [];

try {
    // Total clients - CORREGIDO: usar vw_clients como en el original
    $result = $db->fetch("SELECT COUNT(*) as count FROM vw_clients");
    $stats['total_clients'] = $result['count'] ?? 0;
    
    // Total products
    $result = $db->fetch("SELECT COUNT(*) as count FROM vw_products");
    $stats['total_products'] = $result['count'] ?? 0;
    
    // Total quotes this month
    $result = $db->fetch(
        "SELECT COUNT(*) as count FROM quotes WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())"
    );
    $stats['monthly_quotes'] = $result['count'] ?? 0;
    
    // Total sales this month (approved quotes)
    $result = $db->fetch(
        "SELECT COALESCE(SUM(total_amount), 0) as total FROM quotes WHERE status = 'APPROVED' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())"
    );
    $stats['monthly_sales'] = $result['total'] ?? 0;
    
    // Pending quotes
    $result = $db->fetch("SELECT COUNT(*) as count FROM quotes WHERE status = 'SENT'");
    $stats['pending_quotes'] = $result['count'] ?? 0;
    
    // Low stock products
    $result = $db->fetch("SELECT COUNT(*) as count FROM vw_low_stock_products");
    $stats['low_stock_products'] = $result['count'] ?? 0;
    
} catch (Exception $e) {
    logError("Dashboard stats error: " . $e->getMessage());
    $stats = [
        'total_clients' => 0,
        'total_products' => 0,
        'monthly_quotes' => 0,
        'monthly_sales' => 0,
        'pending_quotes' => 0,
        'low_stock_products' => 0
    ];
}

// Get recent activities
$recentActivities = [];
try {
    $sql = "SELECT 
                ca.activity_id,
                ca.client_id,
                ca.activity_type,
                ca.activity_date,
                ca.details,
                c.company_name,
                q.quote_number
            FROM client_activities ca
            JOIN clients c ON ca.client_id = c.client_id
            LEFT JOIN quotes q ON ca.quote_id = q.quote_id
            WHERE c.deleted_at IS NULL
            ORDER BY ca.activity_date DESC
            LIMIT 10";
    
    $recentActivities = $db->fetchAll($sql);
} catch (Exception $e) {
    logError("Recent activities error: " . $e->getMessage());
    $recentActivities = [];
}

// Get expiring quotes
$expiringQuotes = [];
try {
    $expiringQuotes = $db->fetchAll("SELECT * FROM vw_expiring_quotes ORDER BY days_until_expiry ASC LIMIT 5");
} catch (Exception $e) {
    logError("Expiring quotes error: " . $e->getMessage());
    $expiringQuotes = [];
}

// Get low stock products
$lowStockProducts = [];
try {
    $lowStockProducts = $db->fetchAll("SELECT * FROM vw_low_stock_products ORDER BY stock_quantity ASC LIMIT 5");
} catch (Exception $e) {
    logError("Low stock products error: " . $e->getMessage());
    $lowStockProducts = [];
}

// Get recent quotes
$recentQuotes = [];
try {
    $sql = "SELECT 
                quote_id,
                quote_number,
                client_name,
                username,
                status,
                total_amount,
                issue_date,
                expiry_date
            FROM vw_quotes 
            ORDER BY issue_date DESC 
            LIMIT 10";
    
    $recentQuotes = $db->fetchAll($sql);
} catch (Exception $e) {
    logError("Recent quotes error: " . $e->getMessage());
    $recentQuotes = [];
}

// Get top clients
$topClients = [];
try {
    $topClients = $db->fetchAll("SELECT * FROM vw_top_clients LIMIT 5");
} catch (Exception $e) {
    logError("Top clients error: " . $e->getMessage());
    $topClients = [];
}

// Include dashboard view
include __DIR__ . '/../views/dashboard.php';