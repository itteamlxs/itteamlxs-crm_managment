<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';
require_once __DIR__ . '/../../../config/db.php';

requireLogin();
$user = getCurrentUser();

$companyName = 'CRM System';
try {
    $db = Database::getInstance();
    $result = $db->fetch("SELECT setting_value FROM vw_settings WHERE setting_key = 'company_display_name'");
    if ($result) {
        $companyName = $result['setting_value'];
    }
} catch (Exception $e) {
    logError("Failed to get company name: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('dashboard') ?: 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-speedometer2"></i> <?php echo __('dashboard') ?: 'Dashboard'; ?></h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <h5><?php echo __('welcome') ?: 'Welcome'; ?>, <?php echo sanitizeOutput($user['display_name']); ?>!</h5>
                        <p class="mb-1"><strong><?php echo __('role') ?: 'Role'; ?>:</strong> <?php echo sanitizeOutput(getUserRole()); ?></p>
                        <p class="mb-1"><strong><?php echo __('login_date') ?: 'Login Date'; ?>:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                        <p><strong><?php echo __('company') ?: 'Company'; ?>:</strong> <?php echo sanitizeOutput($companyName); ?></p>
                    </div>
                </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>