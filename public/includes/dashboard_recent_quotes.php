<?php
/**
 * Dashboard Recent Quotes Component
 * Displays recent quotes with status and links
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

// Get recent quotes - filtered by user
$recentQuotes = [];
try {
    if ($user['is_admin']) {
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
    } else {
        $sql = "SELECT 
                    q.quote_id,
                    q.quote_number,
                    c.company_name as client_name,
                    u.username,
                    q.status,
                    q.total_amount,
                    q.issue_date,
                    q.expiry_date
                FROM quotes q
                JOIN clients c ON q.client_id = c.client_id
                JOIN users u ON q.user_id = u.user_id
                WHERE q.user_id = ?
                ORDER BY q.issue_date DESC 
                LIMIT 10";
        $recentQuotes = $db->fetchAll($sql, [$user['user_id']]);
    }
} catch (Exception $e) {
    logError("Recent quotes error: " . $e->getMessage());
    $recentQuotes = [];
}
?>

<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title">
            <i class="bi bi-file-text"></i>
            <?php echo __('recent_quotes') ?: 'Recent Quotes'; ?>
        </h5>
        <?php if (canAccessModule('quotes')): ?>
            <a href="<?php echo url('quotes', 'list'); ?>" class="btn btn-outline-dashboard btn-sm">
                <?php echo __('view_all') ?: 'View All'; ?>
            </a>
        <?php endif; ?>
    </div>
    <?php if (!empty($recentQuotes)): ?>
        <div class="table-responsive">
            <table class="table dashboard-table">
                <thead>
                    <tr>
                        <th><?php echo __('quote_number') ?: 'Quote #'; ?></th>
                        <th><?php echo __('client') ?: 'Client'; ?></th>
                        <th><?php echo __('status') ?: 'Status'; ?></th>
                        <th><?php echo __('amount') ?: 'Amount'; ?></th>
                        <th><?php echo __('date') ?: 'Date'; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentQuotes as $quote): ?>
                        <tr>
                            <td>
                                <?php if (canAccessModule('quotes')): ?>
                                    <a href="<?php echo url('quotes', 'view', ['id' => $quote['quote_id']]); ?>">
                                        <?php echo sanitizeOutput($quote['quote_number']); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo sanitizeOutput($quote['quote_number']); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo sanitizeOutput($quote['client_name']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $quote['status'] === 'APPROVED' ? 'success' : ($quote['status'] === 'SENT' ? 'warning' : 'secondary'); ?>">
                                    <?php echo sanitizeOutput($quote['status']); ?>
                                </span>
                            </td>
                            <td><?php echo formatCurrency($quote['total_amount']); ?></td>
                            <td><?php echo formatDate($quote['issue_date'], 'M j, Y'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="p-4 text-center text-muted">
            <i class="bi bi-file-earmark-text display-4 d-block mb-3"></i>
            <p class="mb-0"><?php echo __('no_recent_quotes') ?: 'No recent quotes'; ?></p>
        </div>
    <?php endif; ?>
</div>