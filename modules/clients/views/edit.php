<?php
require_once __DIR__ . '/../../../core/url_helper.php';
?>
<!DOCTYPE html>
<html lang="<?php echo sanitizeOutput(getUserLanguage()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeOutput(__('app_name') ?: APP_NAME); ?> - <?php echo __('edit_client') ?: 'Edit Client'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-pencil"></i> <?php echo __('edit_client') ?: 'Edit Client'; ?></h2>
                <p class="text-muted mb-0"><?php echo sanitizeOutput($client['company_name']); ?></p>
            </div>
            <a href="<?php echo url('clients', 'list'); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> <?php echo __('back_to_list') ?: 'Back to List'; ?>
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

        <div class="row">
            <div class="col-lg-8">
                <!-- Client Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo __('client_details') ?: 'Client Details'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="clientForm">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="row">
                                <!-- Company Information -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3"><?php echo __('company_information') ?: 'Company Information'; ?></h6>
                                    
                                    <div class="mb-3">
                                        <label for="company_name" class="form-label"><?php echo __('company_name') ?: 'Company Name'; ?> *</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" 
                                               value="<?php echo sanitizeOutput($client['company_name']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tax_id" class="form-label"><?php echo __('tax_id') ?: 'Tax ID'; ?></label>
                                        <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                               value="<?php echo sanitizeOutput($client['tax_id'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="address" class="form-label"><?php echo __('address') ?: 'Address'; ?></label>
                                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo sanitizeOutput($client['address'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                                
                                <!-- Contact Information -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3"><?php echo __('contact_information') ?: 'Contact Information'; ?></h6>
                                    
                                    <div class="mb-3">
                                        <label for="contact_name" class="form-label"><?php echo __('contact_name') ?: 'Contact Name'; ?> *</label>
                                        <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                               value="<?php echo sanitizeOutput($client['contact_name']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label"><?php echo __('email') ?: 'Email'; ?> *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo sanitizeOutput($client['email']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="phone" class="form-label"><?php echo __('phone') ?: 'Phone'; ?></label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo sanitizeOutput($client['phone'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end pt-3 border-top">
                                <a href="<?php echo url('clients', 'list'); ?>" class="btn btn-secondary me-2">
                                    <?php echo __('cancel') ?: 'Cancel'; ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> <?php echo __('update_client') ?: 'Update Client'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Client Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo __('client_info') ?: 'Client Info'; ?></h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><small class="text-muted"><?php echo __('created_at') ?: 'Created'; ?>:</small><br>
                           <?php echo formatDate($client['created_at'], 'Y-m-d H:i'); ?></p>
                        
                        <?php if ($client['updated_at']): ?>
                        <p class="mb-2"><small class="text-muted"><?php echo __('updated_at') ?: 'Updated'; ?>:</small><br>
                           <?php echo formatDate($client['updated_at'], 'Y-m-d H:i'); ?></p>
                        <?php endif; ?>
                        
                        <p class="mb-0"><small class="text-muted"><?php echo __('client_id') ?: 'ID'; ?>:</small><br>
                           #<?php echo $client['client_id']; ?></p>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <?php if (!empty($activities)): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo __('recent_activities') ?: 'Recent Activities'; ?></h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($activities as $activity): ?>
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-circle-fill text-primary" style="font-size: 0.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="fw-semibold"><?php echo sanitizeOutput($activity['activity_type']); ?></div>
                                <small class="text-muted"><?php echo formatDate($activity['activity_date'], 'Y-m-d H:i'); ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/crm-project/public/assets/js/clients.js"></script>
</body>
</html>