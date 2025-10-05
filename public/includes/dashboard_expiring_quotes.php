<?php
/**
 * Dashboard Expiring Quotes Component
 * Displays quotes that are about to expire
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

// Get expiring quotes - filtered by user
$expiringQuotes = [];
try {
    if ($user['is_admin']) {
        $expiringQuotes = $db->fetchAll("SELECT * FROM vw_expiring_quotes ORDER BY days_until_expiry ASC LIMIT 5");
    } else {
        $sql = "SELECT 
                    q.quote_id,
                    q.quote_number,
                    q.client_id,
                    c.company_name as client_name,
                    q.expiry_date,
                    DATEDIFF(q.expiry_date, CURDATE()) as days_until_expiry
                FROM quotes q
                JOIN clients c ON q.client_id = c.client_id
                JOIN settings s ON s.setting_key = 'quote_expiry_notification_days'
                WHERE q.status = 'SENT' 
                AND q.user_id = ?
                AND q.expiry_date <= DATE_ADD(CURDATE(), INTERVAL CAST(s.setting_value AS UNSIGNED) DAY)
                ORDER BY days_until_expiry ASC
                LIMIT 5";
        $expiringQuotes = $db->fetchAll($sql, [$user['user_id']]);
    }
} catch (Exception $e) {
    logError("Expiring quotes error: " . $e->getMessage());
    $expiringQuotes = [];
}
?>

<?php if (!empty($expiringQuotes)): ?>
<div class="sidebar-card mb-3">
    <h6 class="sidebar-card-title">
        <i class="bi bi-clock-history"></i>
        <?php echo __('expiring_quotes') ?: 'Expiring Quotes'; ?>
    </h6>
    <?php foreach ($expiringQuotes as $quote): ?>
        <div class="expiring-quote">
            <div class="quote-info">
                <div class="quote-details">
                    <h6>
                        <?php if (canAccessModule('quotes')): ?>
                            <a href="<?php echo url('quotes', 'view', ['id' => $quote['quote_id']]); ?>">
                                <?php echo sanitizeOutput($quote['quote_number']); ?>
                            </a>
                        <?php else: ?>
                            <?php echo sanitizeOutput($quote['quote_number']); ?>
                        <?php endif; ?>
                    </h6>
                    <small><?php echo sanitizeOutput($quote['client_name']); ?></small>
                </div>
                <span class="quote-badge badge-<?php echo $quote['days_until_expiry'] <= 1 ? 'danger' : 'warning'; ?>">
                    <?php echo $quote['days_until_expiry']; ?> <?php echo __('days') ?: 'days'; ?>
                </span>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>