<?php
/**
 * Dashboard Top Clients Component
 * Displays top 5 clients by total spend
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../core/url_helper.php';
    require_once __DIR__ . '/../../config/db.php';
}

requireLogin();
$user = getCurrentUser();
$db = Database::getInstance();

// Get top clients - filtered by user
$topClients = [];
try {
    if ($user['is_admin']) {
        $topClients = $db->fetchAll("SELECT * FROM vw_top_clients LIMIT 5");
    } else {
        $sql = "SELECT 
                    c.client_id,
                    c.company_name,
                    SUM(q.total_amount) as total_spend,
                    COUNT(q.quote_id) as purchase_count,
                    RANK() OVER (ORDER BY SUM(q.total_amount) DESC) as rank
                FROM clients c
                JOIN quotes q ON c.client_id = q.client_id
                WHERE q.status = 'APPROVED' 
                AND c.created_by = ?
                AND c.deleted_at IS NULL
                GROUP BY c.client_id, c.company_name
                ORDER BY total_spend DESC
                LIMIT 5";
        $topClients = $db->fetchAll($sql, [$user['user_id']]);
    }
} catch (Exception $e) {
    logError("Top clients error: " . $e->getMessage());
    $topClients = [];
}
?>

<?php if (!empty($topClients)): ?>
<div class="sidebar-card mb-3">
    <h6 class="sidebar-card-title">
        <i class="bi bi-star"></i>
        <?php echo __('top_clients') ?: 'Top Clients'; ?>
    </h6>
    <?php foreach ($topClients as $client): ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <strong>
                    <?php if (canAccessModule('clients')): ?>
                        <a href="<?php echo url('clients', 'edit', ['id' => $client['client_id']]); ?>" class="text-decoration-none text-dark">
                            <?php echo sanitizeOutput($client['company_name']); ?>
                        </a>
                    <?php else: ?>
                        <?php echo sanitizeOutput($client['company_name']); ?>
                    <?php endif; ?>
                </strong>
                <br>
                <small class="text-muted">
                    <?php echo $client['purchase_count']; ?> <?php echo __('orders') ?: 'orders'; ?>
                </small>
            </div>
            <div class="text-end">
                <span class="fw-bold"><?php echo formatCurrency($client['total_spend']); ?></span>
                <br>
                <small class="text-muted"># <?php echo $client['rank']; ?></small>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (canAccessModule('clients')): ?>
        <div class="mt-2">
            <a href="<?php echo url('clients', 'list'); ?>" class="btn btn-outline-dashboard btn-sm w-100">
                <i class="bi bi-building"></i> <?php echo __('view_all') ?: 'View All Clients'; ?>
            </a>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>