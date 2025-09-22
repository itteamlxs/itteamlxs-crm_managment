<?php
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../core/helpers.php';
require_once __DIR__ . '/../../../core/security.php';
require_once __DIR__ . '/../../../core/rbac.php';
require_once __DIR__ . '/../../../core/url_helper.php';

requireLogin();
requirePermission('edit_client');
?>
<!DOCTYPE html>
<html lang="<?= getUserLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('edit_client'); ?> - <?= APP_NAME; ?></title>
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
                    <h1 class="h2">
                        <i class="bi bi-person-gear"></i> <?= __('edit_client'); ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?= url('dashboard', 'index') ?>">
                                    <i class="bi bi-house-door"></i> <?= __('dashboard') ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?= url('clients', 'list') ?>">
                                    <i class="bi bi-building"></i> <?= __('clients') ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="bi bi-person-gear"></i> <?= __('edit_client') ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="<?= url('clients', 'list'); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> <?= __('back_to_list'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Success Message -->
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i><?= sanitizeOutput($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Error Messages -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= sanitizeOutput($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Edit Client Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><?= __('client_details'); ?></h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="editClientForm">
                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">

                                <!-- Company Information -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="company_name" class="form-label">
                                            <?= __('company_name'); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" 
                                               value="<?= sanitizeOutput($client['company_name']); ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="contact_name" class="form-label">
                                            <?= __('contact_name'); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                               value="<?= sanitizeOutput($client['contact_name']); ?>" required>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            <?= __('email'); ?> <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= sanitizeOutput($client['email']); ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label"><?= __('phone'); ?></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?= sanitizeOutput($client['phone'] ?? ''); ?>">
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="tax_id" class="form-label"><?= __('tax_id'); ?></label>
                                        <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                               value="<?= sanitizeOutput($client['tax_id'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label"><?= __('created_at'); ?></label>
                                        <input type="text" class="form-control" 
                                               value="<?= formatDate($client['created_at'], 'Y-m-d H:i'); ?>" readonly>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label"><?= __('address'); ?></label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?= sanitizeOutput($client['address'] ?? ''); ?></textarea>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= url('clients', 'list'); ?>" class="btn btn-secondary">
                                        <?= __('cancel'); ?>
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i><?= __('update_client'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Client Information Sidebar -->
                <div class="col-lg-4">
                    <!-- Client Activity -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><?= __('recent_activity'); ?></h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($activities)): ?>
                                <div class="timeline">
                                    <?php foreach ($activities as $activity): ?>
                                        <div class="timeline-item mb-3">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-circle-fill text-primary" style="font-size: 8px;"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="mb-1 small">
                                                        <strong><?= sanitizeOutput($activity['activity_type']); ?></strong>
                                                    </p>
                                                    <?php if (!empty($activity['quote_number'])): ?>
                                                        <p class="mb-1 text-muted small">
                                                            <?= __('quote'); ?>: <?= sanitizeOutput($activity['quote_number']); ?>
                                                            <?php if (!empty($activity['total_amount'])): ?>
                                                                - <?= formatCurrency($activity['total_amount']); ?>
                                                            <?php endif; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <p class="mb-0 text-muted small">
                                                        <?= formatDate($activity['activity_date'], 'Y-m-d H:i'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small"><?= __('no_activity_found'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Quotes -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><?= __('recent_quotes'); ?></h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($quotes)): ?>
                                <?php foreach ($quotes as $quote): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="small fw-bold"><?= sanitizeOutput($quote['quote_number']); ?></span>
                                            <br>
                                            <span class="badge bg-<?= $quote['status'] == 'APPROVED' ? 'success' : ($quote['status'] == 'SENT' ? 'warning' : 'secondary'); ?>">
                                                <?= sanitizeOutput($quote['status']); ?>
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <div class="small fw-bold"><?= formatCurrency($quote['total_amount']); ?></div>
                                            <div class="small text-muted"><?= formatDate($quote['issue_date'], 'Y-m-d'); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted small"><?= __('no_quotes_found'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editClientForm');
            
            // Basic client-side validation
            form.addEventListener('submit', function(e) {
                const companyName = document.getElementById('company_name').value.trim();
                const contactName = document.getElementById('contact_name').value.trim();
                const email = document.getElementById('email').value.trim();
                
                if (!companyName || !contactName || !email) {
                    e.preventDefault();
                    alert('<?= __('please_fill_required_fields'); ?>');
                    return;
                }
                
                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('<?= __('invalid_email_format'); ?>');
                    return;
                }
            });
        });
    </script>
</body>
</html>