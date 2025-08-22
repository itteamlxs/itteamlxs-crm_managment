<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('settings') ?: 'Settings'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-gear"></i> <?php echo __('settings') ?: 'System Settings'; ?></h2>
            <a href="<?php echo dashboardUrl(); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <?php echo __('back_to_dashboard') ?: 'Dashboard'; ?>
            </a>
        </div>

        <!-- Messages -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo sanitizeOutput($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Settings Form -->
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="row">
                <div class="col-lg-8">
                    <!-- Company Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-building"></i> <?php echo __('company_settings') ?: 'Company Settings'; ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_display_name" class="form-label"><?php echo __('company_name') ?: 'Company Name'; ?> *</label>
                                        <input type="text" class="form-control" id="company_display_name" name="company_display_name" 
                                               value="<?php echo htmlspecialchars($currentSettings['company_display_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="timezone" class="form-label"><?php echo __('timezone') ?: 'Timezone'; ?></label>
                                        <select class="form-select" id="timezone" name="timezone">
                                            <?php foreach ($timezones as $value => $label): ?>
                                            <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" 
                                                    <?php echo ($currentSettings['timezone'] ?? 'America/New_York') === $value ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="default_tax_rate" class="form-label"><?php echo __('default_tax_rate') ?: 'Default Tax Rate'; ?> (%)</label>
                                        <input type="number" class="form-control" id="default_tax_rate" name="default_tax_rate" 
                                               value="<?php echo htmlspecialchars($currentSettings['default_tax_rate'] ?? '0.00', ENT_QUOTES, 'UTF-8'); ?>" 
                                               step="0.01" min="0" max="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="available_languages" class="form-label"><?php echo __('available_languages') ?: 'Available Languages'; ?></label>
                                        <div class="mt-2">
                                            <?php 
                                            $selectedLanguages = json_decode($currentSettings['available_languages'] ?? '["es"]', true);
                                            if (!is_array($selectedLanguages)) {
                                                $selectedLanguages = ['es'];
                                            }
                                            foreach ($languages as $code => $name): 
                                            ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="lang_<?php echo $code; ?>" 
                                                       name="available_languages[]" value="<?php echo $code; ?>"
                                                       <?php echo in_array($code, $selectedLanguages) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="lang_<?php echo $code; ?>">
                                                    <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-file-text"></i> <?php echo __('quote_settings') ?: 'Quote Settings'; ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="quote_expiry_days" class="form-label"><?php echo __('quote_expiry_days') ?: 'Quote Expiry (Days)'; ?></label>
                                        <input type="number" class="form-control" id="quote_expiry_days" name="quote_expiry_days" 
                                               value="<?php echo htmlspecialchars($currentSettings['quote_expiry_days'] ?? '7', ENT_QUOTES, 'UTF-8'); ?>" 
                                               min="1" max="365">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="quote_expiry_notification_days" class="form-label"><?php echo __('notification_days') ?: 'Notification Days'; ?></label>
                                        <input type="number" class="form-control" id="quote_expiry_notification_days" name="quote_expiry_notification_days" 
                                               value="<?php echo htmlspecialchars($currentSettings['quote_expiry_notification_days'] ?? '3', ENT_QUOTES, 'UTF-8'); ?>" 
                                               min="1" max="30">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="low_stock_threshold" class="form-label"><?php echo __('low_stock_threshold') ?: 'Low Stock Threshold'; ?></label>
                                        <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" 
                                               value="<?php echo htmlspecialchars($currentSettings['low_stock_threshold'] ?? '10', ENT_QUOTES, 'UTF-8'); ?>" 
                                               min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-envelope"></i> <?php echo __('email_settings') ?: 'Email Settings'; ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="smtp_host" class="form-label"><?php echo __('smtp_host') ?: 'SMTP Host'; ?></label>
                                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" 
                                               value="<?php echo htmlspecialchars($currentSettings['smtp_host'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="smtp_port" class="form-label"><?php echo __('smtp_port') ?: 'SMTP Port'; ?></label>
                                        <input type="number" class="form-control" id="smtp_port" name="smtp_port" 
                                               value="<?php echo htmlspecialchars($currentSettings['smtp_port'] ?? '587', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="smtp_encryption" class="form-label"><?php echo __('encryption') ?: 'Encryption'; ?></label>
                                        <select class="form-select" id="smtp_encryption" name="smtp_encryption">
                                            <?php foreach ($encryptionTypes as $value => $label): ?>
                                            <option value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" 
                                                    <?php echo ($currentSettings['smtp_encryption'] ?? 'TLS') === $value ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="smtp_username" class="form-label"><?php echo __('smtp_username') ?: 'SMTP Username'; ?></label>
                                        <input type="text" class="form-control" id="smtp_username" name="smtp_username" 
                                               value="<?php echo htmlspecialchars($currentSettings['smtp_username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="smtp_password" class="form-label"><?php echo __('smtp_password') ?: 'SMTP Password'; ?></label>
                                        <input type="password" class="form-control" id="smtp_password" name="smtp_password" 
                                               placeholder="<?php echo __('leave_blank_to_keep_current') ?: 'Leave blank to keep current'; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="from_email" class="form-label"><?php echo __('from_email') ?: 'From Email'; ?></label>
                                        <input type="email" class="form-control" id="from_email" name="from_email" 
                                               value="<?php echo htmlspecialchars($currentSettings['from_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="from_name" class="form-label"><?php echo __('from_name') ?: 'From Name'; ?></label>
                                        <input type="text" class="form-control" id="from_name" name="from_name" 
                                               value="<?php echo htmlspecialchars($currentSettings['from_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- System Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-tools"></i> <?php echo __('system_settings') ?: 'System Settings'; ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="backup_time" class="form-label"><?php echo __('backup_time') ?: 'Daily Backup Time'; ?></label>
                                <input type="time" class="form-control" id="backup_time" name="backup_time" 
                                       value="<?php echo htmlspecialchars($currentSettings['backup_time'] ?? '02:00:00', ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="form-text"><?php echo __('backup_time_help') ?: 'Time for automatic daily backups'; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Save Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg"></i> <?php echo __('save_settings') ?: 'Save Settings'; ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>