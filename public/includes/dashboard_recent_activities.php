<?php
/**
 * Dashboard Recent Activities Component
 * Displays recent client activities and quote events
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../config/db.php';
}

requireLogin();
$user = getCurrentUser();
$db = Database::getInstance();

// Get recent activities - filtered by user if not admin
$recentActivities = [];
try {
    if ($user['is_admin']) {
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
    } else {
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
                WHERE c.deleted_at IS NULL AND c.created_by = ?
                ORDER BY ca.activity_date DESC
                LIMIT 10";
        $recentActivities = $db->fetchAll($sql, [$user['user_id']]);
    }
} catch (Exception $e) {
    logError("Recent activities error: " . $e->getMessage());
    $recentActivities = [];
}
?>

<div class="chart-card">
    <div class="chart-header">
        <h5 class="chart-title">
            <i class="bi bi-activity"></i>
            <?php echo __('recent_activities') ?: 'Recent Activities'; ?>
        </h5>
    </div>
    <?php if (!empty($recentActivities)): ?>
        <div class="table-responsive">
            <table class="table dashboard-table">
                <thead>
                    <tr>
                        <th><?php echo __('activity') ?: 'Activity'; ?></th>
                        <th><?php echo __('client') ?: 'Client'; ?></th>
                        <th><?php echo __('date') ?: 'Date'; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentActivities as $activity): ?>
                        <tr>
                            <td>
                                <span class="activity-icon <?php echo $activity['activity_type'] === 'QUOTE_APPROVED' ? 'success' : 'primary'; ?>">
                                    <i class="bi bi-<?php echo $activity['activity_type'] === 'QUOTE_APPROVED' ? 'check-circle' : 'file-text'; ?>"></i>
                                </span>
                                <?php echo sanitizeOutput($activity['activity_type']); ?>
                                <?php if (!empty($activity['quote_number'])): ?>
                                    - <?php echo sanitizeOutput($activity['quote_number']); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo sanitizeOutput($activity['company_name']); ?></td>
                            <td><?php echo formatDate($activity['activity_date'], 'M j, Y'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="p-4 text-center text-muted">
            <i class="bi bi-inbox display-4 d-block mb-3"></i>
            <p class="mb-0"><?php echo __('no_recent_activities') ?: 'No recent activities'; ?></p>
        </div>
    <?php endif; ?>
</div>