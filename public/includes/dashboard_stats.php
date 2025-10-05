<?php
/**
 * Dashboard Statistics Cards Component
 * Displays key metrics: clients, products, quotes, sales, alerts
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../config/db.php';
}

requireLogin();
$user = getCurrentUser();
$db = Database::getInstance();

// Get dashboard statistics
$stats = [];

try {
    // Total clients (filtered by user if not admin)
    if ($user['is_admin']) {
        $result = $db->fetch("SELECT COUNT(*) as count FROM clients WHERE deleted_at IS NULL");
    } else {
        $result = $db->fetch("SELECT COUNT(*) as count FROM clients WHERE deleted_at IS NULL AND created_by = ?", [$user['user_id']]);
    }
    $stats['total_clients'] = $result['count'] ?? 0;
    
    // Total products
    $result = $db->fetch("SELECT COUNT(*) as count FROM vw_products");
    $stats['total_products'] = $result['count'] ?? 0;
    
    // Total quotes this month (filtered by user if not admin)
    if ($user['is_admin']) {
        $result = $db->fetch(
            "SELECT COUNT(*) as count FROM quotes WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        );
    } else {
        $result = $db->fetch(
            "SELECT COUNT(*) as count FROM quotes WHERE user_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())",
            [$user['user_id']]
        );
    }
    $stats['monthly_quotes'] = $result['count'] ?? 0;
    
    // Total sales this month (filtered by user if not admin)
    if ($user['is_admin']) {
        $result = $db->fetch(
            "SELECT COALESCE(SUM(total_amount), 0) as total FROM quotes WHERE status = 'APPROVED' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        );
    } else {
        $result = $db->fetch(
            "SELECT COALESCE(SUM(total_amount), 0) as total FROM quotes WHERE user_id = ? AND status = 'APPROVED' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())",
            [$user['user_id']]
        );
    }
    $stats['monthly_sales'] = $result['total'] ?? 0;
    
    // Pending quotes (filtered by user if not admin)
    if ($user['is_admin']) {
        $result = $db->fetch("SELECT COUNT(*) as count FROM quotes WHERE status = 'SENT'");
    } else {
        $result = $db->fetch("SELECT COUNT(*) as count FROM quotes WHERE status = 'SENT' AND user_id = ?", [$user['user_id']]);
    }
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
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-content">
            <div>
                <div class="stat-value"><?php echo number_format($stats['total_clients']); ?></div>
                <div class="stat-label"><?php echo __('total_clients') ?: 'Total Clients'; ?></div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div>
                <div class="stat-value"><?php echo number_format($stats['total_products']); ?></div>
                <div class="stat-label"><?php echo __('total_products') ?: 'Total Products'; ?></div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-box"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div>
                <div class="stat-value"><?php echo number_format($stats['monthly_quotes']); ?></div>
                <div class="stat-label"><?php echo __('monthly_quotes') ?: 'Monthly Quotes'; ?></div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-file-text"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div>
                <div class="stat-value"><?php echo formatCurrency($stats['monthly_sales']); ?></div>
                <div class="stat-label"><?php echo __('monthly_sales') ?: 'Monthly Sales'; ?></div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-graph-up"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div>
                <div class="stat-value"><?php echo number_format($stats['pending_quotes']); ?></div>
                <div class="stat-label"><?php echo __('pending_quotes') ?: 'Pending Quotes'; ?></div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div>
                <div class="stat-value"><?php echo number_format($stats['low_stock_products']); ?></div>
                <div class="stat-label"><?php echo __('low_stock_alerts') ?: 'Low Stock Alerts'; ?></div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>
</div>