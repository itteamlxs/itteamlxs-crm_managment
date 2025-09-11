<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

// Ensure user has permission
requireLogin();
requirePermission('manage_settings');
?>
<!DOCTYPE html>
<html lang="<?= getUserLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitizeOutput(__('settings_management')) ?> - <?= sanitizeOutput(__('app_name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/crm-project/public/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../../../public/includes/nav.php'; ?>
    
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header with Breadcrumbs -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div>
                    <h1 class="h2"><?= __('settings_management') ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= url('dashboard', 'index') ?>">
                                    <i class="bi bi-house-door"></i> <?= __('dashboard') ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-gear"></i> <?= __('settings_management') ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="<?= url('dashboard', 'index') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i>
                            <?= __('back_to_dashboard') ?>
                        </a>
                    </div>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= __('error') ?>:</strong>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                    <li><?= sanitizeOutput($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= sanitizeOutput($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('settings', 'edit') ?>" id="settingsForm">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                <!-- Company Settings -->
                <?php if (isset($settingsByCategory['company'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-building"></i>
                            <?= __('company_settings') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($settingsByCategory['company'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= __('setting_' . $setting['setting_key']) ?>
                                </label>
                                <input type="text" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Email Settings -->
                <?php if (isset($settingsByCategory['email'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-envelope"></i>
                            <?= __('email_settings') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($settingsByCategory['email'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= __('setting_' . $setting['setting_key']) ?>
                                </label>
                                <?php if ($setting['setting_key'] === 'smtp_port'): ?>
                                <input type="number" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>" 
                                       min="1" max="65535">
                                <?php elseif ($setting['setting_key'] === 'smtp_encryption'): ?>
                                <select class="form-select" id="<?= $setting['setting_key'] ?>" name="<?= $setting['setting_key'] ?>">
                                    <?php foreach ($smtpEncryptionOptions as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $setting['setting_value'] === $value ? 'selected' : '' ?>>
                                        <?= sanitizeOutput($label) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php elseif ($setting['setting_key'] === 'smtp_password'): ?>
                                <input type="password" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="" 
                                       placeholder="<?= __('leave_blank_keep_current') ?>">
                                <?php else: ?>
                                <input type="<?= in_array($setting['setting_key'], ['smtp_username', 'from_email']) ? 'email' : 'text' ?>" 
                                       class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quote Settings -->
                <?php if (isset($settingsByCategory['quotes'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text"></i>
                            <?= __('quote_settings') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($settingsByCategory['quotes'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= __('setting_' . $setting['setting_key']) ?>
                                </label>
                                <?php if ($setting['setting_key'] === 'default_tax_rate'): ?>
                                <div class="input-group">
                                    <input type="number" class="form-control" 
                                           id="<?= $setting['setting_key'] ?>" 
                                           name="<?= $setting['setting_key'] ?>" 
                                           value="<?= sanitizeOutput($setting['setting_value']) ?>" 
                                           min="0" max="100" step="0.01">
                                    <span class="input-group-text">%</span>
                                </div>
                                <?php else: ?>
                                <input type="number" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>" 
                                       min="1">
                                <?php endif; ?>
                                <div class="form-text">
                                    <?= __('setting_' . $setting['setting_key'] . '_help') ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Product Settings -->
                <?php if (isset($settingsByCategory['products'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam"></i>
                            <?= __('product_settings') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($settingsByCategory['products'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= __('setting_' . $setting['setting_key']) ?>
                                </label>
                                <input type="number" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>" 
                                       min="1">
                                <div class="form-text">
                                    <?= __('setting_' . $setting['setting_key'] . '_help') ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- System Settings -->
                <?php if (isset($settingsByCategory['system'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-cpu"></i>
                            <?= __('system_settings') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($settingsByCategory['system'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= __('setting_' . $setting['setting_key']) ?>
                                </label>
                                <?php if ($setting['setting_key'] === 'timezone'): ?>
                                <select class="form-select" id="<?= $setting['setting_key'] ?>" name="<?= $setting['setting_key'] ?>">
                                    <?php foreach ($availableTimezones as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $setting['setting_value'] === $value ? 'selected' : '' ?>>
                                        <?= sanitizeOutput($label) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php elseif ($setting['setting_key'] === 'available_languages'): ?>
                                <textarea class="form-control" id="<?= $setting['setting_key'] ?>" 
                                          name="<?= $setting['setting_key'] ?>" rows="3"><?= sanitizeOutput($setting['setting_value']) ?></textarea>
                                <div class="form-text">
                                    <?= __('json_format_example') ?>: ["es", "en", "fr", "zh"]
                                </div>
                                <?php elseif ($setting['setting_key'] === 'backup_time'): ?>
                                <input type="time" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>" 
                                       step="1">
                                <?php else: ?>
                                <input type="text" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Other Settings -->
                <?php if (isset($settingsByCategory['other']) && !empty($settingsByCategory['other'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-three-dots"></i>
                            <?= __('other_settings') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($settingsByCategory['other'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= sanitizeOutput($setting['setting_key']) ?>
                                </label>
                                <input type="text" class="form-control" 
                                       id="<?= $setting['setting_key'] ?>" 
                                       name="<?= $setting['setting_key'] ?>" 
                                       value="<?= sanitizeOutput($setting['setting_value']) ?>">
                                <div class="form-text">
                                    <?= __('last_updated') ?>: <?= formatDate($setting['updated_at']) ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                    <button type="button" class="btn btn-secondary me-md-2" onclick="window.location.href='<?= url('dashboard', 'index') ?>'">
                        <?= __('cancel') ?>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        <?= __('save_settings') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/crm-project/public/assets/js/common.js"></script>
    <script>
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?= __('saving') ?>...';
        });

        function testSmtpConnection() {
            const formData = new FormData();
            formData.append('action', 'test_smtp');
            formData.append('csrf_token', '<?= generateCSRFToken() ?>');
            
            ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption'].forEach(field => {
                formData.append(field, document.getElementById(field).value);
            });

            fetch('<?= url('settings', 'test') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const alertClass = data.success ? 'alert-success' : 'alert-danger';
                const alertHtml = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                document.querySelector('.main-content .container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>