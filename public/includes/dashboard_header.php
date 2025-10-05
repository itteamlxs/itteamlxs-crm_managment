<?php
/**
 * Dashboard Header Component
 * Displays welcome message, user info, and company branding
 */

if (!function_exists('getCurrentUser')) {
    require_once __DIR__ . '/../../core/helpers.php';
    require_once __DIR__ . '/../../core/rbac.php';
    require_once __DIR__ . '/../../config/db.php';
}

requireLogin();
$user = getCurrentUser();
$db = Database::getInstance();

// Get company info
$companyName = 'CRM System';
$companyLogo = '';

try {
    $result = $db->fetch("SELECT setting_value FROM vw_settings WHERE setting_key = 'company_display_name'");
    if ($result) {
        $companyName = $result['setting_value'];
    }
    
    $logoResult = $db->fetch("SELECT setting_value FROM vw_settings WHERE setting_key = 'company_logo'");
    if ($logoResult && !empty($logoResult['setting_value'])) {
        $companyLogo = $logoResult['setting_value'];
    }
} catch (Exception $e) {
    logError("Failed to get company info: " . $e->getMessage());
}
?>

<div class="dashboard-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-speedometer2 me-2"></i>
            <?php echo __('dashboard') ?: 'Dashboard'; ?>
        </h1>
        <p class="mb-0">
            <?php echo __('welcome') ?: 'Welcome'; ?>, 
            <?php echo sanitizeOutput($user['display_name']); ?>! 
            <?php echo __('role') ?: 'Role'; ?>: 
            <?php echo sanitizeOutput(getUserRole()); ?> | 
            <?php echo __('company') ?: 'Company'; ?>: 
            <?php echo sanitizeOutput($companyName); ?>
        </p>
    </div>
    <div class="dashboard-logo">
        <?php if (!empty($companyLogo)): ?>
            <img src="<?php echo sanitizeOutput($companyLogo); ?>" 
                 alt="Company Logo" 
                 style="max-height: 60px; max-width: 150px;" 
                 class="img-fluid">
        <?php else: ?>
            <i class="bi bi-building" style="font-size: 2.5rem; color: #1e3a8a; opacity: 0.7;"></i>
        <?php endif; ?>
    </div>
</div>